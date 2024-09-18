<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ImageGenerationController extends BaseController
{
    protected $utilityController;

    public function __construct(UtilityController $utilityController)
    {
        $this->utilityController = $utilityController;
    }

    public function generateImage($step, $inputData, $previousResults)
    {
        try {
            $prompt = $step['prompt'];
            $backgroundInfo = $step['background_information'];
            $inputFields = $step['input_fields'] ?? [];

            // Replace placeholders in prompt and background info
            $prompt = $this->utilityController->replacePlaceholders($prompt, $inputData, $previousResults, $inputFields);
            $backgroundInfo = $this->utilityController->replacePlaceholders($backgroundInfo, $inputData, $previousResults, $inputFields);

            // Combine background info and prompt
            $fullPrompt = $backgroundInfo . "\n\n" . $prompt;

            // Sanitize the combined prompt
            $sanitizedPrompt = $this->sanitizePrompt($fullPrompt);

            Log::info("Prepared prompt for image generation", [
                'originalPrompt' => $fullPrompt,
                'sanitizedPrompt' => $sanitizedPrompt,
                'inputData' => $inputData,
            ]);

            $imageResult = $this->callOpenAIImage($sanitizedPrompt, $step['temperature']);

            // Add both original and sanitized prompts to the result
            $imageResult['originalPrompt'] = $fullPrompt;
            $imageResult['sanitizedPrompt'] = $sanitizedPrompt;

            return $imageResult;
        } catch (\Exception $e) {
            Log::error('Error in generateImage method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Return a default image or error message
            return [
                'error' => true,
                'message' => 'Failed to generate image. Using default image.',
                'file_path' => '/path/to/default/image.png',
                'file_name' => 'default_image.png',
                'url' => '/url/to/default/image.png',
                'originalPrompt' => $fullPrompt ?? 'N/A',
                'sanitizedPrompt' => $sanitizedPrompt ?? 'N/A'
            ];
        }
    }

    private function callOpenAIImage($prompt, $temperature)
    {
        try {
            $response = OpenAI::images()->create([
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024',
                'response_format' => 'url',
            ]);

            if (isset($response->data[0]->url)) {
                $imageUrl = $response->data[0]->url;
                
                $imageContent = file_get_contents($imageUrl);
                $filename = 'generated_image_' . time() . '.png';
                $path = storage_path('app/public/' . $filename);
                file_put_contents($path, $imageContent);

                return [
                    'file_path' => $path,
                    'file_name' => $filename,
                    'url' => $imageUrl,
                ];
            } else {
                throw new \Exception('Unexpected OpenAI Image API response structure');
            }
        } catch (\Exception $e) {
            Log::error('Error in callOpenAIImage method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw new \Exception('Error calling OpenAI Image API: ' . $e->getMessage());
        }
    }

    private function sanitizePrompt($prompt)
    {
        // Remove any potentially problematic content
        $prompt = preg_replace('/[^\p{L}\p{N}\s\p{P}]/u', '', $prompt);
        
        // Ensure the prompt is not too long
        $prompt = substr($prompt, 0, 1000);
        
        // Add a safety disclaimer
        $prompt = "Please create a safe and appropriate image of the following: " . $prompt;
        
        return $prompt;
    }
}