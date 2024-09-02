<?php

namespace Startupful\ContentsSummary;

use Illuminate\Support\ServiceProvider;

class ContentsSummaryServiceProvider extends ServiceProvider
{
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