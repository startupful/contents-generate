<?php

namespace Startupful\ContentsGenerate;

use Illuminate\Support\ServiceProvider;
use Startupful\ContentsGenerate\Services\YouTubeTranscriptService;
use Startupful\ContentsGenerate\Http\Controllers\JsonHelperController;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;

class ContentsGenerateServiceProvider extends ServiceProvider
{
    public static string $name = 'contents-generate';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasMigrations([
                'create_content_generates_table',
                'create_content_generates_logic_table',
                'add_audio_columns_to_content_generates',
                'add_file_fields_to_content_generate_table',
            ])
            ->runsMigrations();
    }

    public function register()
    {
        $this->app->bind(YouTubeDataApiService::class, function ($app) {
            return new YouTubeDataApiService();
        });

        $this->app->singleton(JsonHelperController::class, function ($app) {
            return new JsonHelperController();
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'contents-generate');
    }
}