<?php

namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

class CodeGenerationController extends BaseController
{
    protected $utilityController;
    protected $fileManagerController;

    public function __construct(UtilityController $utilityController, FileManagerController $fileManagerController)
    {
        $this->utilityController = $utilityController;
        $this->fileManagerController = $fileManagerController;
    }

    public function generateCode($step, $inputData, $previousResults)
    {
        try {
            Log::info("Starting code generation", ['step' => $step, 'inputData' => $inputData, 'previousResults' => $previousResults]);

            $inputFields = $step['input_fields'] ?? [];
            if (!is_array($inputFields)) {
                $inputFields = [$inputFields];
            }

            $prompt = $this->utilityController->replacePlaceholders($step['prompt'], $inputData, $previousResults, $inputFields);
            $backgroundInfo = $this->utilityController->replacePlaceholders($step['background_information'], $inputData, $previousResults, $inputFields);

            $referenceFile = null;
            if (isset($step['reference_file'])) {
                $referenceFile = $this->utilityController->replacePlaceholders($step['reference_file'], $inputData, $previousResults, $inputFields);
            }

            $messages = $this->prepareMessages($backgroundInfo, $prompt, $previousResults, $referenceFile);

            Log::info("Prepared messages for code generation", [
                'messages' => json_encode($messages),
                'referenceFile' => $referenceFile
            ]);    

            $aiProvider = $step['ai_provider'] ?? 'openai';
            $model = $step['ai_model'] ?? 'gpt-4-vision-preview';
            $temperature = $step['temperature'] ?? 0.7;

            $generatedCode = $this->callAIProvider($aiProvider, $messages, $model, $temperature);

            // Extract only the HTML content
            $generatedCode = $this->extractHtmlContent($generatedCode);
            
            // Replace any remaining placeholders in the generated code
            $generatedCode = $this->utilityController->replacePlaceholders($generatedCode, $inputData, $previousResults, $inputFields);

            Log::info("Code generation completed", ['generatedCode' => substr($generatedCode, 0, 100) . '...']);

            return $generatedCode;
        } catch (\Exception $e) {
            Log::error('Error in generateCode method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function callAIProvider($provider, $messages, $model, $temperature)
    {
        Log::info("Calling {$provider} API", [
            'model' => $model,
            'temperature' => $temperature,
        ]);

        switch ($provider) {
            case 'openai':
                return $this->callOpenAI($messages, $model, $temperature);
            case 'anthropic':
                return $this->callAnthropic($messages, $model, $temperature);
            case 'gemini':
                return $this->callGemini($messages, $model, $temperature);
            default:
                throw new \Exception("Unsupported AI provider: $provider");
        }
    }

    private function callOpenAI($messages, $model, $temperature)
    {
        $response = OpenAI::chat()->create([
            'model' => $model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => 4000
        ]);

        if (isset($response->choices[0]->message->content)) {
            return $response->choices[0]->message->content;
        } else {
            throw new \Exception('Unexpected OpenAI API response structure');
        }
    }

    private function callAnthropic($messages, $model, $temperature)
    {
        $apiKey = env('ANTHROPIC_API_KEY');
        $apiVersion = $this->getAnthropicVersionFromModel($model);

        $systemMessage = '';
        $userMessage = [];
        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemMessage .= $message['content'] . "\n";
            } elseif ($message['role'] === 'user') {
                foreach ($message['content'] as $content) {
                    if ($content['type'] === 'text') {
                        $userMessage[] = ['type' => 'text', 'text' => $content['text']];
                    } elseif ($content['type'] === 'image_url') {
                        $imageData = $this->getImageData($content['image_url']['url']);
                        if ($imageData) {
                            $userMessage[] = [
                                'type' => 'image',
                                'source' => [
                                    'type' => 'base64',
                                    'media_type' => $imageData['mime_type'],
                                    'data' => $imageData['base64']
                                ]
                            ];
                        }
                    }
                }
            }
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $apiKey,
            'anthropic-version' => $apiVersion,
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $model,
            'system' => $systemMessage,
            'messages' => [
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 4000,
            'temperature' => $temperature,
        ]);

        if ($response->successful()) {
            $content = $response->json('content');
            if (is_array($content) && isset($content[0]['text'])) {
                return $content[0]['text'];
            } else {
                throw new \Exception('Unexpected Anthropic API response structure');
            }
        } else {
            $errorMessage = $response->json('error.message') ?? $response->body();
            throw new \Exception('Anthropic API request failed: ' . $errorMessage);
        }
    }

    private function callGemini($messages, $model, $temperature)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        $prompt = '';
        $imageContent = null;
        foreach ($messages as $message) {
            if ($message['role'] === 'user') {
                foreach ($message['content'] as $content) {
                    if ($content['type'] === 'text') {
                        $prompt .= $content['text'] . "\n";
                    } elseif ($content['type'] === 'image_url') {
                        $imageData = $this->getImageData($content['image_url']['url']);
                        if ($imageData) {
                            $imageContent = $imageData;
                        }
                    }
                }
            }
        }

        $requestBody = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $temperature,
                'maxOutputTokens' => 4000,
            ],
        ];

        if ($imageContent) {
            $requestBody['contents'][0]['parts'][] = [
                'inline_data' => [
                    'mime_type' => $imageContent['mime_type'],
                    'data' => $imageContent['base64']
                ]
            ];
        }

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

    private function getImageData($url)
    {
        try {
            $imageContent = file_get_contents($url);
            if ($imageContent === false) {
                Log::error("Failed to fetch image content", ['url' => $url]);
                return null;
            }
            
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($imageContent);
            
            return [
                'mime_type' => $mimeType,
                'base64' => base64_encode($imageContent)
            ];
        } catch (\Exception $e) {
            Log::error("Error fetching image data", ['url' => $url, 'error' => $e->getMessage()]);
            return null;
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

    private function prepareMessages($backgroundInfo, $prompt, $previousResults, $referenceFile = null)
    {
        $messages = [
            ['role' => 'system', 'content' => $backgroundInfo],
            ['role' => 'user', 'content' => []]
        ];

        $messages[1]['content'][] = [
            'type' => 'text',
            'text' => "You are a front-end developer. Your task is to replicate the layout of the provided image exactly as it appears. Use Tailwind CSS, Bootstrap Icons, jQuery, and Alpine.js as needed. Ensure the layout includes the following: - Use placeholder images from Unsplash or placeholder.com for any missing images in the provided layout. - Identify the overall layout structure and replicate it accurately. - Do not include any text descriptions or explanations, only provide the code.\n\n" . $prompt
        ];

        $imageUrl = $this->getImageUrl($referenceFile, $previousResults);

        if ($imageUrl) {
            $messages[1]['content'][] = [
                'type' => 'image_url',
                'image_url' => ['url' => $imageUrl]
            ];
            Log::info("Image URL found and added to messages", ['imageUrl' => $imageUrl]);
        } else {
            Log::warning("Image URL not found", [
                'referenceFile' => $referenceFile,
                'previousResults' => $previousResults,
                'step_1_result' => $previousResults['step_1']['result'] ?? 'Not available'
            ]);
        }

        return $messages;
    }

    private function getImageUrl($referenceFile, $previousResults)
    {
        Log::debug("Getting image URL", ['referenceFile' => $referenceFile, 'previousResults' => $previousResults]);

        if (filter_var($referenceFile, FILTER_VALIDATE_URL)) {
            Log::info("Reference file is already a valid URL", ['url' => $referenceFile]);
            return $referenceFile;
        }

        // Extract the key from the placeholder
        preg_match('/\{\{step1\.input\.(\w+)\}\}/', $referenceFile, $matches);
        if (!empty($matches[1])) {
            $key = $matches[1];
            Log::debug("Extracted key from reference file", ['key' => $key]);

            if (isset($previousResults['step_1']['result'][$key]['url'])) {
                $url = $previousResults['step_1']['result'][$key]['url'];
                Log::info("Found URL in previous results", ['url' => $url]);
                return $url;
            } elseif (isset($previousResults['step_1']['result'][$key]['path'])) {
                $url = url('storage/' . $previousResults['step_1']['result'][$key]['path']);
                Log::info("Constructed URL from path", ['url' => $url]);
                return $url;
            } else {
                Log::warning("Key found but no valid URL or path", ['key' => $key, 'previousResults' => $previousResults['step_1']['result'][$key] ?? null]);
            }
        } else {
            Log::warning("Failed to extract key from reference file", ['referenceFile' => $referenceFile]);
        }

        Log::warning("Unable to find or construct image URL", ['referenceFile' => $referenceFile, 'previousResults' => $previousResults]);
        return null;
    }

    private function extractHtmlContent($content)
    {
        // Remove markdown code block indicators
        $content = preg_replace('/```html\s*|\s*```/', '', $content);
        
        // Remove any leading or trailing whitespace
        $content = trim($content);

        // Normalize line breaks to LF
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        return $content;
    }
}