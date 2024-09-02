<?php

namespace Startupful\ContentsSummary\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenAI\Laravel\Facades\OpenAI;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Startupful\ContentsSummary\Traits\SummarySaveable;
use Startupful\ContentsSummary\Models\ContentSummary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Embed\Embed;
use DOMDocument;
use DOMXPath;

class ArticleSummaryController extends Controller
{
    use SummarySaveable;

    public function summarize(Request $request)
    {
        try {
            Log::info('Entering ArticleSummaryController summarize method', ['url' => $request->input('url')]);

            $url = $request->input('url');
            $content = $this->fetchContent($url);
            $summaryData = $this->generateSummary($content);
            $metadata = $this->getMetadata($url);

            Log::info('Summary data generated', ['title' => $summaryData['title']]);
            Log::info('Metadata retrieved', $metadata);  // 로그 추가

            $contentSummary = ContentSummary::create([
                'uuid' => Str::uuid(),
                'title' => $summaryData['title'],
                'content' => $summaryData['summary'],
                'type' => 'Article',
                'original_url' => $url,
                'user_id' => auth()->id(),
                'thumbnail' => $metadata['thumbnail'],
                'favicon' => $metadata['favicon'],
                'brand' => $metadata['brand'],
                'author' => $metadata['author'],
                'published_date' => now()->format('Y-m-d'),
            ]);

            Log::info('ContentSummary created', [
                'id' => $contentSummary->id,
                'uuid' => $contentSummary->uuid,
                'thumbnail' => $contentSummary->thumbnail,
                'favicon' => $contentSummary->favicon,
                'brand' => $contentSummary->brand,
            ]);

            return $contentSummary;
        } catch (\Exception $e) {
            Log::error('Failed to create article summary', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function fetchContent($url)
    {
        try {
            // Guzzle HTTP 클라이언트 인스턴스 생성
            $client = new Client();

            // URL로 GET 요청 보내기
            $response = $client->get($url);

            // 응답 본문 가져오기
            $html = $response->getBody()->getContents();

            // DOM 파서 생성
            $dom = new DOMDocument();
            @$dom->loadHTML($html);

            // XPath 객체 생성
            $xpath = new DOMXPath($dom);

            // 본문 내용 추출 (예: <article> 태그 내용)
            $articleNodes = $xpath->query('//article');
            if ($articleNodes->length > 0) {
                $content = $articleNodes->item(0)->textContent;
            } else {
                // <article> 태그가 없는 경우, <body> 태그 내용 사용
                $bodyNodes = $xpath->query('//body');
                $content = $bodyNodes->item(0)->textContent;
            }

            // 불필요한 공백 제거 및 텍스트 정리
            $content = preg_replace('/\s+/', ' ', $content);
            $content = trim($content);

            return $content;
        } catch (RequestException $e) {
            // 요청 실패 시 예외 처리
            throw new \Exception("Failed to fetch content from URL: " . $e->getMessage());
        }
    }

    private function generateSummary($content)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-2024-05-13',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes text in the style of a Wikipedia page using Markdown format.'],
                ['role' => 'user', 'content' => "
    Create a Wikipedia-style page based on the following text. Follow these guidelines:

    1. Start with a main title (use # for h1) that captures the text's topic.

    2. Begin with an introductory paragraph that provides an overview of the text content. This should be informative and factual, covering the most important aspects.

    3. Use ## for '개요', followed by a more detailed summary of the content. This should give a comprehensive overview of the text, its main points, and any key takeaways.

    4. Use ## for '주요 내용' to introduce the key points or main topics discussed in the text. For each main point:

        a. Use ### for the key point or topic title.
        
        b. Under each ### heading, provide a detailed explanation of the point. This explanation should be structured in one of the following ways, depending on the content:
            - A paragraph of text for general explanations.
            - An unordered list (use - for each item) for related items or steps.
            - An ordered list (use 1., 2., 3., etc.) for sequential processes or ranked items.
            - A combination of the above if appropriate.

        c. Ensure that the explanation under each ### heading is comprehensive yet concise, capturing the essence of the point discussed in the text.

        d. If applicable, include relevant examples, statistics, or quotes from the text to support each point. Use > for blockquotes, prefaced with '원문 발췌:'.

        e. Use appropriate Markdown syntax for emphasis (**bold**, *italic*) within the explanations to highlight key terms or ideas.

    5. If applicable, use ## for '영향 및 의의' to discuss the broader implications or significance of the text content.

    6. Ensure proper use of Markdown syntax for structure and emphasis.

    7. Keep the summary informative, factual, and structured, highlighting the most important aspects of the text.

    Here's the text content to summarize:

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

    private function getMetadata($url)
    {
        try {
            $info = Embed::create($url);

            $brand = $info->providerName ?: $this->getFallbackBrand($url);
            $faviconFilename = Str::slug($brand) . '.png';  // 파일 확장자를 .png로 고정
            $favicon = $this->saveImage($info->favicon ?: $this->getFallbackFavicon($url), 'favicons', $faviconFilename);
            $thumbnail = $this->saveImage($info->image, 'thumbnails', md5($url));

            return [
                'thumbnail' => $thumbnail,
                'favicon' => $favicon,
                'brand' => $brand,
                'author' => $this->getAuthor($info, $url),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get metadata', ['url' => $url, 'error' => $e->getMessage()]);
            return $this->getFallbackMetadata($url);
        }
    }

    private function saveImage($url, $directory, $filename)
    {
        if (empty($url)) {
            Log::info('Empty image URL', ['directory' => $directory, 'filename' => $filename]);
            return null;
        }

        $path = $directory . '/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            Log::info('Image already exists', ['path' => $path]);
            return Storage::url($path);
        }

        try {
            $client = new Client();
            $response = $client->get($url);
            $imageContent = $response->getBody()->getContents();
            
            Storage::disk('public')->put($path, $imageContent);
            Log::info('Image saved successfully', ['path' => $path]);
            return Storage::url($path);
        } catch (\Exception $e) {
            Log::error('Failed to save image', ['url' => $url, 'error' => $e->getMessage()]);
            return null;
        }
    }

    private function getFallbackImage($url)
    {
        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        
        // 일반적인 OG 이미지 경로 시도
        $possiblePaths = [
            '/og-image.jpg',
            '/images/og-image.png',
            '/assets/images/og-image.jpg',
        ];
        
        foreach ($possiblePaths as $path) {
            $imageUrl = $domain . $path;
            if ($this->urlExists($imageUrl)) {
                return $imageUrl;
            }
        }
        
        // 기본 이미지 반환
        return asset('images/default-thumbnail.jpg');
    }

    private function getFallbackFavicon($url)
    {
        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        
        $possibleFavicons = [
            '/favicon.ico',
            '/favicon.png',
            '/assets/favicon.ico',
        ];
        
        foreach ($possibleFavicons as $favicon) {
            $faviconUrl = $domain . $favicon;
            if ($this->urlExists($faviconUrl)) {
                return $faviconUrl;
            }
        }
        
        // 기본 파비콘 반환
        return asset('images/default-favicon.ico');
    }

    private function getFallbackBrand($url)
    {
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'];
        
        // www. 제거
        $host = preg_replace('/^www\./', '', $host);
        
        // 도메인에서 TLD 제거
        $parts = explode('.', $host);
        if (count($parts) > 1) {
            array_pop($parts);
        }
        
        // 남은 부분을 브랜드 이름으로 사용
        $brand = implode(' ', $parts);
        
        // 첫 글자를 대문자로
        return ucwords($brand);
    }

    private function urlExists($url)
    {
        $headers = get_headers($url);
        return stripos($headers[0], "200 OK") ? true : false;
    }

    private function getFallbackMetadata($url)
    {
        // 기본적인 메타데이터 반환
        return [
            'thumbnail' => $this->getFallbackImage($url),
            'favicon' => $this->getFallbackFavicon($url),
            'brand' => $this->getFallbackBrand($url),
        ];
    }

    private function getAuthor($info, $url)
    {
        // Embed 라이브러리에서 제공하는 작성자 정보 확인
        if ($info->authorName) {
            return $info->authorName;
        }

        // 직접 HTML을 파싱하여 작성자 정보 추출
        $html = file_get_contents($url);
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);

        // 다양한 메타 태그 확인
        $metaAuthors = [
            '//meta[@name="author"]/@content',
            '//meta[@property="article:author"]/@content',
            '//meta[@property="og:article:author"]/@content',
            '//meta[@name="twitter:creator"]/@content',
        ];

        foreach ($metaAuthors as $xpath_query) {
            $author = $xpath->evaluate("string($xpath_query)");
            if ($author) {
                return $author;
            }
        }

        // Schema.org 마크업 확인
        $schemaAuthors = $xpath->query('//script[@type="application/ld+json"]');
        foreach ($schemaAuthors as $schema) {
            $json = json_decode($schema->nodeValue, true);
            if (isset($json['author']['name'])) {
                return $json['author']['name'];
            }
        }

        // 일반적인 HTML 구조 확인
        $commonAuthors = [
            '//span[contains(@class, "author")]',
            '//p[contains(@class, "author")]',
            '//a[contains(@class, "author")]',
            '//div[contains(@class, "author")]',
        ];

        foreach ($commonAuthors as $xpath_query) {
            $authorNode = $xpath->query($xpath_query)->item(0);
            if ($authorNode) {
                return trim($authorNode->textContent);
            }
        }

        // 작성자를 찾지 못한 경우
        return 'Unknown';
    }
}