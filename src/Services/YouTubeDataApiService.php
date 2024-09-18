<?php

namespace Startupful\ContentsGenerate\Services;

use Google_Client;
use Google_Service_YouTube;
use Exception;
use Illuminate\Support\Facades\Log;

class YouTubeDataApiService
{
    private $youtube;
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
        
        $this->authenticate();
    }

    private function authenticate()
    {
        $accessToken = env('YOUTUBE_ACCESS_TOKEN');
        $refreshToken = env('YOUTUBE_REFRESH_TOKEN');

        if ($accessToken && $refreshToken) {
            $this->client->setAccessToken([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]);

            if ($this->client->isAccessTokenExpired()) {
                $this->refreshToken();
            }
        } else {
            throw new Exception('YouTube API tokens are not set in the .env file');
        }

        $this->youtube = new Google_Service_YouTube($this->client);
    }

    private function refreshToken()
    {
        try {
            $this->client->fetchAccessTokenWithRefreshToken(env('YOUTUBE_REFRESH_TOKEN'));
            $newAccessToken = $this->client->getAccessToken();
            
            $this->updateEnvFile('YOUTUBE_ACCESS_TOKEN', $newAccessToken['access_token']);
        } catch (Exception $e) {
            Log::error('Failed to refresh YouTube API token: ' . $e->getMessage());
            throw new Exception('Failed to refresh YouTube API token');
        }
    }

    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        $content = file_get_contents($path);
        
        if (strpos($content, $key) === false) {
            file_put_contents($path, "\n$key=$value", FILE_APPEND);
        } else {
            file_put_contents($path, preg_replace(
                "/^$key=.*/m",
                "$key=$value",
                $content
            ));
        }
    }

    public function getVideoInfo($videoId)
    {
        try {
            Log::info('Fetching video info from YouTube API', ['videoId' => $videoId]);
            $response = $this->youtube->videos->listVideos('snippet,statistics', ['id' => $videoId]);

            if (empty($response->items)) {
                Log::warning('Video not found', ['videoId' => $videoId]);
                throw new Exception('Video not found');
            }

            $videoDetails = $response->items[0]->snippet;
            $statistics = $response->items[0]->statistics;

            Log::info('Fetching video transcript', ['videoId' => $videoId]);
            $transcript = $this->getTranscript($videoId);

            $result = [
                'id' => $videoId,
                'title' => $videoDetails->title,
                'description' => $videoDetails->description,
                'publishedAt' => $videoDetails->publishedAt,
                'channelTitle' => $videoDetails->channelTitle,
                'channelId' => $videoDetails->channelId,
                'viewCount' => $statistics->viewCount,
                'likeCount' => $statistics->likeCount,
                'commentCount' => $statistics->commentCount,
                'transcript' => $transcript,
                'thumbnail' => $videoDetails->thumbnails->high->url,
                'favicon' => "https://www.youtube.com/s/desktop/bd3558ba/img/favicon.ico",
                'brand' => 'YouTube',
                'author' => $videoDetails->channelTitle,
                'published_date' => date('Y-m-d', strtotime($videoDetails->publishedAt)),
            ];

            Log::info('Video info fetched successfully', ['videoId' => $videoId]);
            return $result;
        } catch (Exception $e) {
            Log::error('Error in getVideoInfo method', ['error' => $e->getMessage(), 'videoId' => $videoId]);
            throw new Exception('Failed to get video info: ' . $e->getMessage());
        }
    }

    private function getTranscript($videoId)
    {
        try {
            $response = $this->youtube->captions->listCaptions('snippet', $videoId);
            $transcripts = [];

            foreach ($response->getItems() as $caption) {
                $lang = $caption->getSnippet()->getLanguage();
                if ($lang == 'ko' || $lang == 'en') {
                    try {
                        $captionContent = $this->youtube->captions->download($caption->getId());
                        $transcripts[$lang] = $this->parseCaptionContent($captionContent);
                    } catch (Exception $e) {
                        Log::warning('Failed to download caption', [
                            'videoId' => $videoId,
                            'captionId' => $caption->getId(),
                            'language' => $lang,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            if (empty($transcripts)) {
                Log::warning('No transcript available for the video', ['videoId' => $videoId]);
                return 'Transcript not available for this video.';
            }

            Log::info('Transcripts fetched successfully', ['videoId' => $videoId, 'languages' => array_keys($transcripts)]);
            return $transcripts;
        } catch (Exception $e) {
            Log::warning('Error in getTranscript method', [
                'error' => $e->getMessage(),
                'videoId' => $videoId,
                'errorDetails' => json_decode($e->getMessage(), true)
            ]);
            return 'Failed to retrieve transcript. Error: ' . $e->getMessage();
        }
    }

    private function parseCaptionContent($content)
    {
        $lines = explode("\n", $content);
        $transcript = [];
        $currentTime = '';
        $currentText = '';

        foreach ($lines as $line) {
            if (preg_match('/^\d{2}:\d{2}:\d{2},\d{3}/', $line)) {
                if ($currentTime !== '' && $currentText !== '') {
                    $transcript[] = [
                        'time' => $currentTime,
                        'text' => trim($currentText)
                    ];
                }
                $currentTime = $line;
                $currentText = '';
            } else {
                $currentText .= $line . ' ';
            }
        }

        // Add the last caption
        if ($currentTime !== '' && $currentText !== '') {
            $transcript[] = [
                'time' => $currentTime,
                'text' => trim($currentText)
            ];
        }

        return $transcript;
    }

    public function isAuthenticated()
    {
        return $this->client->getAccessToken() && !$this->client->isAccessTokenExpired();
    }
}