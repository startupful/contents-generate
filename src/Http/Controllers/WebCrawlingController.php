<?php

namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebCrawlingController extends BaseController
{
    public function __construct()
    {
        mb_internal_encoding('UTF-8');
    }

    public function crawlWebsites($step, $inputData, $stepIndex, $previousResults = [])
    {
        try {
            $inputData = $this->normalizeUTF8($inputData);
            
            $this->logInfo("Starting web crawling", [
                'step' => $step,
                'inputData' => $inputData,
                'stepIndex' => $stepIndex
            ]);

            $apiKey = env('GOOGLE_CUSTOM_SEARCH_API_KEY');
            $cx = env('GOOGLE_CUSTOM_SEARCH_ENGINE_ID');

            if (!$apiKey || !$cx) {
                throw new \Exception("Google Custom Search API key or Search Engine ID not configured.");
            }

            $searchQuery = $this->replacePlaceholders($step['search_query'] ?? '', $inputData, $previousResults);
            $numResults = $step['num_results'] ?? 10;

            $this->logInfo("Search query after placeholder replacement", ['searchQuery' => $searchQuery]);

            $params = [
                'key' => $apiKey,
                'cx' => $cx,
                'q' => $searchQuery,
                'num' => $numResults,
            ];

            $this->logDebug("API request parameters", ['params' => $params]);

            $response = Http::get('https://www.googleapis.com/customsearch/v1', $params);

            $this->logDebug("API response", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $results = $response->json();

                $this->logDebug("API response decoded", ['results' => $results]);
            
                return [
                    'type' => 'web_crawling',
                    'result' => $results
                ];                
            } else {
                $this->logError("API request failed", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("API request failed: " . $response->body());
            }

        } catch (\Exception $e) {
            $this->logError('Error in crawlWebsites method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'step' => $step,
                'inputData' => $inputData
            ]);
            throw $e;
        }
    }

    private function logInfo($message, $context = [])
    {
        Log::info($message, $this->prepareLogContext($context));
    }

    private function logDebug($message, $context = [])
    {
        Log::debug($message, $this->prepareLogContext($context));
    }

    private function logError($message, $context = [])
    {
        Log::error($message, $this->prepareLogContext($context));
    }

    private function prepareLogContext($context)
    {
        return array_map(function ($item) {
            if (is_array($item) || is_object($item)) {
                return json_encode($item, JSON_UNESCAPED_UNICODE);
            }
            return $item;
        }, $context);
    }    

    private function replacePlaceholders($text, $inputData, $previousResults)
    {
        $placeholders = $this->collectPlaceholders($inputData, $previousResults);

        Log::debug("Placeholders array", ['placeholders' => $placeholders]);

        $result = preg_replace_callback('/{{([\w\.-]+)}}/', function($matches) use ($placeholders) {
            $key = $matches[1];
            Log::debug("Processing placeholder", ['key' => $key]);

            if (!isset($placeholders[$key])) {
                Log::warning("Placeholder not found", ['key' => $key]);
                return $matches[0];
            }

            $replacement = $placeholders[$key];

            if (is_array($replacement)) {
                $replacement = json_encode($replacement, JSON_UNESCAPED_UNICODE);
            }                     

            Log::debug("Placeholder replacement", [
                'key' => $key,
                'replacement' => $replacement
            ]);

            return $replacement;
        }, $text);

        Log::debug("Placeholders replaced", ['result' => $result]);

        return $result;
    }

    private function collectPlaceholders($inputData, $previousResults)
    {
        $placeholders = [];

        $placeholders['step1.input'] = json_encode($inputData, JSON_UNESCAPED_UNICODE);
        foreach ($inputData as $key => $value) {
            $placeholders["step1.input.{$key}"] = $value;
        }

        foreach ($previousResults as $stepIndex => $result) {
            $stepKey = "step" . ($stepIndex + 1);

            // Check if $result is a JsonResponse and extract the data
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                $resultData = $result->getData(true); // Get the data as an associative array
            } else {
                $resultData = $result;
            }

            $placeholders[$stepKey] = json_encode($resultData, JSON_UNESCAPED_UNICODE);

            if (isset($resultData['result'])) {
                $this->flattenArray($resultData['result'], $stepKey, $placeholders);
            }
        }

        return $placeholders;
    }

    private function flattenArray($array, $prefix, &$result)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->flattenArray($value, $prefix . '.' . $key, $result);
            } else {
                $result[$prefix . '.' . $key] = $value;
            }
        }
    }

    private function decodeUnicode($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->decodeUnicode($value);
            }
        } elseif (is_string($data)) {
            $data = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
            }, $data);
        }
        return $data;
    }

    private function truncateForLog($text, $length = 1000)
    {
        if (is_array($text) || is_object($text)) {
            $text = json_encode($text, JSON_UNESCAPED_UNICODE);
        }
    
        if (is_string($text) && mb_strlen($text, 'UTF-8') > $length) {
            return mb_substr($text, 0, $length, 'UTF-8') . '...';
        }
        return $text;
    }

    private function normalizeUTF8($input) {
        if (is_array($input)) {
            return array_map([$this, 'normalizeUTF8'], $input);
        }
        return mb_convert_encoding($input, 'UTF-8', 'UTF-8');
    }

    private function logEncodingResults($results)
    {
        $sample = json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $encoded = base64_encode($sample);
        $decoded = base64_decode($encoded);

        Log::info("Encoding Results", [
            'original' => $this->truncateForLog($sample),
            'encoded' => $this->truncateForLog($encoded),
            'decoded' => $this->truncateForLog($decoded),
            'original_encoding' => $this->checkEncoding($sample),
            'decoded_encoding' => $this->checkEncoding($decoded)
        ]);
    }

    private function checkEncoding($str) {
        $encodings = ['UTF-8', 'ASCII', 'ISO-8859-1', 'EUC-KR'];
        foreach ($encodings as $encoding) {
            if (mb_check_encoding($str, $encoding)) {
                return $encoding;
            }
        }
        return 'Unknown';
    }
}