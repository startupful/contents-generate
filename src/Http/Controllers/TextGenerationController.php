<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller as BaseController;
use OpenAI\Laravel\Facades\OpenAI;

class TextGenerationController extends BaseController
{
    protected $utilityController;
    protected $fileManagerController;
    protected $currentLanguage;

    public function __construct(UtilityController $utilityController, FileManagerController $fileManagerController)
    {
        $this->utilityController = $utilityController;
        $this->fileManagerController = $fileManagerController;
        $this->currentLanguage = config('app.locale', 'ko');
    }

    public function generateText($step, $inputData, $previousResults)
    {
        try {
            Log::info("Starting text generation", ['step' => $step, 'inputData' => $inputData, 'previousResults' => $previousResults]);

            $processedInputData = $this->processFileInputs($inputData);
            $isImageFile = $this->isImageFile($processedInputData);

            $inputFields = $step['input_fields'] ?? [];
            if (!is_array($inputFields)) {
                $inputFields = [$inputFields];
            }

            $prompt = $this->utilityController->replacePlaceholders($step['prompt'], $processedInputData, $previousResults, $step['input_fields'] ?? []);
            $backgroundInfo = $this->utilityController->replacePlaceholders($step['background_information'], $processedInputData, $previousResults, $step['input_fields'] ?? []);

            $fullPrompt = "Please generate the content in {$this->currentLanguage} language.\n\n" . $prompt;

            // Handle reference file only if it's not an image
            if (!$isImageFile) {
                $referenceFile = $step['reference_file'] ?? null;
                if ($referenceFile) {
                    $referenceFile = $this->utilityController->replacePlaceholders($referenceFile, $processedInputData, $previousResults);
                    if (isset($processedInputData['file']) && isset($processedInputData['file']['path'])) {
                        $filePath = $processedInputData['file']['path'];
                        try {
                            $fileContent = $this->fileManagerController->getFileContent($filePath);
                            $fullPrompt .= "\n\nReference file content:\n" . $fileContent;
                            Log::info("File content added to prompt", ['filePath' => $filePath, 'contentLength' => strlen($fileContent)]);
                        } catch (\Exception $e) {
                            Log::error('Error extracting file content', ['error' => $e->getMessage(), 'file' => $filePath]);
                        }
                    }
                }
            }

            Log::info("Prepared prompt for text generation", [
                'prompt' => $fullPrompt,
                'isImageFile' => $isImageFile,
            ]);

            $model = $step['ai_model'];
            $temperature = $step['temperature'];

            $generatedText = $this->callOpenAI($fullPrompt, $backgroundInfo, $model, $temperature, $processedInputData, $previousResults, $isImageFile);

            Log::info("Text generation completed", ['generatedText' => substr($generatedText, 0, 100) . '...']);

            return $generatedText;
        } catch (\Exception $e) {
            Log::error('Error in generateText method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function processFileInputs($inputData)
    {
        $processedData = [];
        foreach ($inputData as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $processedData[$key] = $this->fileManagerController->processFileInput($value);
            } else {
                $processedData[$key] = $value;
            }
        }
        return $processedData;
    }

    private function callOpenAI($prompt, $backgroundInfo, $model, $temperature, $processedInputData, $previousResults, $isImageFile)
    {
        $maxRetries = 3;
        $retryDelay = 5000; // 5 seconds

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                Log::info("Calling OpenAI API (Attempt $attempt/$maxRetries)", [
                    'model' => $model,
                    'temperature' => $temperature,
                    'isImageFile' => $isImageFile
                ]);

                $messages = [
                    ['role' => 'system', 'content' => $this->ensureValidUTF8($backgroundInfo)],
                    ['role' => 'user', 'content' => $this->ensureValidUTF8($prompt)]
                ];

                if ($isImageFile) {
                    $imageUrl = $this->getImageUrl($processedInputData, $previousResults);
                    if ($imageUrl) {
                        $messages[1]['content'] = [
                            ['type' => 'text', 'text' => $this->ensureValidUTF8($prompt)],
                            ['type' => 'image_url', 'image_url' => ['url' => $imageUrl]]
                        ];
                        Log::info("Added image URL to OpenAI request", ['imageUrl' => $imageUrl]);
                    }
                }

                Log::debug('OpenAI API request', ['messages' => $messages]);

                $response = OpenAI::chat()->create([
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => $temperature,
                ]);

                if (isset($response->choices[0]->message->content)) {
                    return $this->ensureValidUTF8($response->choices[0]->message->content);
                } else {
                    throw new \Exception('Unexpected OpenAI API response structure');
                }
            } catch (\Exception $e) {
                Log::warning("Error calling OpenAI API (Attempt $attempt/$maxRetries)", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                if ($attempt < $maxRetries) {
                    Log::info("Retrying in {$retryDelay}ms...");
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2; // Exponential backoff
                } else {
                    Log::error('All attempts to call OpenAI API failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new \Exception('Error calling OpenAI API after ' . $maxRetries . ' attempts: ' . $e->getMessage());
                }
            }
        }
    }

    private function getImageUrl($processedInputData, $previousResults)
    {
        // Check in processed input data
        if (isset($processedInputData['file']['url'])) {
            return $processedInputData['file']['url'];
        }

        // Check in previous results
        if (isset($previousResults['step_0']['file']['url'])) {
            return $previousResults['step_0']['file']['url'];
        }

        return null;
    }

    private function isImageFile($fileData)
    {
        return isset($fileData['file']['mime_type']) && strpos($fileData['file']['mime_type'], 'image/') === 0;
    }

    private function ensureValidUTF8($string)
    {
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }

    public function setLanguage($language)
    {
        $this->currentLanguage = $language;
    }
}