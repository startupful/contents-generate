<?php

namespace Startupful\ContentsSummary\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenAI\Laravel\Facades\OpenAI;
use Google_Client;
use Google_Service_YouTube;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Startupful\ContentsSummary\Models\ContentSummary;

class YoutubeSummaryController extends Controller
{
    public function summarize(Request $request)
    {
        Log::info('YouTube summarization started', ['input' => $request->all()]);

        $url = $request->input('url'); 
        
        if (empty($url)) {
            Log::error('Empty URL provided');
            throw new Exception('Empty URL provided');
        }

        $videoId = $this->extractVideoId($url);

        Log::info('Extracted video ID', ['videoId' => $videoId]);

        try {
            $videoInfo = $this->fetchVideoInfo($videoId);
            Log::info('Fetched video info', ['videoInfo' => $videoInfo]);

            $summaryData = $this->generateSummary($videoInfo);
            Log::info('Generated summary', ['summaryLength' => strlen($summaryData['summary'])]);

            $metadata = $this->getMetadata($videoInfo);
            Log::info('Got metadata', ['metadata' => $metadata]);

            $contentSummary = ContentSummary::create([
                'uuid' => Str::uuid(),
                'title' => $summaryData['title'],
                'content' => $summaryData['summary'],
                'type' => 'Video',
                'original_url' => "https://www.youtube.com/watch?v={$videoId}",
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
        } catch (Exception $e) {
            Log::error('YouTube summarization failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function extractVideoId($url)
    {
        if (empty($url)) {
            throw new \Exception("Empty URL provided");
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }
        throw new \Exception("Invalid YouTube URL: $url");
    }

    private function fetchVideoInfo($videoId)
    {
        Log::info('Fetching video info', ['videoId' => $videoId]);

        $apiKey = config('services.youtube.api_key');
        Log::info('Using API key', ['apiKey' => substr($apiKey, 0, 5) . '...']); // API 키의 일부만 로그에 기록

        $client = new Google_Client();
        $client->setDeveloperKey(config('services.youtube.api_key'));
        $youtube = new Google_Service_YouTube($client);

        try {
            $response = $youtube->videos->listVideos('snippet,statistics', ['id' => $videoId]);

            if (empty($response->items)) {
                Log::warning('Video not found', ['videoId' => $videoId]);
                throw new Exception('Video not found');
            }

            $videoDetails = $response->items[0]->snippet;
            $statistics = $response->items[0]->statistics;

            $videoInfo = [
                'id' => $videoId,
                'title' => $videoDetails->title,
                'description' => $videoDetails->description,
                'publishedAt' => $videoDetails->publishedAt,
                'channelTitle' => $videoDetails->channelTitle,
                'channelId' => $videoDetails->channelId,
                'viewCount' => $statistics->viewCount,
                'likeCount' => $statistics->likeCount,
                'commentCount' => $statistics->commentCount,
            ];

            Log::info('Video info fetched successfully', ['videoInfo' => $videoInfo]);
            return $videoInfo;
        } catch (Exception $e) {
            Log::error('Failed to fetch video info', [
                'videoId' => $videoId,
                'error' => $e->getMessage(),
                'apiKey' => substr($apiKey, 0, 5) . '...'
            ]);
            throw $e;
        }
    }

    private function fetchChannelInfo($channelId)
    {
        $client = new Google_Client();
        $client->setDeveloperKey(config('services.youtube.api_key'));
        $youtube = new Google_Service_YouTube($client);

        try {
            $response = $youtube->channels->listChannels('snippet', ['id' => $channelId]);

            if (empty($response->items)) {
                Log::warning('Channel not found', ['channelId' => $channelId]);
                return null;
            }

            $channelDetails = $response->items[0]->snippet;

            return [
                'profileIcon' => $channelDetails->thumbnails->default->url,
            ];
        } catch (Exception $e) {
            Log::error('Failed to fetch channel info', [
                'channelId' => $channelId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function parseCaptions($captions)
    {
        $lines = explode("\n", $captions);
        $text = '';
        foreach ($lines as $line) {
            if (!preg_match('/^\d{2}:\d{2}:\d{2},\d{3} --> \d{2}:\d{2}:\d{2},\d{3}$/', $line)) {
                $text .= ' ' . $line;
            }
        }
        return trim($text);
    }

    private function generateSummary($videoInfo)
    {
        Log::info('Generating summary', ['videoTitle' => $videoInfo['title']]);

        $content = "Title: {$videoInfo['title']}\n\n";
        $content .= "Description: {$videoInfo['description']}\n\n";
        $content .= "Channel: {$videoInfo['channelTitle']}\n";
        $content .= "Views: {$videoInfo['viewCount']}\n";
        $content .= "Likes: {$videoInfo['likeCount']}\n";
        $content .= "Comments: {$videoInfo['commentCount']}\n";

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-2024-05-13',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes YouTube videos in the style of a Wikipedia page using Markdown format.'],
                    ['role' => 'user', 'content' => "
            Create a Wikipedia-style page based on the following YouTube video information and comments. Follow these guidelines:

            1. Start with a main title (use # for h1) that captures the video's topic.

            2. Begin with an introductory paragraph that provides an overview of the video content. This should be informative and factual, covering the most important aspects.

            3. Use ## for '개요', followed by a more detailed summary of the content. This should give a comprehensive overview of the video, its main points, and any key takeaways.

            4. Use ## for '주요 내용' to introduce the key points or main topics discussed in the video. For each main point:

                a. Use ### for the key point or topic title.
                
                b. Under each ### heading, provide a detailed explanation of the point. This explanation should be structured in one of the following ways, depending on the content:
                    - A paragraph of text for general explanations.
                    - An unordered list (use - for each item) for related items or steps.
                    - An ordered list (use 1., 2., 3., etc.) for sequential processes or ranked items.
                    - A combination of the above if appropriate.

                c. Ensure that the explanation under each ### heading is comprehensive yet concise, capturing the essence of the point discussed in the video.

                d. If applicable, include relevant examples, statistics, or quotes from the video to support each point.

                e. Use appropriate Markdown syntax for emphasis (**bold**, *italic*) within the explanations to highlight key terms or ideas.

            5. If applicable, use ## for '영향 및 의의' to discuss the broader implications or significance of the video content.

            6. Ensure proper use of Markdown syntax for structure and emphasis.

            7. Keep the summary informative, factual, and structured, highlighting the most important aspects of the video and viewer reactions.

            Here's the video information to summarize:

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
        } catch (Exception $e) {
            Log::error('Failed to generate summary', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function getMetadata($videoInfo)
    {
        Log::info('Getting metadata', ['videoTitle' => $videoInfo['title'], 'videoId' => $videoInfo['id']]);

        $thumbnailUrl = "https://img.youtube.com/vi/{$videoInfo['id']}/hqdefault.jpg";
        
        // 썸네일 URL이 유효한지 확인
        $headers = get_headers($thumbnailUrl);
        if(!$headers || strpos($headers[0], '404') !== false) {
            Log::warning('Thumbnail not found, using default image', ['videoId' => $videoInfo['id']]);
            $thumbnailUrl = asset('images/default-thumbnail.jpg');
        }

        // 채널 정보 가져오기
        $channelInfo = $this->fetchChannelInfo($videoInfo['channelId']);

        $brand = 'YouTube';
        $faviconFilename = Str::slug($brand) . '.png';
        $authorIconFilename = Str::slug($videoInfo['channelTitle']) . '.png';

        $metadata = [
            'thumbnail' => $this->saveImage($thumbnailUrl, 'thumbnails', md5($videoInfo['id'])),
            'favicon' => $this->saveImage("https://www.youtube.com/favicon.ico", 'favicons', $faviconFilename),
            'brand' => $brand,
            'author' => $videoInfo['channelTitle'],
            'date' => date('Y-m-d', strtotime($videoInfo['publishedAt'])),
            'author_icon' => $this->saveImage($channelInfo['profileIcon'], 'author_icons', $authorIconFilename),
        ];

        Log::info('Metadata retrieved', ['metadata' => $metadata]);
        return $metadata;
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
}