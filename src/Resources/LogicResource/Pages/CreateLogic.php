<?php

namespace Startupful\ContentsGenerate\Resources\LogicResource\Pages;

use Startupful\ContentsGenerate\Resources\LogicResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateLogic extends CreateRecord
{
    protected static string $resource = LogicResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('생성')
                ->action('create')
        ];
    }

    protected function getActions(): array
    {
        return [];
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $this->callHook('beforeCreate');

            $this->record = $this->handleRecordCreation($data);

            $this->callHook('afterCreate');

            $this->getCreatedNotification()?->send();

            if ($another) {
                // 계속해서 새로운 레코드를 생성하려면 폼을 초기화합니다.
                $this->form->fill();
            } else {
                $this->redirect($this->getRedirectUrl());
            }
        } catch (Halt $exception) {
            return;
        }
    }

    protected function getFormActions(): array
    {
        return [];
    }
}