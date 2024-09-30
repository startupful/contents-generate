<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;

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
            $aiProvider = $step['ai_provider'];
            $aiModel = $step['ai_model'];

            // Replace placeholders in prompt and background info
            $prompt = $this->utilityController->replacePlaceholders($prompt, $inputData, $previousResults, $inputFields);
            $backgroundInfo = $this->utilityController->replacePlaceholders($backgroundInfo, $inputData, $previousResults, $inputFields);

            // Combine background info and prompt
            $fullPrompt = $this->createDetailedPrompt($backgroundInfo, $prompt);

            // Sanitize the combined prompt
            $sanitizedPrompt = $this->sanitizePrompt($fullPrompt);

            Log::info("Prepared prompt for image generation", [
                'originalPrompt' => $fullPrompt,
                'sanitizedPrompt' => $sanitizedPrompt,
                'inputData' => $inputData,
                'aiProvider' => $aiProvider,
                'aiModel' => $aiModel,
            ]);

            $imageResult = $this->callImageAPI($aiProvider, $aiModel, $sanitizedPrompt);

            // Add both original and sanitized prompts to the result
            $imageResult['originalPrompt'] = $fullPrompt;
            $imageResult['sanitizedPrompt'] = $sanitizedPrompt;

            return $imageResult;
        } catch (\Exception $e) {
            Log::error('Error in generateImage method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return [
                'error' => true,
                'message' => 'Failed to generate image: ' . $e->getMessage(),
                'file_path' => '/path/to/default/image.png',
                'file_name' => 'default_image.png',
                'url' => '/url/to/default/image.png',
                'originalPrompt' => $fullPrompt ?? 'N/A',
                'sanitizedPrompt' => $sanitizedPrompt ?? 'N/A'
            ];
        }
    }

    private function callImageAPI($aiProvider, $aiModel, $prompt)
    {
        Log::info("Calling image generation API", [
            'provider' => $aiProvider,
            'model' => $aiModel,
        ]);

        switch ($aiProvider) {
            case 'openai':
                return $this->callOpenAIImage($prompt, $aiModel);
            case 'huggingface':
                return $this->callHuggingFaceImage($prompt, $aiModel);
            default:
                throw new \Exception("Unsupported AI provider: $aiProvider");
        }
    }

    private function callOpenAIImage($prompt, $model)
    {
        $response = OpenAI::images()->create([
            'model' => $model,
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
    }

    private function callHuggingFaceImage($prompt, $model)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('HUGGINGFACE_API_KEY'),
        ])->post("https://api-inference.huggingface.co/models/$model", [
            'inputs' => $prompt,
        ]);
    
        if ($response->successful()) {
            $imageContent = $response->body();
            $filename = 'generated_image_' . time() . '.png';
            $path = storage_path('app/public/' . $filename);
            file_put_contents($path, $imageContent);
    
            return [
                'file_path' => $path,
                'file_name' => $filename,
                'url' => url('storage/' . $filename),
            ];
        } else {
            throw new \Exception('Hugging Face API request failed: ' . $response->body());
        }
    }

    private function createDetailedPrompt($backgroundInfo, $prompt)
    {
        return "$prompt";
    }

    private function sanitizePrompt($prompt)
    {
        // Remove any potentially problematic content
        $prompt = preg_replace('/[^\p{L}\p{N}\s\p{P}]/u', '', $prompt);
        
        // Ensure the prompt is not too long (adjust max length as needed)
        $prompt = substr($prompt, 0, 2000);
        
        return $prompt;
    }
}