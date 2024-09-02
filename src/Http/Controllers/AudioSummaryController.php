<?php

namespace Startupful\ContentsSummary\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenAI\Laravel\Facades\OpenAI;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Startupful\ContentsSummary\Models\ContentSummary;

class AudioSummaryController extends Controller
{
    public function summarize(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|max:25000',
        ]);
    
        $audioFile = $request->file('audio');
    
        // FFprobe를 사용하여 파일 형식 확인
        $ffprobe = FFProbe::create();
        if (!$ffprobe->isValid($audioFile->getPathname())) {
            return back()->withErrors(['audio' => 'Invalid audio file.']);
        }

        try {
            Log::info('Audio summarization started', ['filename' => $audioFile->getClientOriginalName()]);

            // 음성을 텍스트로 변환 (OpenAI의 Whisper API 사용)
            $transcription = $this->transcribeAudio($audioFile);

            // 변환된 텍스트 요약
            $summaryData = $this->generateSummary($transcription);

            // 메타데이터 생성
            $metadata = $this->getMetadata($audioFile);

            $contentSummary = ContentSummary::create([
                'uuid' => Str::uuid(),
                'title' => $summaryData['title'],
                'content' => $summaryData['summary'],
                'type' => 'Audio',
                'original_url' => $audioFile->getClientOriginalName(),
                'user_id' => auth()->id(),
                'thumbnail' => null, // 썸네일 없음
                'favicon' => $metadata['favicon'],
                'brand' => $metadata['brand'],
                'author' => $metadata['author'],
                'published_date' => $metadata['date'],
                'author_icon' => null, // 저자 아이콘 없음
            ]);

            Log::info('ContentSummary created', [
                'id' => $contentSummary->id,
                'uuid' => $contentSummary->uuid,
                'favicon' => $contentSummary->favicon,
            ]);

            return $contentSummary;
        } catch (\Exception $e) {
            Log::error('Audio summarization failed', [
                'file' => $audioFile->getClientOriginalName(),
                'mime' => $audioFile->getMimeType(),
                'size' => $audioFile->getSize(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function transcribeAudio($audioFile)
    {
        Log::info('Transcribing audio', ['filename' => $audioFile->getClientOriginalName()]);

        $ffmpeg = FFMpeg::create();
        $audio = $ffmpeg->open($audioFile->getPathname());

        // 임시 파일 생성
        $tempFile = tempnam(sys_get_temp_dir(), 'audio_') . '.mp3';

        try {
            // 오디오를 MP3 형식으로 변환하여 임시 파일에 저장
            $format = new Mp3();
            $audio->save($format, $tempFile);

            // OpenAI API에 파일 전송
            $response = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($tempFile, 'r'),
                'response_format' => 'text',
            ]);

            // 임시 파일 삭제
            unlink($tempFile);

            Log::info('Audio transcription completed', ['length' => strlen($response->text)]);

            return $response->text;
        } catch (\Exception $e) {
            // 임시 파일이 존재하면 삭제
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            Log::error('Transcription failed', [
                'file' => $audioFile->getClientOriginalName(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function generateSummary($content)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-2024-05-13',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes audio content in the style of a Wikipedia page using Markdown format.'],
                ['role' => 'user', 'content' => "
    Create a Wikipedia-style page based on the following audio transcription. Follow these guidelines:

    1. Start with a main title (use # for h1) that captures the audio's topic.

    2. Begin with an introductory paragraph that provides an overview of the audio content. This should be informative and factual, covering the most important aspects.

    3. Use ## for '개요', followed by a more detailed summary of the content. This should give a comprehensive overview of the audio, its main points, and any key takeaways.

    4. Use ## for '주요 내용' to introduce the key points or main topics discussed in the audio. For each main point:

        a. Use ### for the key point or topic title.
        
        b. Under each ### heading, provide a detailed explanation of the point. This explanation should be structured in one of the following ways, depending on the content:
            - A paragraph of text for general explanations.
            - An unordered list (use - for each item) for related items or steps.
            - An ordered list (use 1., 2., 3., etc.) for sequential processes or ranked items.
            - A combination of the above if appropriate.

        c. Ensure that the explanation under each ### heading is comprehensive yet concise, capturing the essence of the point discussed in the audio.

        d. If applicable, include relevant examples, statistics, or quotes from the audio to support each point.

        e. Use appropriate Markdown syntax for emphasis (**bold**, *italic*) within the explanations to highlight key terms or ideas.

    5. If applicable, use ## for '결론 및 의의' to discuss the broader implications or significance of the audio content.

    6. Ensure proper use of Markdown syntax for structure and emphasis.

    7. Keep the summary informative, factual, and structured, highlighting the most important aspects of the audio.

    Here's the audio transcription to summarize:

    $content
                "],
            ],
            'max_tokens' => 4000,
            'temperature' => 0.7,
        ]);

        $markdown = $response->choices[0]->message->content;

        // Extract title from the first # heading
        preg_match('/^# (.*)$/m', $markdown, $matches);
        $title = $matches[1] ?? '';

        // Remove the # title from the summary
        $summary = preg_replace('/^# .*$/m', '', $markdown, 1);

        return [
            'title' => $title,
            'summary' => trim($summary)
        ];
    }

    private function getMetadata($audioFile)
    {
        Log::info('Getting metadata', ['filename' => $audioFile->getClientOriginalName()]);

        $brand = 'Audio';

        $metadata = [
            'favicon' => null, // 파비콘을 null로 설정
            'brand' => $brand,
            'author' => 'Unknown', // 음성 파일에서 작성자 정보를 추출하기 어려울 수 있습니다.
            'date' => now()->format('Y-m-d'), // 현재 날짜를 사용합니다. 필요하다면 파일 생성 날짜를 사용할 수 있습니다.
        ];

        Log::info('Metadata retrieved', ['metadata' => $metadata]);

        return $metadata;
    }

    // private function saveImage($url, $directory, $filename)
    // {
    //     // 이미지 저장 로직을 주석 처리
    // }
}