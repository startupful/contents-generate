<?php

use Illuminate\Support\Facades\Route;
use Startupful\ContentsGenerate\Http\Controllers\ProcessController;
use Startupful\ContentsGenerate\Http\Controllers\YouTubeScrapingController;

Route::post('/process-logic', [ProcessController::class, 'process'])->name('process.logic');

Route::post('/scrap-youtube', [YouTubeScrapingController::class, 'scrapYouTube']);