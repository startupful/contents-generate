<?php

namespace Startupful\ContentsSummary\Resources\ContentsSummaryResource\Pages;

use Startupful\ContentsSummary\Resources\ContentsSummaryResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class ViewContentsSummary extends ViewRecord
{
    protected static string $resource = ContentsSummaryResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('original_url')
                    ->label('Original URL')
                    ->url(fn ($record) => $record->original_url),
                TextEntry::make('content')
                    ->label('')
                    ->markdown()
                    ->columnSpanFull(),
            ]);
    }

    public function getTitle(): string
    {
        return $this->record->title ?? 'View Content Summary';
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->getResource()::getUrl() => $this->getResource()::getModelLabel(),
            '#' => $this->record->type . '',
        ];
    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }
}