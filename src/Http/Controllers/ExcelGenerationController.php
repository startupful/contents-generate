<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use OpenAI\Laravel\Facades\OpenAI;

class ExcelGenerationController extends BaseController
{
    protected $utilityController;

    public function __construct(UtilityController $utilityController)
    {
        $this->utilityController = $utilityController;
    }

    public function generateExcel($step, $inputData, $previousResults)
    {
        try {
            $prompt = $step['prompt'];
            $backgroundInfo = $step['background_information'];

            // Correctly structure inputFields
            $inputFields = [];
            if (isset($previousResults['step_0']) && is_array($previousResults['step_0'])) {
                foreach ($previousResults['step_0'] as $key => $value) {
                    $inputFields[] = ['label' => $key];
                }
            }

            Log::info("Input fields structured", ['inputFields' => $inputFields]);

            $prompt = $this->utilityController->replacePlaceholders($prompt, $inputData, $previousResults, $inputFields);
            $backgroundInfo = $this->utilityController->replacePlaceholders($backgroundInfo, $inputData, $previousResults, $inputFields);

            Log::info("Prepared prompt for Excel generation", [
                'prompt' => $prompt,
                'backgroundInfo' => $backgroundInfo,
                'inputData' => $inputData,
                'previousResults' => $previousResults,
                'inputFields' => $inputFields
            ]);

            $columnInfo = $this->getColumnInfo($step['excel_columns'][0]['columns']);

            // Generate content using AI
            $generatedContent = $this->generateContentForExcel($prompt, $backgroundInfo, $columnInfo, $step['ai_provider'], $step['ai_model'], $step['temperature'], $inputData);

            // Parse and clean the generated content
            $parsedData = $this->parseAndCleanGeneratedContent($generatedContent, $columnInfo);

            // Create Excel file
            $excelFile = $this->createExcelFile($parsedData, $step['excel_columns'][0]);

            return $excelFile;
        } catch (\Exception $e) {
            Log::error('Error in generateExcel method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function getColumnInfo($columns)
    {
        $columnInfo = [];
        foreach ($columns as $column) {
            $columnInfo[] = [
                'name' => $column['name'],
                'description' => $column['description']
            ];
        }
        return $columnInfo;
    }

    private function generateContentForExcel($prompt, $backgroundInfo, $columnInfo, $aiProvider, $aiModel, $temperature, $inputData)
    {
        $columnNames = array_column($columnInfo, 'name');
        $columnDescriptions = array_column($columnInfo, 'description');

        $aiPrompt = $prompt . "\n\n" .
                    "Please provide the output as CSV data, with the following headers in the first row:\n" .
                    implode(', ', $columnNames) . "\n\n" .
                    "Column descriptions:\n" .
                    implode("\n", array_map(function($name, $desc) {
                        return "$name: $desc";
                    }, $columnNames, $columnDescriptions)) . "\n\n" .
                    "Ensure that each field is properly quoted, especially if it contains commas.";

        $systemMessage = $backgroundInfo . "\n\n" .
                        "You are a helpful assistant that generates Excel data in CSV format. " .
                        "Ensure proper handling of special characters and formatting. " .
                        "Provide only the CSV data without any additional text or markdown formatting. " .
                        "Always enclose each field in double quotes, and escape any double quotes within fields by doubling them.";

        $maxRetries = 3;
        $retryDelay = 5000; // 5 seconds

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                Log::info("Calling {$aiProvider} API (Attempt $attempt/$maxRetries)", [
                    'model' => $aiModel,
                    'temperature' => $temperature,
                ]);

                switch ($aiProvider) {
                    case 'openai':
                        return $this->callOpenAI($aiPrompt, $systemMessage, $aiModel, $temperature);
                    case 'anthropic':
                        return $this->callAnthropic($aiPrompt, $systemMessage, $aiModel, $temperature, $columnInfo);
                    case 'gemini':
                        return $this->callGemini($aiPrompt, $systemMessage, $aiModel, $temperature);
                    default:
                        throw new \Exception("Unsupported AI provider: {$aiProvider}");
                }
            } catch (\Exception $e) {
                Log::warning("Error calling {$aiProvider} API (Attempt $attempt/$maxRetries)", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                if ($attempt < $maxRetries) {
                    Log::info("Retrying in {$retryDelay}ms...");
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2; // Exponential backoff
                } else {
                    Log::error("All attempts to call {$aiProvider} API failed", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new \Exception("Error calling {$aiProvider} API after " . $maxRetries . " attempts: " . $e->getMessage());
                }
            }
        }
    }

    private function callOpenAI($prompt, $systemMessage, $model, $temperature)
    {
        $response = OpenAI::chat()->create([
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemMessage],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => $temperature,
        ]);

        return $response->choices[0]->message->content;
    }

    private function callAnthropic($prompt, $systemMessage, $model, $temperature, $columnInfo)
    {
        $apiKey = env('ANTHROPIC_API_KEY');
        $apiVersion = $this->getAnthropicVersionFromModel($model);

        // 컬럼 정보를 포함한 상세한 지시사항 생성
        $detailedInstructions = $this->generateDetailedInstructions($columnInfo);

        $fullPrompt = $systemMessage . "\n\n" . $detailedInstructions . "\n\n" . $prompt . "\n\nPlease provide the output as properly formatted CSV data.";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $apiKey,
            'anthropic-version' => $apiVersion,
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $fullPrompt],
            ],
            'max_tokens' => 4000,
            'temperature' => $temperature,
        ]);

        if ($response->successful()) {
            $content = $response->json('content');
            if (is_array($content) && isset($content[0]['text'])) {
                return $this->extractCSVFromClaudeResponse($content[0]['text']);
            } else {
                throw new \Exception('Unexpected Anthropic API response structure');
            }
        } else {
            $errorMessage = $response->json('error.message') ?? $response->body();
            throw new \Exception('Anthropic API request failed: ' . $errorMessage);
        }
    }

    private function generateDetailedInstructions($columnInfo)
    {
        $instructions = "Please generate an Excel sheet with the following columns:\n\n";
        foreach ($columnInfo as $column) {
            $instructions .= "- {$column['name']}: {$column['description']}\n";
        }
        $instructions .= "\nEnsure that each row in the generated data corresponds to these columns in order. Provide the data in CSV format, with each field properly quoted and separated by commas. Include a header row with the column names.";
        return $instructions;
    }

    private function callGemini($prompt, $systemMessage, $model, $temperature)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        $requestBody = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $systemMessage . "\n\n" . $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $temperature,
                'maxOutputTokens' => 4000,
            ],
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", $requestBody);

        if ($response->successful()) {
            $content = $response->json('candidates.0.content.parts.0.text');
            if ($content) {
                return $content;
            } else {
                throw new \Exception('Unexpected Gemini API response structure');
            }
        } else {
            $errorMessage = $response->json('error.message') ?? $response->body();
            throw new \Exception('Google Gemini API request failed: ' . $errorMessage);
        }
    }

    private function getAnthropicVersionFromModel($model)
    {
        if (preg_match('/(\d{8})$/', $model, $matches)) {
            $modelDate = $matches[1];
            $currentDate = new \DateTime();
            $modelDateTime = \DateTime::createFromFormat('Ymd', $modelDate);

            if ($modelDateTime > $currentDate) {
                $versionDate = $currentDate->format('Y-m-d');
            } else {
                $versionDate = $modelDateTime->format('Y-m-d');
            }

            $maxSupportedVersion = '2023-06-01';
            if ($versionDate > $maxSupportedVersion) {
                $versionDate = $maxSupportedVersion;
            }

            return $versionDate;
        }
        
        return '2023-06-01';
    }

    private function extractCSVFromClaudeResponse($response)
    {
        // CSV 데이터를 포함하는 가장 큰 블록을 찾습니다
        preg_match_all('/```(?:csv)?\s*([\s\S]*?)\s*```/', $response, $matches);
        
        if (!empty($matches[1])) {
            // 가장 긴 CSV 블록을 선택합니다
            $csvContent = max($matches[1], function($a, $b) {
                return strlen($a) > strlen($b) ? $a : $b;
            });
        } else {
            // CSV 블록이 없으면 전체 응답을 사용합니다
            $csvContent = $response;
        }

        return trim($csvContent);
    }

    private function parseAndCleanGeneratedContent($content, $columnInfo)
    {
        // 기존 코드를 유지하되, CSV 파싱 로직을 강화합니다
        $content = $this->extractCSVFromClaudeResponse($content);
        
        // CSV를 파싱합니다
        $rows = str_getcsv($content, "\n");
        $data = array_map(function($row) {
            return str_getcsv($row, ",", '"', "\\");
        }, $rows);

        // 헤더를 확인하고 정리합니다
        $headers = array_shift($data);
        $expectedHeaders = array_column($columnInfo, 'name');
        
        if (count($headers) !== count($expectedHeaders) || array_diff($headers, $expectedHeaders)) {
            Log::warning("Generated headers do not match expected headers", [
                'expected' => $expectedHeaders,
                'received' => $headers
            ]);
            // 헤더가 일치하지 않으면 예상된 헤더를 사용합니다
            array_unshift($data, $expectedHeaders);
        } else {
            array_unshift($data, $headers);
        }

        // 데이터를 정리합니다
        $cleanedData = array_map(function($row) use ($expectedHeaders) {
            // 각 행이 올바른 수의 열을 가지도록 합니다
            $row = array_pad($row, count($expectedHeaders), '');
            // 헤더를 키로 사용하여 연관 배열을 만듭니다
            return array_combine($expectedHeaders, $this->cleanFields($row));
        }, $data);

        return $cleanedData;
    }

    private function cleanFields($fields)
    {
        return array_map(function($field) {
            // Remove any surrounding whitespace
            $field = trim($field);
            // Remove any surrounding quotes (they'll be added back when writing to Excel)
            $field = trim($field, '"');
            // Escape any remaining double quotes by doubling them
            $field = str_replace('"', '""', $field);
            return $field;
        }, $fields);
    }

    private function createExcelFile($parsedData, $excelConfig)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($excelConfig['sheet_name']);

        // Apply global header styles
        $this->applyGlobalHeaderStyles($sheet, $excelConfig, count($excelConfig['columns']));

        // Set headers and apply column styles
        foreach ($excelConfig['columns'] as $colIndex => $columnConfig) {
            $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 2);  // Start from column B
            $sheet->setCellValue($columnLetter . '1', $columnConfig['name']);
            $this->applyColumnStyles($sheet, $columnLetter, $columnConfig);
        }

        // Populate data
        $startRow = 1; // Start from row 2 as row 1 is for headers
        foreach ($parsedData as $rowIndex => $rowData) {
            foreach ($excelConfig['columns'] as $colIndex => $columnConfig) {
                $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 2);  // Start from column B
                $cellValue = $rowData[$columnConfig['name']] ?? ''; // Use column name to get the correct data
                $sheet->setCellValue($columnLetter . ($rowIndex + $startRow), $cellValue);
            }
        }

        // Apply cell merging
        $this->applyCellMerging($sheet, $excelConfig['columns'], count($parsedData) + $startRow - 1);

        // Generate file
        $writer = new Xlsx($spreadsheet);
        $filename = 'generated_excel_' . time() . '.xlsx';
        $path = storage_path('app/public/' . $filename);
        $writer->save($path);

        return [
            'file_path' => $path,
            'file_name' => $filename,
        ];
    }

    private function applyGlobalHeaderStyles($sheet, $excelConfig, $columnCount)
    {
        $lastColumn = Coordinate::stringFromColumnIndex($columnCount + 1);  // +1 because we start from column B
        $headerRange = 'A1:' . $lastColumn . '1';
        $headerStyle = $sheet->getStyle($headerRange);

        // Apply background color
        $headerStyle->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB(substr($excelConfig['global_header_background'], 1));

        // Apply text color
        $headerStyle->getFont()
            ->getColor()->setARGB(substr($excelConfig['global_header_text_color'], 1));

        // Apply height
        $sheet->getRowDimension(1)->setRowHeight($excelConfig['global_header_height']);

        // Apply alignment
        $headerStyle->getAlignment()
            ->setHorizontal($this->getAlignmentConstant($excelConfig['global_header_alignment']));
    }

    private function applyColumnStyles($sheet, $columnLetter, $columnConfig)
    {
        // Set column width
        if (isset($columnConfig['width']) && is_numeric($columnConfig['width'])) {
            $sheet->getColumnDimension($columnLetter)->setWidth((float)$columnConfig['width']);
        }

        // Set alignment for the entire column
        if (isset($columnConfig['alignment'])) {
            $sheet->getStyle($columnLetter . '1:' . $columnLetter . $sheet->getHighestRow())
                ->getAlignment()->setHorizontal($this->getAlignmentConstant($columnConfig['alignment']));
        }

        // Apply header background and text color if specified
        if (isset($columnConfig['header_background'])) {
            $sheet->getStyle($columnLetter . '1')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB(substr($columnConfig['header_background'], 1));
        }
        if (isset($columnConfig['header_text_color'])) {
            $sheet->getStyle($columnLetter . '1')->getFont()
                ->getColor()->setARGB(substr($columnConfig['header_text_color'], 1));
        }
    }

    private function applyCellMerging($sheet, $columnConfig, $lastRow)
    {
        foreach ($columnConfig as $index => $column) {
            if (isset($column['merge_duplicates']) && $column['merge_duplicates']) {
                $columnLetter = Coordinate::stringFromColumnIndex($index + 2);  // Start from column B
                $this->mergeDuplicates($sheet, $columnLetter, $lastRow);
            }
        }
    }

    private function getAlignmentConstant($alignment)
    {
        switch (strtolower($alignment)) {
            case 'left':
                return Alignment::HORIZONTAL_LEFT;
            case 'center':
                return Alignment::HORIZONTAL_CENTER;
            case 'right':
                return Alignment::HORIZONTAL_RIGHT;
            default:
                return Alignment::HORIZONTAL_GENERAL;
        }
    }

    private function mergeDuplicates($sheet, $columnLetter, $highestRow)
    {
        $prevValue = null;
        $mergeStart = 2;

        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell($columnLetter . $row)->getValue();

            if ($cellValue !== $prevValue) {
                if ($row - $mergeStart > 1) {
                    $sheet->mergeCells($columnLetter . $mergeStart . ':' . $columnLetter . ($row - 1));
                }
                $prevValue = $cellValue;
                $mergeStart = $row;
            }
        }

        // Handle the last merge
        if ($highestRow - $mergeStart > 0) {
            $sheet->mergeCells($columnLetter . $mergeStart . ':' . $columnLetter . $highestRow);
        }
    }
}