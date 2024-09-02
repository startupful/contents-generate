<?php

namespace Startupful\ContentsSummary\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpPresentation\IOFactory;
use OpenAI\Laravel\Facades\OpenAI;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Slide\Background\Image;
use PhpOffice\PhpPresentation\Shape\Table;
use PhpOffice\PhpPresentation\Slide\Iterator;
use PhpOffice\PhpPresentation\Shape\RichText;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Startupful\ContentsSummary\Models\ContentSummary;

class PptSummaryController extends Controller
{
    public function summarize(Request $request)
    {
        $pptFile = $request->file('file');

        if (!$pptFile) {
            throw new \Exception('No PPT file uploaded');
        }

        $content = $this->extractPptContent($pptFile);

        $summaryData = $this->generateSummary($content);

        $metadata = $this->getMetadata($pptFile);

        $contentSummary = ContentSummary::create([
            'uuid' => Str::uuid(),
            'title' => $summaryData['title'],
            'content' => $summaryData['summary'],
            'type' => 'PPT',
            'original_url' => $pptFile->getClientOriginalName(),
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

    private function getAuthor($pptFile)
    {
        $ppt = IOFactory::load($pptFile->getPathname());
        $properties = $ppt->getDocumentProperties();
        $creator = $properties->getCreator();
        
        if (is_string($creator) && !empty($creator)) {
            return $creator;
        } else {
            return 'Unknown';
        }
    }

    private function getCreationDate($pptFile)
    {
        $ppt = IOFactory::load($pptFile->getPathname());
        $properties = $ppt->getDocumentProperties();
        $created = $properties->getCreated();
        
        if ($created instanceof \DateTime) {
            return $created->format('Y-m-d');
        } elseif (is_int($created)) {
            return date('Y-m-d', $created);
        } elseif (is_string($created)) {
            return date('Y-m-d', strtotime($created));
        } else {
            return now()->format('Y-m-d');
        }
    }

    private function extractPptContent($pptFile)
    {
        $content = '';
        try {
            $ppt = IOFactory::load($pptFile->getPathname());

            foreach ($ppt->getAllSlides() as $slideIndex => $slide) {
                Log::info("Processing slide " . ($slideIndex + 1));
                $content .= "Slide " . ($slideIndex + 1) . ":\n";
                foreach ($slide->getShapeCollection() as $shape) {
                    if ($shape instanceof RichText) {
                        foreach ($shape->getParagraphs() as $paragraph) {
                            $text = trim($paragraph->getPlainText());
                            if (!empty($text)) {
                                $content .= $text . "\n";
                                Log::info("Extracted text from RichText: " . substr($text, 0, 50) . "...");
                            }
                        }
                    } elseif ($shape instanceof Table) {
                        foreach ($shape->getRows() as $row) {
                            foreach ($row->getCells() as $cell) {
                                $text = trim($cell->getText());
                                if (!empty($text)) {
                                    $content .= $text . "\n";
                                    Log::info("Extracted text from Table: " . substr($text, 0, 50) . "...");
                                }
                            }
                        }
                    }
                }
                $content .= "\n";
            }
        } catch (\Exception $e) {
            Log::error('Error extracting content from PPT: ' . $e->getMessage());
            $content = "Error extracting content from PPT file.";
        }

        if (empty(trim($content))) {
            $content = "This PPT file does not contain any extractable text content.";
            Log::warning("No content extracted from PPT file: " . $pptFile->getClientOriginalName());
        } else {
            Log::info("Extracted content from PPT file: " . substr($content, 0, 500) . "...");
        }

        return $content;
    }

    private function generateSummary($content)
    {
        if (trim($content) === "This PPT file does not contain any extractable text content.") {
            return [
                'title' => 'Empty PowerPoint Presentation',
                'summary' => 'The PowerPoint file does not contain any extractable text content. It might consist only of images or shapes without text, or the text might be embedded in a way that our system cannot extract.'
            ];
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-2024-05-13',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes PowerPoint presentations in the style of a Wikipedia page using Markdown format.'],
                ['role' => 'user', 'content' => "
    Create a Wikipedia-style page based on the following PowerPoint presentation content. Follow these guidelines:

    1. Start with a main title (use # for h1) that captures the presentation's topic.

    2. Begin with an introductory paragraph that provides an overview of the presentation content. This should be informative and factual, covering the most important aspects.

    3. Use ## for '개요', followed by a more detailed summary of the content. This should give a comprehensive overview of the presentation, its main points, and any key takeaways.

    4. Use ## for '주요 내용' to introduce the key points or main topics discussed in the presentation. For each main point:

        a. Use ### for the key point or topic title.
        
        b. Under each ### heading, provide a detailed explanation of the point. This explanation should be structured in one of the following ways, depending on the content:
            - A paragraph of text for general explanations.
            - An unordered list (use - for each item) for related items or steps.
            - An ordered list (use 1., 2., 3., etc.) for sequential processes or ranked items.
            - A combination of the above if appropriate.

        c. Ensure that the explanation under each ### heading is comprehensive yet concise, capturing the essence of the point discussed in the presentation.

        d. If applicable, include relevant examples, statistics, or key information from the slides to support each point.

        e. Use appropriate Markdown syntax for emphasis (**bold**, *italic*) within the explanations to highlight key terms or ideas.

    5. If applicable, use ## for '결론 및 시사점' to discuss the broader implications or significance of the presentation content.

    6. Ensure proper use of Markdown syntax for structure and emphasis.

    7. Keep the summary informative, factual, and structured, highlighting the most important aspects of the presentation.

    Here's the PowerPoint content to summarize:

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

    private function getMetadata($pptFile)
    {
        $ppt = IOFactory::load($pptFile->getPathname());
        $properties = $ppt->getDocumentProperties();

        // 썸네일 추출 로직을 주석 처리하고 기본 이미지 URL 사용
        // $thumbnail = $this->extractFirstSlideImage($pptFile);
        $thumbnail = asset('images/default-ppt-thumbnail.jpg');

        $brand = 'PowerPoint';

        return [
            'thumbnail' => $thumbnail,
            'favicon' => null, // 파비콘을 null로 설정
            'brand' => $brand,
            'author' => $properties->getCreator() ?? 'Unknown',
            'date' => $this->formatDate($properties->getCreated()),
            'author_icon' => null,
        ];
    }

    // private function saveImage($url, $directory, $filename)
    // {
    //     // 이미지 저장 로직을 주석 처리
    // }

    // private function extractFirstSlideImage($pptFile)
    // {
    //     // 첫 번째 슬라이드 이미지 추출 로직을 주석 처리
    // }

    private function formatDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d');
        } elseif (is_numeric($date)) {
            // Unix timestamp로 가정
            return date('Y-m-d', $date);
        } elseif (is_string($date)) {
            // 문자열 날짜로 가정
            return date('Y-m-d', strtotime($date));
        } else {
            // 날짜 정보가 없는 경우 현재 날짜 반환
            return now()->format('Y-m-d');
        }
    }
}