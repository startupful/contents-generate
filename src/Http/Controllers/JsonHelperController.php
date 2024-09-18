<?php

namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class JsonHelperController extends BaseController
{
    public function safeJsonEncode($data)
    {
        if (is_string($data) && is_array(json_decode($data, true)) && (json_last_error() == JSON_ERROR_NONE)) {
            return $data; // 이미 JSON 문자열임
        }
        return json_encode($data);
    }

    public function safeJsonDecode($data, $associative = false)
    {
        if (is_string($data)) {
            $decoded = json_decode($data, $associative);
            if (json_last_error() == JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        return $data; // JSON이 아니면 원래 데이터 반환
    }
}