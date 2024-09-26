<?php

namespace Startupful\ContentsGenerate\Resources\ContentsGenerateResource\Pages;

use Startupful\ContentsGenerate\Resources\ContentsGenerateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContentsGenerates extends ListRecords
{
    protected static string $resource = ContentsGenerateResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    public function getTitle(): string 
    {
        return 'AI Contents';
    }
}