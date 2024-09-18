<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;
use Startupful\ContentsGenerate\Models\ContentGenerate;
use Startupful\ContentsGenerate\Models\Logic;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StorageController extends BaseController
{
    protected $jsonHelper;

    public function __construct(JsonHelperController $jsonHelper)
    {
        $this->jsonHelper = $jsonHelper;
    }
    
    public function storeGeneratedContent($result, $steps, $logicId)
    {
        $lastStep = end($result);
        $lastStepType = end($steps)['type'];

        $logic = Logic::find($logicId);
        $title = $this->generateTitle($logic->name);

        $contentGenerate = new ContentGenerate();
        $contentGenerate->uuid = Str::uuid();
        $contentGenerate->title = $title;
        $contentGenerate->type = $this->determineContentType($lastStepType);

        // Check if 'result' key exists and is an array
        $resultData = isset($lastStep['result']) && is_array($lastStep['result']) ? $lastStep['result'] : $lastStep;

        if ($lastStepType === 'generate_audio') {
            $contentGenerate->audio_text = $resultData['text'] ?? null;
            $contentGenerate->audio_model = $resultData['model'] ?? null;
            $contentGenerate->audio_voice = $resultData['voice'] ?? null;
            $contentGenerate->file_path = $resultData['file_path'] ?? null;
            $contentGenerate->file_name = $resultData['file_name'] ?? null;
            // audio_content는 저장하지 않습니다.
            $contentGenerate->content = json_encode([
                'text' => $resultData['text'] ?? null,
                'model' => $resultData['model'] ?? null,
                'voice' => $resultData['voice'] ?? null,
                'file_path' => $resultData['file_path'] ?? null,
                'file_name' => $resultData['file_name'] ?? null,
            ]);
        } elseif ($lastStepType === 'generate_image') {
            $contentGenerate->file_path = $resultData['file_path'] ?? null;
            $contentGenerate->file_name = $resultData['file_name'] ?? null;
            $contentGenerate->content = $resultData['originalPrompt'] ?? null;
        } elseif ($lastStepType === 'generate_excel') {
            $contentGenerate->file_path = $resultData['file_path'] ?? null;
            $contentGenerate->file_name = $resultData['file_name'] ?? null;
            $contentGenerate->content = json_encode($resultData);
        } else {
            $contentGenerate->content = $this->prepareContentForStorage($lastStep);
        }

        $contentGenerate->published_date = now();
        $contentGenerate->status = 'draft';
        $contentGenerate->save();

        Log::info('Content Generate saved', [
            'id' => $contentGenerate->id,
            'type' => $contentGenerate->type,
            'file_path' => $contentGenerate->file_path,
            'file_name' => $contentGenerate->file_name,
            'content' => substr($contentGenerate->content, 0, 100) . '...' // Log only first 100 chars
        ]);

        return $contentGenerate;
    }

    private function generateTitle($logicName)
    {
        $baseTitle = $logicName;
        $count = ContentGenerate::where('title', 'like', $baseTitle . '-%')->count();
        $newNumber = $count + 1;
        return $baseTitle . '-' . $newNumber;
    }

    private function prepareContentForStorage($content)
    {
        // JSON 문자열인 경우 디코딩
        if (is_string($content) && $this->isJson($content)) {
            $decodedContent = json_decode($content, true);
            if (isset($decodedContent['result'])) {
                $content = $decodedContent['result'];
            } else {
                $content = $decodedContent;
            }
        }

        // 배열이나 객체인 경우
        if (is_array($content) || is_object($content)) {
            if (isset($content['result'])) {
                $content = $content['result'];
            }
        }

        // 문자열이 아닌 경우 문자열로 변환
        if (!is_string($content)) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        // 유니코드 이스케이프 시퀀스 디코딩
        $content = $this->decodeUnicode($content);

        // 줄바꿈 문자 보존
        $content = str_replace('\n', "\n", $content);

        // 앞뒤의 따옴표 제거 (json_encode로 인해 추가된 것)
        $content = trim($content, '"');

        // HTML 엔티티 디코딩
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $content;
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function decodeUnicode($str) {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }

    private function determineContentType($stepType)
    {
        switch ($stepType) {
            case 'generate_text':
                return 'text';
            case 'generate_ui_ux':
                return 'code';
            case 'generate_audio':
                return 'audio';
            case 'generate_image':
                return 'image';
            case 'generate_excel':
                return 'excel';
            default:
                return 'unknown';
        }
    }
}