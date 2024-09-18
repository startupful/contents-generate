<?php

namespace Startupful\ContentsGenerate\Resources\ContentsGenerateResource\Pages;

use Startupful\ContentsGenerate\Resources\ContentsGenerateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentsGenerate extends EditRecord
{
    protected static string $resource = ContentsGenerateResource::class;

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