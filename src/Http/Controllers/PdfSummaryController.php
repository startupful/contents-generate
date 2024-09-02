<?php

namespace Startupful\ContentsSummary\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Smalot\PdfParser\Parser;
use OpenAI\Laravel\Facades\OpenAI;
use Org_Heigl\Ghostscript\Ghostscript;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Startupful\ContentsSummary\Models\ContentSummary;

class PdfSummaryController extends Controller
{
    public function summarize(Request $request)
    {
        Log::info('PDF summarization started', ['input' => $request->all()]);

        $pdfFile = $request->file('file');

        if (!$pdfFile) {
            throw new \Exception('No PDF file uploaded');
        }

        $content = $this->extractPdfContent($pdfFile);

        $summaryData = $this->generateSummary($content);

        $metadata = $this->getMetadata($pdfFile);

        $contentSummary = ContentSummary::create([
            'uuid' => Str::uuid(),
            'title' => $summaryData['title'],
            'content' => $summaryData['summary'],
            'type' => 'PDF',
            'original_url' => $pdfFile->getClientOriginalName(),
            'user_id' => auth()->id(),
            'thumbnail' => $metadata['thumbnail'],
            'favicon' => $metadata['favicon'],
            'brand' => $metadata['brand'],
            'author' => $metadata['author'],
            'published_date' => $metadata['date'],
            'author_icon' => $metadata['author_icon'],
        ]);

        Log::info('ContentSummary created', [
            'id' => $contentSummary->id,
            'uuid' => $contentSummary->uuid,
            'thumbnail' => $contentSummary->thumbnail,
            'favicon' => $contentSummary->favicon,
            'author_icon' => $contentSummary->author_icon,
        ]);

        return $contentSummary;
    }

    private function extractPdfContent($pdfFile)
    {
        Log::info('Extracting PDF content', ['filename' => $pdfFile->getClientOriginalName()]);

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfFile->getPathname());
        
        $text = $pdf->getText();
        
        // UTF-8 인코딩 문제 해결
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = iconv('UTF-8', 'UTF-8//IGNORE', $text);
        
        Log::info('PDF content extracted', ['contentLength' => strlen($text)]);

        return $text;
    }

    private function generateSummary($content)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-2024-05-13',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes PDF content in the style of a Wikipedia page, using Markdown format.'],
                ['role' => 'user', 'content' => "
    Create a Wikipedia-style page based on the following PDF content. Follow these guidelines:

    1. Start with a main title (use # for h1) that captures the PDF's topic.

    2. Begin with an introductory paragraph that provides an overview of the PDF content. This should be informative and factual, covering the most important aspects.

    3. Use ## for '개요', followed by a more detailed summary of the content. This should give a comprehensive overview of the PDF, its main points, and any key takeaways.

    4. Use ## for '주요 내용' to introduce the key points or main topics discussed in the PDF. For each main point:

        a. Use ### for the key point or topic title.
        
        b. Under each ### heading, provide a detailed explanation of the point. This explanation should be structured in one of the following ways, depending on the content:
            - A paragraph of text for general explanations.
            - An unordered list (use - for each item) for related items or steps.
            - An ordered list (use 1., 2., 3., etc.) for sequential processes or ranked items.
            - A combination of the above if appropriate.

        c. Ensure that the explanation under each ### heading is comprehensive yet concise, capturing the essence of the point discussed in the PDF.

        d. If applicable, include relevant examples, statistics, or quotes from the PDF to support each point.

        e. Use appropriate Markdown syntax for emphasis (**bold**, *italic*) within the explanations to highlight key terms or ideas.

    5. If applicable, use ## for '영향 및 의의' to discuss the broader implications or significance of the PDF content.

    6. Ensure proper use of Markdown syntax for structure and emphasis.

    7. Keep the summary informative, factual, and structured, highlighting the most important aspects of the PDF.

    Here's the PDF content to summarize:

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

    private function getMetadata($pdfFile)
    {
        Log::info('Getting metadata', ['filename' => $pdfFile->getClientOriginalName()]);

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfFile->getPathname());
        $details = $pdf->getDetails();

        // 썸네일 추출 로직을 주석 처리하고 기본 이미지 URL 사용
        // $thumbnail = $this->extractFirstImage($pdfFile);
        $thumbnail = asset('images/default-pdf-thumbnail.jpg');

        $brand = 'PDF';

        $metadata = [
            'thumbnail' => $thumbnail,
            'favicon' => null, // 파비콘을 null로 설정
            'brand' => $brand,
            'author' => $details['Author'] ?? 'Unknown',
            'date' => $this->formatDate($details['CreationDate'] ?? now()),
            'author_icon' => null,
        ];

        Log::info('Metadata retrieved', ['metadata' => $metadata]);

        return $metadata;
    }

    // private function extractFirstImage($pdfFile)
    // {
    //     // 첫 번째 이미지 추출 로직을 주석 처리
    // }

    // private function saveImage($url, $directory, $filename)
    // {
    //     // 이미지 저장 로직을 주석 처리
    // }

    private function formatDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d');
        } elseif (is_numeric($date)) {
            return date('Y-m-d', $date);
        } elseif (is_string($date)) {
            return date('Y-m-d', strtotime($date));
        } else {
            return now()->format('Y-m-d');
        }
    }
}