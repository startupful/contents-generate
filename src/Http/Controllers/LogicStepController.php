<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class LogicStepController extends BaseController
{
    protected $jsonHelper;
    protected $fileManagerController;
    protected $utilityController;

    public function __construct(FileManagerController $fileManagerController, UtilityController $utilityController, JsonHelperController $jsonHelper)
    {
        $this->jsonHelper = $jsonHelper;
        $this->fileManagerController = $fileManagerController;
        $this->utilityController = $utilityController;
    }

    public function executeStep($step, $inputData, $previousResults, $stepIndex)
    {
        Log::info('Executing step', ['stepType' => $step['type'], 'stepIndex' => $stepIndex, 'inputData' => $this->jsonHelper->safeJsonEncode($inputData)]);

        $stepResult = match ($step['type']) {
            'input' => $this->processInputStep($inputData, $step),
            'scrap_webpage' => App::make(WebScrapingController::class)->scrapWebpage($step, $inputData, $stepIndex),
            'generate_text' => App::make(TextGenerationController::class)->generateText($step, $inputData, $previousResults),
            'generate_image' => App::make(ImageGenerationController::class)->generateImage($step, $inputData, $previousResults),
            'generate_excel' => App::make(ExcelGenerationController::class)->generateExcel($step, $inputData, $previousResults),
            'generate_ui_ux' => App::make(CodeGenerationController::class)->generateCode($step, $inputData, $previousResults),
            'generate_audio' => App::make(AudioGenerationController::class)->generateAudio($step, $inputData, $previousResults),
            'content_integration' => $this->processContentIntegration($step, $previousResults),
            default => throw new \Exception("Unsupported step type: {$step['type']}"),
        };

        return $stepResult;
    }

    private function processInputStep($inputData, $step)
    {
        Log::debug('Processing input step', ['step' => $this->jsonHelper->safeJsonEncode($step), 'inputData' => $this->jsonHelper->safeJsonEncode($inputData)]);
        
        $processedInput = [];
        foreach ($step['input_fields'] as $field) {
            $key = $field['label'];
            $value = $this->findInputValue($inputData, $key);
            
            Log::debug('Processing field', ['key' => $key, 'value' => $this->jsonHelper->safeJsonEncode($value)]);
            
            if ($field['type'] === 'file' && $value !== null) {
                $processedValue = $this->fileManagerController->processFileInput($value);
                if ($processedValue !== null) {
                    $processedInput[$key] = $processedValue;
                } else {
                    Log::warning('File processing failed', ['key' => $key, 'value' => $this->jsonHelper->safeJsonEncode($value)]);
                }
            } else {
                $processedInput[$key] = $value;
            }
        }
        
        Log::info('Processed input step', ['processedInput' => $this->jsonHelper->safeJsonEncode($processedInput)]);
        return $processedInput;
    }

    private function findInputValue($inputData, $label)
    {
        if (isset($inputData[$label])) {
            return $inputData[$label];
        }
        
        if (is_array($inputData) && isset($inputData['inputData'])) {
            if (is_array($inputData['inputData']) && isset($inputData['inputData'][$label])) {
                return $inputData['inputData'][$label];
            }
        }
        
        Log::warning('Input value not found', ['label' => $label]);
        return null;
    }

    private function processFileInput($filePath)
    {
        Log::debug('Processing file input', ['filePath' => $filePath]);
        return $this->fileManagerController->processFileInput($filePath);
    }

    private function processContentIntegration($step, $previousResults)
    {
        Log::info('Processing content integration', ['step' => $this->jsonHelper->safeJsonEncode($step)]);

        $contentTemplate = $step['content_template'] ?? '';
        $integratedContent = $this->utilityController->replacePlaceholders($contentTemplate, [], $previousResults);

        Log::info('Content integration completed', ['integratedContent' => $this->truncateForLog($integratedContent)]);

        return [
            'type' => 'content_integration',
            'result' => $integratedContent
        ];
    }

    private function truncateForLog($text, $length = 100)
    {
        if (is_string($text) && strlen($text) > $length) {
            return substr($text, 0, $length) . '...';
        }
        return $text;
    }
}