<?php

namespace Startupful\ContentsSummary\Resources\ContentsSummaryResource\Pages;

use Startupful\ContentsSummary\Resources\ContentsSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentsSummary extends EditRecord
{
    protected static string $resource = ContentsSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}