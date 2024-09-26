<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller as BaseController;
use OpenAI\Laravel\Facades\OpenAI;
use DateTime;

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

            $aiProvider = $step['ai_provider'];
            $model = $step['ai_model'];
            $temperature = $step['temperature'];

            $generatedText = $this->callAIProvider($aiProvider, $fullPrompt, $backgroundInfo, $model, $temperature, $processedInputData, $previousResults, $isImageFile);

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

    private function callAIProvider($provider, $prompt, $backgroundInfo, $model, $temperature, $processedInputData, $previousResults, $isImageFile)
    {
        Log::info("Calling {$provider} API", [
            'model' => $model,
            'temperature' => $temperature,
            'isImageFile' => $isImageFile
        ]);
    
        switch ($provider) {
            case 'openai':
                return $this->callOpenAI($prompt, $backgroundInfo, $model, $temperature, $processedInputData, $previousResults, $isImageFile);
            case 'anthropic':
                return $this->callAnthropic($prompt, $backgroundInfo, $model, $temperature);
            case 'gemini':
                return $this->callGemini($prompt, $backgroundInfo, $model, $temperature);
            case 'huggingface':
                return $this->callHuggingFace($prompt, $backgroundInfo, $model, $temperature);
            default:
                throw new \Exception("Unsupported AI provider: $provider");
        }
    }

    private function callOpenAI($prompt, $backgroundInfo, $model, $temperature, $processedInputData, $previousResults, $isImageFile)
    {
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
            $content = $response->choices[0]->message->content;
            // 줄바꿈 문자를 PHP_EOL로 변환
            $content = str_replace(["\r\n", "\r", "\n"], PHP_EOL, $content);
            // 연속된 줄바꿈을 하나로 줄임
            $content = preg_replace('/('.PHP_EOL.'){3,}/', PHP_EOL.PHP_EOL, $content);
            return $this->ensureValidUTF8($content);
        } else {
            throw new \Exception('Unexpected OpenAI API response structure');
        }
    }

    private function callAnthropic($prompt, $backgroundInfo, $model, $temperature)
    {
        $apiKey = env('ANTHROPIC_API_KEY');
        $apiVersion = $this->getAnthropicVersionFromModel($model);

        $response = Http::timeout(120)->withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $apiKey,
            'anthropic-version' => $apiVersion,
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $model,
            'system' => $this->ensureValidUTF8($backgroundInfo),
            'messages' => [
                ['role' => 'user', 'content' => $this->ensureValidUTF8($prompt)],
            ],
            'max_tokens' => 8000,
            'temperature' => $temperature,
        ]);

        if ($response->successful()) {
            $content = $response->json('content');
            if (is_array($content) && isset($content[0]['text'])) {
                return $this->ensureValidUTF8($content[0]['text']);
            } else {
                throw new \Exception('Unexpected Anthropic API response structure');
            }
        } else {
            Log::error('Anthropic API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'model' => $model,
                'apiVersion' => $apiVersion,
            ]);
            throw new \Exception('Anthropic API request failed: ' . $response->body());
        }
    }

    private function getAnthropicVersionFromModel($model)
    {
        // Extract the date from the model name
        if (preg_match('/(\d{8})$/', $model, $matches)) {
            $modelDate = $matches[1];
            $currentDate = new \DateTime();
            $modelDateTime = \DateTime::createFromFormat('Ymd', $modelDate);

            // If the model date is in the future, use the current date
            if ($modelDateTime > $currentDate) {
                $versionDate = $currentDate->format('Y-m-d');
            } else {
                $versionDate = $modelDateTime->format('Y-m-d');
            }

            // Anthropic API currently supports versions up to 2023-06-01
            $maxSupportedVersion = '2023-06-01';
            if ($versionDate > $maxSupportedVersion) {
                $versionDate = $maxSupportedVersion;
            }

            return $versionDate;
        }
        
        // If no date is found in the model name, return the max supported version
        return '2023-06-01';
    }

    private function callGemini($prompt, $backgroundInfo, $model, $temperature)
    {
        $apiKey = env('GEMINI_API_KEY');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $this->ensureValidUTF8($backgroundInfo . "\n\n" . $prompt)]]],
            ],
            'generationConfig' => [
                'temperature' => $temperature,
            ],
        ]);

        if ($response->successful()) {
            $content = $response->json('candidates.0.content.parts.0.text');
            return $this->ensureValidUTF8($content);
        } else {
            throw new \Exception('Google Gemini API request failed: ' . $response->body());
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
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        // Remove any BOM
        $string = preg_replace('/\x{FEFF}/u', '', $string);
        return $string;
    }

    public function setLanguage($language)
    {
        $this->currentLanguage = $language;
    }
}