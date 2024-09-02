<?php

use Illuminate\Support\Facades\Route;
use Startupful\ContentsSummary\Http\Controllers\ContentsSummaryController;

Route::group(['prefix' => 'contents-summary'], function () {
    Route::get('/', [ContentsSummaryController::class, 'index'])->name('contents-summary.index');
    Route::post('/summarize/{type}', [ContentsSummaryController::class, 'summarize'])->name('contents-summary.summarize');
    Route::get('/s/{uuid}', [ContentsSummaryController::class, 'show'])->name('contents-summary.show');
    Route::get('/partial/{uuid}', [ContentsSummaryController::class, 'showPartial'])->name('contents-summary.show-partial');
    Route::post('/contents-summary/save/{uuid}', [ContentsSummaryController::class, 'save'])->name('contents-summary.save');
    Route::get('/my-summaries', [ContentsSummaryController::class, 'userSummaries'])->name('contents-summary.user-summaries');
    Route::get('/e', [ContentsSummaryController::class, 'explore'])->name('contents-summary.explore');
});