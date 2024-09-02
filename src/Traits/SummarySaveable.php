<?php

namespace Startupful\ContentsSummary\Traits;

use Startupful\ContentsSummary\Models\ContentSummary;
use Illuminate\Support\Str;

trait SummarySaveable
{
    protected function saveAndRedirect($summaryData, $type, $url, $metadata)
    {
        $contentSummary = ContentSummary::create([
            'uuid' => Str::uuid(),
            'title' => $summaryData['title'],
            'content' => $summaryData['summary'],
            'type' => $type,
            'original_url' => $url,
            'user_id' => auth()->id(),
            'thumbnail' => $metadata['thumbnail'],
            'favicon' => $metadata['favicon'],
            'brand' => $metadata['brand'],
            'author' => $metadata['author'],
            'published_date' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('contents-summary.show', $contentSummary->uuid);
    }
}