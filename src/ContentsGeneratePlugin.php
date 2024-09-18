<?php

namespace Startupful\ContentsGenerate;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ContentsGeneratePlugin implements Plugin
{
    public function getId(): string
    {
        return 'contents-generate';
    }

    public function register(Panel $panel): void
    {
        $panel
        ->resources([
            Resources\ContentsGenerateResource::class,
            Resources\LogicResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}