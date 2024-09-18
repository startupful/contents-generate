<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
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

        switch ($aiProvider) {
            case 'openai':
                return $this->callOpenAI($aiPrompt, $systemMessage, $aiModel, $temperature);
            case 'anthropic':
                return $this->callAnthropic($aiPrompt, $systemMessage, $aiModel, $temperature);
            default:
                throw new \Exception("Unsupported AI provider: {$aiProvider}");
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

    private function callAnthropic($prompt, $systemMessage, $model, $temperature)
    {
        // Implement Anthropic API call here
        throw new \Exception("Anthropic API call not implemented");
    }

    private function parseAndCleanGeneratedContent($content, $columnInfo)
    {
        // Remove any potential markdown code block
        $content = preg_replace('/```csv\s*([\s\S]*?)\s*```/', '$1', $content);
        $content = trim($content);

        // Parse CSV
        $rows = str_getcsv($content, "\n");
        $data = array_map('str_getcsv', $rows);

        // Clean and validate data
        $headers = array_shift($data);
        $expectedHeaders = array_column($columnInfo, 'name');
        
        if ($headers !== $expectedHeaders) {
            throw new \Exception("Generated content does not match expected format. Expected headers: " . implode(', ', $expectedHeaders) . ". Received headers: " . implode(', ', $headers));
        }

        $cleanedData = array_map(function($row) use ($headers) {
            // Ensure each row has the correct number of columns
            $row = array_pad($row, count($headers), '');
            // Create associative array using headers as keys
            return array_combine($headers, $this->cleanFields($row));
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
        $startRow = 2; // Start from row 2 as row 1 is for headers
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