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
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->get($url);
            $content = $step['extraction_type'] === 'text_only' 
                ? $this->extractTextContent($response->body()) 
                : $response->body();

            Log::info("Web scraping completed", ['url' => $url, 'contentLength' => strlen($content)]);

            return [
                'url' => $url,
                'Output' => $content,
                'extraction_type' => $step['extraction_type']
            ];
        } catch (\Exception $e) {
            Log::error('Error in scrapWebpage method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
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

    private function extractTextContent($html)
    {
        $document = new Document($html);
        $body = $document->find('body')[0] ?? $document;
        
        // Remove script and style elements
        foreach($body->find('script, style') as $element) {
            $element->remove();
        }
    
        $text = $body->text();
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
}