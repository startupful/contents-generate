<?php

namespace Startupful\ContentsSummary\Services;

use Illuminate\Http\Request;
use Startupful\ContentsSummary\Models\ContentSummary;
use Startupful\ContentsSummary\Http\Controllers\ArticleSummaryController;
use Startupful\ContentsSummary\Http\Controllers\YoutubeSummaryController;
use Startupful\ContentsSummary\Http\Controllers\PdfSummaryController;
use Startupful\ContentsSummary\Http\Controllers\PptSummaryController;
use Startupful\ContentsSummary\Http\Controllers\AudioSummaryController;

class SummaryService
{
    public function summarize($type, Request $request)
    {
        switch ($type) {
            case 'article':
                return app(ArticleSummaryController::class)->summarize($request);
            case 'youtube':
                return app(YoutubeSummaryController::class)->summarize($request);
            case 'pdf':
                return app(PdfSummaryController::class)->summarize($request);
            case 'ppt':
                return app(PptSummaryController::class)->summarize($request);
            case 'audio':
                return app(AudioSummaryController::class)->summarize($request);
            default:
                throw new \InvalidArgumentException("Invalid summary type: {$type}");
        }
    }
}