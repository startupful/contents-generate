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
        mb_internal_encoding('UTF-8');
    }
    
    public function storeGeneratedContent($result, $steps, $logicId, $userId)
    {
        if (is_null($userId)) {
            Log::error('User ID is null when storing content');
            throw new \Exception('User ID cannot be null');
        }

        $lastStep = end($result);
        $lastStepType = end($steps)['type'];

        $logic = Logic::find($logicId);
        $title = $this->generateTitle($logic->name);

        $contentGenerate = new ContentGenerate();
        $contentGenerate->uuid = Str::uuid();
        $contentGenerate->title = $title;
        $contentGenerate->type = $this->determineContentType($lastStepType);
        $contentGenerate->user_id = $userId;
        
        $contentType = $contentGenerate->type;

        // Check if 'result' key exists and is an array
        $resultData = isset($lastStep['result']) && is_array($lastStep['result']) ? $lastStep['result'] : $lastStep;

        if ($lastStepType === 'generate_ui_ux') {
            $contentGenerate->content = $this->prepareContentForStorage($lastStep, 'code');
        } elseif ($lastStepType === 'generate_audio') {
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
        } elseif ($lastStepType === 'content_integration') {
    
            // web_crawling 타입인 경우 별도 처리
            if (isset($resultData['type']) && $resultData['type'] === 'web_crawling') {
                $content = $this->handleWebCrawlingContent($resultData);
            } else {
                $content = is_string($resultData) ? $resultData : ($resultData['result'] ?? json_encode($resultData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
            
            // 줄바꿈을 그대로 보존
            $contentGenerate->content = $this->prepareContentForStorage($content, $contentType);
        } else {
            $contentGenerate->content = $this->prepareContentForStorage($lastStep, $contentType);
        }

        if ($lastStepType === 'generate_text' || $lastStepType === 'content_integration') {
            $content = is_string($resultData) ? $resultData : ($resultData['result'] ?? json_encode($resultData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            
            // 이스케이프된 줄바꿈을 실제 줄바꿈으로 변환
            $content = str_replace('\\n', "\n", $content);
            
            // Mermaid 다이어그램 처리
            $content = preg_replace_callback('/<pre class="mermaid">(.*?)<\/pre>/s', function($matches) {
                // Mermaid 다이어그램 내부의 줄바꿈을 보존
                $mermaidContent = str_replace("\n", "___NEWLINE___", $matches[1]);
                return "<pre class=\"mermaid\">{$mermaidContent}</pre>";
            }, $content);
            
            // 나머지 텍스트의 줄바꿈을 \\n으로 변환
            $content = preg_replace('/\n(?!<pre class="mermaid"|<\/pre>)/', "\\n", $content);
            
            // Mermaid 다이어그램 내부의 줄바꿈 복원
            $content = str_replace("___NEWLINE___", "\n", $content);
            
            $contentGenerate->content = $content;
        } else {
            // 다른 타입의 콘텐츠 처리 (이전 코드와 동일)
            $contentGenerate->content = $this->prepareContentForStorage($lastStep, $contentType);
        }

        $contentGenerate->published_date = now();
        $contentGenerate->status = 'draft';
        $contentGenerate->save();

        Log::info('Content Generate saved', [
            'id' => $contentGenerate->id,
            'type' => $contentGenerate->type,
            'file_path' => $contentGenerate->file_path,
            'file_name' => $contentGenerate->file_name,
            'content' => substr($contentGenerate->content, 0, 100) . '...', // Log only first 100 chars
            'user_id' => $contentGenerate->user_id
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

    private function prepareContentForStorage($content, $contentType = null)
    {
        if ($contentType === 'code') {
            if (is_array($content) && isset($content['result'])) {
                $content = $content['result'];
            }
            return $content;
        }
    
        if (is_string($content) && $this->isJson($content)) {
            $decodedContent = json_decode($content, true);
            if (isset($decodedContent['result'])) {
                $content = $decodedContent['result'];
            } else {
                $content = $decodedContent;
            }
        }
    
        if (is_array($content) || is_object($content)) {
            if (isset($content['result'])) {
                $content = $content['result'];
            }
            $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    
        if (!is_string($content)) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    
        $content = $this->decodeUnicode($content);
    
        // 앞뒤의 따옴표 제거 (json_encode로 인해 추가된 것)
        $content = trim($content, '"');
    
        // HTML 엔티티 디코딩
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
        // 코드 블록을 임시로 치환
        $codeBlocks = [];
        $content = preg_replace_callback('/```[\s\S]*?```/', function($matches) use (&$codeBlocks) {
            $placeholder = '___CODE_BLOCK_' . count($codeBlocks) . '___';
            $codeBlocks[] = $matches[0];
            return $placeholder;
        }, $content);
    
        // 줄바꿈 문자 정규화 (PHP_EOL을 사용)
        $content = str_replace(["\r\n", "\r", "\n"], PHP_EOL, $content);
        
        // 연속된 줄바꿈을 하나로 줄임
        $content = preg_replace('/('.preg_quote(PHP_EOL, '/').'){3,}/', PHP_EOL.PHP_EOL, $content);
    
        // 코드 블록 복원
        $content = preg_replace_callback('/___CODE_BLOCK_(\d+)___/', function($matches) use ($codeBlocks) {
            return $codeBlocks[$matches[1]];
        }, $content);
    
        // 앞뒤 공백 제거
        $content = trim($content);
    
        return $content;
    }

    private function normalizeLineBreaks($content, $contentType = null)
    {
        if ($contentType !== 'code') {
            // 줄바꿈 문자 정규화 (PHP_EOL을 사용)
            $content = str_replace(["\r\n", "\r", "\n"], PHP_EOL, $content);
            
            // 연속된 줄바꿈을 하나로 줄임
            $content = preg_replace('/('.preg_quote(PHP_EOL, '/').'){3,}/', PHP_EOL.PHP_EOL, $content);
        }

        return $content;
    }

    private function handleWebCrawlingContent($resultData)
    {
        $content = $resultData['result'] ?? '';
        
        if (is_string($content)) {
            // JSON 문자열인 경우 디코딩
            $decodedContent = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $content = $decodedContent;
            }
        }
        
        if (is_array($content)) {
            // 배열의 각 요소에 대해 유니코드 디코딩 적용
            array_walk_recursive($content, function(&$item) {
                if (is_string($item)) {
                    $item = $this->decodeUnicode($item);
                }
            });
        }
        
        // 다시 JSON으로 인코딩 (유니코드 이스케이프 없이)
        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function decodeUnicode($str) {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/i', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }

    private function determineContentType($stepType)
    {
        switch ($stepType) {
            case 'generate_text':
                return 'text';
            case 'content_integration':
                return 'integration';
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