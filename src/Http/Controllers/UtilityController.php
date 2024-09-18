<?php

namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;

class UtilityController extends BaseController
{
    protected $jsonHelper;

    public function __construct(JsonHelperController $jsonHelper)
    {
        $this->jsonHelper = $jsonHelper;
    }

    public function replacePlaceholders($text, $steps, $previousResults)
    {
        Log::debug('Starting replacePlaceholders', [
            'text' => $this->truncateForLog($text),
            'steps' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($steps)),
            'previousResults' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($previousResults))
        ]);
    
        // Ensure $steps and $previousResults are arrays
        $steps = $this->ensureArray($steps);
        $previousResults = $this->ensureArray($previousResults);
    
        $placeholders = $this->collectPlaceholders($steps, $previousResults);

        Log::debug('Placeholders collected', ['placeholders' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($placeholders))]);

        $result = preg_replace_callback('/{{([\w.]+)}}/', function($matches) use ($placeholders) {
            $key = $matches[1];
            Log::debug('Replacing placeholder', ['key' => $key]);
            
            if (isset($placeholders[$key])) {
                $value = $placeholders[$key];
                Log::debug('Placeholder found', ['key' => $key, 'value' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($value))]);
                
                // JSON 디코딩 시도
                $decodedValue = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decodedValue;
                }
    
                // Ensure the value is a string
                if (is_array($value) || is_object($value)) {
                    $value = $this->jsonHelper->safeJsonEncode($value);
                } elseif (!is_string($value)) {
                    $value = strval($value);
                }
                
                Log::debug('Placeholder value processed', ['key' => $key, 'processed_value' => $this->truncateForLog($value)]);
                return $value;
            } else {
                Log::warning("Placeholder not found", ['key' => $key]);
                return $matches[0];
            }
        }, $text);
    
        Log::debug('Placeholders replaced', ['result' => $this->truncateForLog($result)]);
    
        return $result;
    }

    private function collectPlaceholders($steps, $previousResults)
    {
        $placeholders = [];

        // Process steps (inputs)
        foreach ($steps as $key => $value) {
            $stepNumber = 1; // Assuming there's only one step in the input
            $stepKey = "step{$stepNumber}";

            // Add the entire step input
            $placeholders["{$stepKey}.input"] = json_encode($steps);

            // Process nested structure for input
            $this->processNestedStructure($placeholders, $steps, "{$stepKey}.input");
        }

        // Process previousResults (outputs)
        foreach ($previousResults as $stepKey => $stepData) {
            if (preg_match('/step_(\d+)/', $stepKey, $matches)) {
                $stepNumber = $matches[1];
                $stepKey = "step{$stepNumber}";
                if (isset($stepData['result']['Output'])) {
                    $placeholders["{$stepKey}.output"] = $stepData['result']['Output'];
                } elseif (isset($stepData['result'])) {
                    $placeholders["{$stepKey}.output"] = json_encode($stepData['result']);
                    // Process nested structure for output
                    $this->processNestedStructure($placeholders, $stepData['result'], "{$stepKey}.output");
                }
            }
        }

        Log::debug('All placeholders collected', ['placeholders' => $this->truncateForLog($this->jsonHelper->safeJsonEncode($placeholders))]);

        return $placeholders;
    }

    private function processNestedStructure(&$placeholders, $data, $prefix)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $newKey = "{$prefix}.{$key}";
                if (is_array($value)) {
                    $this->processNestedStructure($placeholders, $value, $newKey);
                } else {
                    $placeholders[$newKey] = $value;
                }
            }
        } else {
            $placeholders[$prefix] = $data;
        }
    }

    private function ensureArray($data)
    {
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return (json_last_error() === JSON_ERROR_NONE) ? $decoded : [$data];
        }
        return is_array($data) ? $data : [$data];
    }

    private function truncateForLog($text, $length = 100)
    {
        if (is_string($text) && strlen($text) > $length) {
            return substr($text, 0, $length) . '...';
        }
        return $text;
    }
}