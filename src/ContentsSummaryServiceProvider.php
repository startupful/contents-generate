<?php

namespace Startupful\ContentsSummary;

use Illuminate\Support\ServiceProvider;

class ContentsSummaryServiceProvider extends ServiceProvider
{
    public static string $name = 'contents-summary';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasMigrations([
                'create_content_summaries_table',
            ])
            ->runsMigrations();
    }

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'contents-summary');
    }
}