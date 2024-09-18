<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class AudioGenerationController extends BaseController
{
    protected $utilityController;
    protected $currentLanguage;

    public function __construct(UtilityController $utilityController)
    {
        $this->utilityController = $utilityController;
        $this->currentLanguage = config('app.locale', 'ko');
    }

    public function generateAudio($step, $inputData, $previousResults)
    {
        try {
            Log::info("Starting audio generation", ['step' => $step, 'inputData' => $inputData, 'previousResults' => $previousResults]);

            $processedInputData = $this->processInputData($inputData);
            $text = $this->utilityController->replacePlaceholders($step['audio_text'], $processedInputData, $previousResults, $step['input_fields'] ?? []);
            Log::info("Audio text after placeholder replacement", ['text' => $text, 'original' => $step['audio_text']]);

            $model = $step['audio_model'];
            $voice = $step['voice'];

            if (empty($text)) {
                throw new \Exception('No text provided for audio generation after placeholder replacement');
            }

            $text = $this->ensureValidUTF8($text);

            Log::info("Preparing audio generation", [
                'text' => $text,
                'model' => $model,
                'voice' => $voice,
                'language' => $this->currentLanguage,
            ]);

            $audioContent = $this->callOpenAIAudio($text, $model, $voice);

            $filename = 'generated_audio_' . time() . '.mp3';
            $path = storage_path('app/public/' . $filename);
            file_put_contents($path, base64_decode($audioContent));

            Log::info("Audio generation completed", ['filename' => $filename]);

            $result = [
                'type' => 'generate_audio',
                'audio_content' => $audioContent,
                'text' => $text,
                'model' => $model,
                'voice' => $voice,
                'file_path' => $path,
                'file_name' => $filename,
                'language' => $this->currentLanguage,
            ];

            // 로그를 위한 결과 복사본 생성
            $logResult = $result;
            
            // audio_content 요약
            $logResult['audio_content'] = $this->summarizeAudioContent($audioContent);

            Log::debug("Generated audio result", ['result' => $logResult]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Error in generateAudio method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function processInputData($inputData)
    {
        $processedData = [];
        foreach ($inputData as $key => $value) {
            if (is_array($value) && !empty($value)) {
                // 여기서 파일 처리 로직을 추가할 수 있습니다. 필요하다면 FileManagerController를 사용하세요.
                $processedData[$key] = $value;
            } else {
                $processedData[$key] = $value;
            }
        }
        return $processedData;
    }

    private function callOpenAIAudio($text, $model, $voice)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/audio/speech', [
                'model' => $model,
                'input' => $text,
                'voice' => $voice,
                'response_format' => 'mp3',
            ]);

            if ($response->successful()) {
                return base64_encode($response->body());
            } else {
                Log::error('OpenAI Audio API call failed', ['response' => $response->body()]);
                throw new \Exception('OpenAI Audio API call failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error calling OpenAI Audio API', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw new \Exception('Error calling OpenAI Audio API: ' . $e->getMessage());
        }
    }

    private function ensureValidUTF8($string)
    {
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }

    public function setLanguage($language)
    {
        $this->currentLanguage = $language;
    }

    private function summarizeAudioContent($audioContent)
    {
        $contentLength = strlen($audioContent);
        $preview = substr($audioContent, 0, 50); // 처음 50자만 표시
        return "[Audio content length: {$contentLength} bytes, Preview: {$preview}...]";
    }
}