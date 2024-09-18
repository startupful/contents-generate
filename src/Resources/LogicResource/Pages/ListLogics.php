<?php

namespace Startupful\ContentsGenerate\Resources\LogicResource\Pages;

use Startupful\ContentsGenerate\Resources\LogicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogics extends ListRecords
{
    protected static string $resource = LogicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('로직 생성'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    public function getTitle(): string 
    {
        return 'Logics';
    }
}