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
        $recentSummaries = ContentSummary::latest()->take(5)->get();

        return view('contents-summary::index', compact('recentSummaries'));
    }

    public function explore()
    {
        $summaries = ContentSummary::latest()->paginate(10);
        return view('contents-summary::explore', compact('summaries'));
    }

    public function summarize(Request $request, $type)
    {   
        try {
            $url = $request->input('video_id'); // 'url' 대신 'video_id'를 사용
            $existingSummary = ContentSummary::where('original_url', $url)->first();
            
            if ($existingSummary) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Summary already exists',
                    'summary' => $existingSummary
                ]);
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

            return response()->json([
                'status' => 'success',
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create summary. Please try again.'
            ], 500);
        }
    }

    public function show($uuid)
    {
        try {
            $content_summaries = ContentSummary::where('uuid', $uuid)->firstOrFail();
            return view('contents-summary::summary', compact('content_summaries'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function showPartial($uuid)
    {
        try {
            $content_summaries = ContentSummary::where('uuid', $uuid)->firstOrFail();

            return view('contents-summary::summary-partial', compact('content_summaries'))
                ->renderSections()['content'];
        } catch (\Exception $e) {
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

            return redirect()->back()->with('success', 'Summary saved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save summary');
        }
    }

    public function userSummaries()
    {
        $summaries = ContentSummary::where('user_id', Auth::id())->paginate(10);

        return view('contents-summary::user_summaries', compact('summaries'));
    }
}