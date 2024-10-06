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
                '1.create_content_generates_table',
                '2.create_content_generates_logic_table',
                '3.seed_content_generates_logic_table',
                '4.seed_content_generates_logic_table',
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