<?php

namespace Startupful\ContentsSummary\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Startupful\ContentsSummary\Models\ContentSummary;
use Startupful\ContentsSummary\Services\SummaryService;
use Illuminate\Support\Facades\Auth;

class ContentsSummaryController extends Controller
{
    protected $summaryService;

    public function __construct(SummaryService $summaryService)
    {
        $this->summaryService = $summaryService;
    }

    public function index()
    {
        Log::info('Entering index method');
        $recentSummaries = ContentSummary::latest()->take(5)->get();
        Log::info('Recent summaries fetched', ['count' => $recentSummaries->count()]);

        return view('contents-summary::index', compact('recentSummaries'));
    }

    public function explore()
    {
        $summaries = ContentSummary::latest()->paginate(10);
        return view('contents-summary::explore', compact('summaries'));
    }

    public function summarize(Request $request, $type)
    {
        Log::info('Entering summarize method', ['type' => $type, 'request' => $request->all()]);
        
        try {
            // URL이 이미 요약되어 있는지 확인
            $url = $request->input('video_id'); // 'url' 대신 'video_id'를 사용
            $existingSummary = ContentSummary::where('original_url', $url)->first();
            
            if ($existingSummary) {
                Log::info('Existing summary found, redirecting', ['uuid' => $existingSummary->uuid]);
                return redirect()->route('contents-summary.show', $existingSummary->uuid);
            }

            switch ($type) {
                case 'article':
                    $summary = app(ArticleSummaryController::class)->summarize($request);
                    break;
                case 'youtube':
                    $summary = app(YoutubeSummaryController::class)->summarize($request);
                    break;
                case 'audio':
                    $summary = app(AudioSummaryController::class)->summarize($request);
                    break;
                case 'pdf':
                    $summary = app(PdfSummaryController::class)->summarize($request);
                    break;
                case 'ppt':
                    $summary = app(PptSummaryController::class)->summarize($request);
                    break;
                // 다른 타입들에 대한 case 추가
                default:
                    throw new \InvalidArgumentException("Invalid summary type: {$type}");
            }

            if (!($summary instanceof ContentSummary)) {
                throw new \Exception("Invalid return type from summarize method");
            }
    
            Log::info('Summary created, redirecting', ['uuid' => $summary->uuid]);
            return redirect()->route('contents-summary.show', $summary->uuid);
        } catch (\Exception $e) {
            Log::error('Failed to summarize content', ['type' => $type, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Failed to create summary. Please try again.');
        }
    }

    public function show($uuid)
    {
        try {
            $content_summaries = ContentSummary::where('uuid', $uuid)->firstOrFail();
            Log::info('Summary found', ['id' => $content_summaries->id, 'uuid' => $content_summaries->uuid]);
            return view('contents-summary::summary', compact('content_summaries'));
        } catch (\Exception $e) {
            Log::error('Failed to find summary', ['uuid' => $uuid, 'error' => $e->getMessage()]);
            abort(404);
        }
    }

    public function showPartial($uuid)
    {
        try {
            $content_summaries = ContentSummary::where('uuid', $uuid)->firstOrFail();
            Log::info('Partial summary found', ['id' => $content_summaries->id, 'uuid' => $content_summaries->uuid]);
            
            // main 태그 내용만 포함하는 부분 뷰 반환
            return view('contents-summary::summary-partial', compact('content_summaries'))
                ->renderSections()['content'];
        } catch (\Exception $e) {
            Log::error('Failed to find partial summary', ['uuid' => $uuid, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Summary not found'], 404);
        }
    }

    public function save($uuid)
    {
        Log::info('Entering save method', ['uuid' => $uuid]);
        
        try {
            $summary = ContentSummary::where('uuid', $uuid)->firstOrFail();
            $summary->user_id = Auth::id();
            $summary->save();
            Log::info('Summary saved successfully', ['id' => $summary->id, 'uuid' => $summary->uuid]);

            return redirect()->back()->with('success', 'Summary saved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to save summary', ['uuid' => $uuid, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to save summary');
        }
    }

    public function userSummaries()
    {
        Log::info('Entering userSummaries method');
        
        $summaries = ContentSummary::where('user_id', Auth::id())->paginate(10);
        Log::info('User summaries fetched', ['count' => $summaries->count()]);

        return view('contents-summary::user_summaries', compact('summaries'));
    }
}