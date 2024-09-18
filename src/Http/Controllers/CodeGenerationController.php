<?php

namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
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

            $messages = $this->prepareMessages($backgroundInfo, $prompt, $previousResults);

            Log::info("Prepared messages for code generation", [
                'messages' => json_encode($messages),  // 전체 메시지 구조를 로그에 기록
            ]);    

            $model = $step['ai_model'] ?? 'gpt-4-vision-preview';
            $temperature = $step['temperature'] ?? 0.7;

            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature,
                'max_tokens' => 4000
            ]);

            if (isset($response->choices[0]->message->content)) {
                $generatedCode = $response->choices[0]->message->content;
                
                // Extract only the HTML content
                $generatedCode = $this->extractHtmlContent($generatedCode);
                
                // Replace any remaining placeholders in the generated code
                $generatedCode = $this->utilityController->replacePlaceholders($generatedCode, $inputData, $previousResults, $inputFields);

                Log::info("Code generation completed", ['generatedCode' => substr($generatedCode, 0, 100) . '...']);

                return $generatedCode;
            } else {
                throw new \Exception('Unexpected OpenAI API response structure');
            }
        } catch (\Exception $e) {
            Log::error('Error in generateCode method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function prepareMessages($backgroundInfo, $prompt, $previousResults)
    {
        $messages = [
            ['role' => 'system', 'content' => $backgroundInfo],
            ['role' => 'user', 'content' => []]
        ];

        $messages[1]['content'][] = [
            'type' => 'text',
            'text' => "You are a front-end developer. Your task is to replicate the layout of the provided image exactly as it appears. Use Tailwind CSS, Bootstrap Icons, jQuery, and Alpine.js as needed. Ensure the layout includes the following: - Use placeholder images from Unsplash or placeholder.com for any missing images in the provided layout. - Identify the overall layout structure and replicate it accurately. - Do not include any text descriptions or explanations, only provide the code.\n\n" . $prompt
        ];

        // 이미지 URL 찾기
        $imageUrl = null;
        if (isset($previousResults['step_1']['result']['image']['url'])) {
            $imageUrl = $previousResults['step_1']['result']['image']['url'];
        } elseif (isset($previousResults['step_1']['result']['image']['path'])) {
            // 상대 경로를 절대 URL로 변환
            $imageUrl = url('storage/' . $previousResults['step_1']['result']['image']['path']);
        }

        if ($imageUrl) {
            $messages[1]['content'][] = [
                'type' => 'image_url',
                'image_url' => ['url' => $imageUrl]
            ];
        } else {
            Log::warning("Image URL not found in previous results", ['previousResults' => $previousResults]);
        }

        return $messages;
    }

    private function extractHtmlContent($content)
    {
        // Remove markdown code block indicators
        $content = preg_replace('/```html\s*|\s*```/', '', $content);
        
        // Remove any leading or trailing whitespace
        $content = trim($content);

        return $content;
    }
}