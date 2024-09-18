<?php

namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class ProcessController extends BaseController
{
    protected $jsonHelper;

    public function __construct(
        JsonHelperController $jsonHelper,
        private LogicStepController $logicStepController,
        private StorageController $storageController
    ) {
        $this->jsonHelper = $jsonHelper;
    }

    public function process(Request $request)
    {
        try {
            $steps = $request->input('steps');
            $inputData = $request->input('inputData');
            $logicId = $request->input('logic_id');

            Log::info('Starting to process logic', [
                'steps' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($steps)),
                'inputData' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($inputData)),
                'logicId' => $logicId
            ]);

            $result = [];

            foreach ($steps as $stepIndex => $step) {
                try {
                    $stepNumber = $stepIndex + 1;
                    Log::info("Executing step {$stepNumber}", ['step' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($step))]);
                    
                    $stepResult = $this->logicStepController->executeStep($step, $inputData, $result, $stepNumber);
                    
                    $result["step_{$stepNumber}"] = [
                        'type' => $step['type'],
                        'result' => $stepResult
                    ];
                    
                    Log::info("Step {$stepNumber} executed successfully", [
                        'result' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($result["step_{$stepNumber}"]), 1000, ['Output'])
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error executing step {$stepNumber}", [
                        'error' => $e->getMessage(),
                        'step' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($step)),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return response()->json(['error' => "Error in step {$stepNumber}: " . $e->getMessage()], 500);
                }
            }

            $content = $this->storageController->storeGeneratedContent($result, $steps, $logicId);

            Log::info('Logic processing completed successfully', ['content' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($content))]);
            return response()->json(['success' => true, 'message' => 'Content generated and stored successfully']);
        } catch (\Exception $e) {
            Log::error('Error processing logic', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function truncateForLog($text, $length = 100, $excludeKeys = [])
    {
        $data = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $excludeKeys) && is_string($value) && strlen($value) > $length) {
                    $data[$key] = substr($value, 0, $length) . '...';
                }
            }
            return $this->jsonHelper->safeJsonEncode($data);
        }
        
        if (is_string($text) && strlen($text) > $length) {
            return substr($text, 0, $length) . '...';
        }
        return $text;
    }

    private function isImageFile($path)
    {
        $mimeType = Storage::mimeType($path);
        return strpos($mimeType, 'image/') === 0;
    }
}