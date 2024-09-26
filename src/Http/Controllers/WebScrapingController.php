<?php

namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DiDom\Document;

class WebScrapingController extends BaseController
{
    public function scrapWebpage($step, $inputData, $stepIndex)
    {
        try {
            Log::info("Starting web scraping", ['step' => $step, 'inputData' => $inputData, 'stepIndex' => $stepIndex]);

            $urlSource = $step['url_source'];
            $url = null;

            if ($urlSource === 'fixed') {
                $url = $step['fixed_url'];
            } elseif ($urlSource === 'user_input') {
                $url = $this->findUrlInInputData($inputData);
            }

            if (!$url) {
                throw new \Exception("URL not provided for web scraping step. URL source: {$urlSource}, Step: {$stepIndex}, InputData: " . json_encode($inputData));
            }

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'Accept-Language' => 'ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7',
            ])->get($url);

            Log::debug('Response Headers', ['headers' => $response->headers()]);

            $content = $this->extractContent($response->body(), $step['extraction_type'], $step['specific_data_types'] ?? [], $url, $response);

            Log::info("Web scraping completed", [
                'url' => $url,
                'contentLength' => strlen(json_encode($content)),
                'extractedContent' => json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ]);

            return [
                'url' => $url,
                'output' => $content,
                'extraction_type' => $step['extraction_type']
            ];
        } catch (\Exception $e) {
            Log::error('Error in scrapWebpage method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function extractContent($html, $extractionType, $specificDataTypes = [], $url = null, $response = null)
    {
        // Detect encoding from headers or meta tags
        $encoding = $this->detectEncoding($html, $response);

        // Log the detected encoding
        Log::debug('Detected encoding', ['encoding' => $encoding]);
    
        // Convert HTML content to UTF-8
        if (strtoupper($encoding) !== 'UTF-8') {
            $html = mb_convert_encoding($html, 'UTF-8', $encoding);
        }

        $document = new Document($html);
        
        switch ($extractionType) {
            case 'text_only':
                return $this->extractTextContent($document);
            case 'html':
                return $html;
            case 'specific_data':
                return $this->extractSpecificData($document, $specificDataTypes, $url);
            default:
                return $this->extractTextContent($document);
        }
    }

    private function extractTextContent($document)
    {
        $body = $document->find('body')[0] ?? $document;
        
        // Remove script and style elements
        foreach($body->find('script, style') as $element) {
            $element->remove();
        }
    
        $text = $body->text();
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function extractSpecificData($document, $specificDataTypes, $url)
    {
        $result = [];

        if (in_array('site', $specificDataTypes)) {
            $result['site'] = $this->extractSite($document, $url);
        }

        if (in_array('author', $specificDataTypes)) {
            $result['author'] = $this->extractAuthor($document);
        }

        if (in_array('url', $specificDataTypes)) {
            $result['url'] = $this->extractUrl($document);
        }

        if (in_array('email', $specificDataTypes)) {
            $result['emails'] = $this->extractEmails($document);
        }

        if (in_array('phone', $specificDataTypes)) {
            $result['phones'] = $this->extractPhones($document);
        }

        if (in_array('address', $specificDataTypes)) {
            $result['addresses'] = $this->extractAddresses($document);
        }

        return $result;
    }

    private function extractSite($document, $url)
    {
        Log::info('Starting site extraction');
    
        // Get all meta tags
        $metaTags = $document->find('meta');

        Log::debug('Total meta tags found', ['count' => count($metaTags)]);
    
        foreach ($metaTags as $meta) {
            $property = $meta->getAttribute('property');
            $name = $meta->getAttribute('name');
            $content = trim($meta->getAttribute('content') ?: $meta->getAttribute('value'));

            Log::debug('Meta tag details', [
                'property' => $property,
                'name' => $name,
                'content' => $content,
            ]);
    
            if ($property == 'og:site_name' && !empty($content)) {
                Log::info('Site extracted from meta tag', ['tag' => 'meta[property="og:site_name"]', 'content' => $content]);
                return $content;
            }
    
            if ($name == 'application-name' && !empty($content)) {
                Log::info('Site extracted from meta tag', ['tag' => 'meta[name="application-name"]', 'content' => $content]);
                return $content;
            }
    
            if ($name == 'publisher' && !empty($content)) {
                Log::info('Site extracted from meta tag', ['tag' => 'meta[name="publisher"]', 'content' => $content]);
                return $content;
            }
    
            if ($property == 'al:android:app_name' && !empty($content)) {
                Log::info('Site extracted from meta tag', ['tag' => 'meta[property="al:android:app_name"]', 'content' => $content]);
                return $content;
            }
        }
    
        // Alternative methods
        $selectors = [
            '.logo',
            '.site-name',
            'header .title',
            'div[class*="logo"]',
            'a[class*="logo"]',
            'img[alt*="디지털타임스"]',
        ];
    
        foreach ($selectors as $selector) {
            $elements = $document->find($selector);
            foreach ($elements as $element) {
                $content = trim($element->text());
                if (!empty($content)) {
                    Log::info('Site extracted from HTML element', ['selector' => $selector, 'content' => $content]);
                    return $content;
                }
                if ($element->hasAttribute('alt')) {
                    $content = trim($element->getAttribute('alt'));
                    if (!empty($content)) {
                        Log::info('Site extracted from image alt attribute', ['selector' => $selector, 'content' => $content]);
                        return $content;
                    }
                }
            }
        }
    
        // Fallback to domain name
        $host = parse_url($url, PHP_URL_HOST);
        $host = preg_replace('/^www\./', '', $host);
        Log::info('Site extracted from domain name', ['content' => $host]);
        return $host;
    }    

    private function extractAuthor($document)
    {
        Log::info('Starting author extraction');
    
        // Get all meta tags
        $metaTags = $document->find('meta');

        Log::debug('Total meta tags found', ['count' => count($metaTags)]);
    
        foreach ($metaTags as $meta) {
            $property = $meta->getAttribute('property');
            $name = $meta->getAttribute('name');
            $content = trim($meta->getAttribute('content') ?: $meta->getAttribute('value'));

            Log::debug('Meta tag details', [
                'property' => $property,
                'name' => $name,
                'content' => $content,
            ]);
    
            if ($name == 'author' && !empty($content)) {
                Log::info('Author extracted from meta tag', ['tag' => 'meta[name="author"]', 'content' => $content]);
                return $content;
            }
    
            if ($property == 'article:author' && !empty($content)) {
                Log::info('Author extracted from meta tag', ['tag' => 'meta[property="article:author"]', 'content' => $content]);
                return $content;
            }
    
            if ($name == 'byline' && !empty($content)) {
                Log::info('Author extracted from meta tag', ['tag' => 'meta[name="byline"]', 'content' => $content]);
                return $content;
            }
    
            if ($name == 'dc.creator' && !empty($content)) {
                Log::info('Author extracted from meta tag', ['tag' => 'meta[name="dc.creator"]', 'content' => $content]);
                return $content;
            }
        }
    
        // Check JSON-LD scripts
        $jsonLDScripts = $document->find('script[type="application/ld+json"]');
        foreach ($jsonLDScripts as $script) {
            $jsonData = json_decode($script->text(), true);
            if ($jsonData) {
                if (isset($jsonData['author'])) {
                    if (isset($jsonData['author']['name'])) {
                        $authorName = $jsonData['author']['name'];
                        Log::info('Author extracted from JSON-LD', ['content' => $authorName]);
                        return $authorName;
                    } elseif (is_array($jsonData['author'])) {
                        $authorNames = [];
                        foreach ($jsonData['author'] as $author) {
                            if (isset($author['name'])) {
                                $authorNames[] = $author['name'];
                            }
                        }
                        if (!empty($authorNames)) {
                            $authorName = implode(', ', $authorNames);
                            Log::info('Authors extracted from JSON-LD', ['content' => $authorName]);
                            return $authorName;
                        }
                    }
                }
            }
        }
    
        // Alternative methods
        $selectors = [
            '.author',
            '.byline',
            '.writer',
            '.journalist',
            '.name',
            'p[class*="author"]',
            'span[class*="author"]',
            'div[class*="author"]',
            'span[class*="byline"]',
            'div[class*="byline"]',
        ];
    
        foreach ($selectors as $selector) {
            $elements = $document->find($selector);
            foreach ($elements as $element) {
                $content = trim($element->text());
                if (!empty($content)) {
                    Log::info('Author extracted from HTML element', ['selector' => $selector, 'content' => $content]);
                    return $content;
                }
            }
        }
    
        // Regex search in body text
        $bodyElement = $document->first('body');
        if ($bodyElement) {
            $bodyText = $bodyElement->text();
            if (preg_match('/([가-힣]{2,4})\s?(기자|작가|칼럼니스트)/u', $bodyText, $matches)) {
                $author = trim($matches[1]);
                Log::info('Author extracted using regex', ['content' => $author]);
                return $author;
            }            
        }
    
        Log::warning('Failed to extract author');
        return null;
    }    

    private function extractUrl($document)
    {
        Log::info('Starting URL extraction');
        
        $metaTags = [
            'meta[property="og:url"]',
            'link[rel="canonical"]',
            'meta[name="twitter:url"]',
        ];

        foreach ($metaTags as $tag) {
            $urlElement = $document->first($tag);
            if ($urlElement) {
                $url = $tag === 'link[rel="canonical"]' 
                    ? $urlElement->getAttribute('href') 
                    : $urlElement->getAttribute('content');
                
                if ($url) {
                    $normalizedUrl = $this->normalizeUrl($url);
                    Log::info('URL extracted', ['tag' => $tag, 'url' => $normalizedUrl]);
                    return $normalizedUrl;
                }
            }
        }

        // 대안: 현재 페이지의 URL 사용 (만약 가능하다면)
        $currentUrl = $document->first('base') ? $document->first('base')->getAttribute('href') : null;
        if ($currentUrl) {
            $normalizedUrl = $this->normalizeUrl($currentUrl);
            Log::info('URL extracted from base tag', ['url' => $normalizedUrl]);
            return $normalizedUrl;
        }

        Log::warning('Failed to extract URL');
        return null;
    }

    private function normalizeUrl($url)
    {
        if (strpos($url, 'http') !== 0) {
            return 'https:' . ($url[0] === '/' ? $url : "/$url");
        }
        return $url;
    }


    private function extractEmails($document)
    {
        $text = $document->text();
        preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches);
        return array_unique($matches[0]);
    }

    private function extractPhones($document)
    {
        $text = $document->text();
        $phones = [];

        // 한국 전화번호 형식 (000-0000-0000, 00-000-0000, +82-10-0000-0000 등)
        preg_match_all('/(\+82|0)[-.\s]?[0-9]{2,3}[-.\s]?[0-9]{3,4}[-.\s]?[0-9]{4}/', $text, $matches);
        $phones = array_merge($phones, $matches[0]);

        // 중복 제거 및 형식 정리
        $phones = array_unique(array_map(function($phone) {
            return preg_replace('/[^\d+]/', '', $phone);
        }, $phones));

        Log::info('Extracted phones', ['phones' => $phones]);
        return $phones;
    }

    private function extractAddresses($document)
    {
        // 주소 추출은 복잡할 수 있으므로, 여기서는 간단한 예시만 제공합니다.
        $addressElements = $document->find('address');
        return array_map(function($element) {
            return trim($element->text());
        }, $addressElements);
    }

    private function findUrlInInputData($inputData)
    {
        if (is_array($inputData)) {
            foreach ($inputData as $key => $value) {
                if (is_string($value) && $this->isValidUrl($value)) {
                    Log::info('Found URL in input data', ['key' => $key, 'url' => $value]);
                    return $value;
                }
            }
        }
        Log::warning('URL not found in input data');
        return null;
    }
    
    private function isValidUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) 
               && preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i', $url);
    }

    private function detectEncoding($html, $response = null)
    {
        // 1. Check charset in HTTP response headers
        if ($response) {
            $contentType = $response->header('Content-Type');
            if ($contentType && preg_match('/charset=([\w-]+)/i', $contentType, $matches)) {
                return strtoupper($matches[1]);
            }
        }

        // 2. Check meta tag with charset attribute
        if (preg_match('/<meta[^>]+charset=["\']?([^"\']+)["\']?/i', $html, $matches)) {
            return strtoupper($matches[1]);
        }

        // 3. Check meta tag with http-equiv="Content-Type"
        if (preg_match('/<meta[^>]+http-equiv=["\']Content-Type["\'][^>]*content=["\'][^"\']*charset=([^"\'>]+)["\']?/i', $html, $matches)) {
            return strtoupper($matches[1]);
        }

        // 4. Fallback to mb_detect_encoding
        $encoding = mb_detect_encoding($html, ['EUC-KR', 'CP949', 'UTF-8', 'ISO-8859-1', 'GBK', 'BIG5'], true);

        return $encoding ?: 'UTF-8';
    }

}