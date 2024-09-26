<?php

namespace Startupful\ContentsGenerate\Resources\LogicResource\Pages;

use Startupful\ContentsGenerate\Resources\LogicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class EditLogic extends EditRecord
{
    protected static string $resource = LogicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->icon('heroicon-o-eye'),
            Actions\Action::make('saveNow')
                ->label(__('startupful-plugin.save'))
                ->action(function () {
                    $this->save();
                })
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation(false)
                ->color('primary'),
            Actions\Action::make('duplicate')
                ->label(__('startupful-plugin.duplicate'))
                ->icon('heroicon-o-square-2-stack')
                ->action(function (Model $record) {
                    $newRecord = $record->replicate();
                    $newRecord->name = $newRecord->name . ' (Copy)';
                    $newRecord->save();
                    Notification::make()
                        ->title('Logic duplicated successfully')
                        ->success()
                        ->send();
                    return redirect($this->getResource()::getUrl('edit', ['record' => $newRecord]));
                })
                ->requiresConfirmation()
                ->color('success'),
            Actions\DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // LogicResource의 saving 메서드를 직접 호출
        LogicResource::saving($record, $data);
        $record->save();

        return $record;
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getRecord(), $data);

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        if ($shouldSendSavedNotification) {
            Notification::make()
                ->title('Saved successfully')
                ->success()
                ->send();
        }

        if ($shouldRedirect) {
            $this->redirect($this->getRedirectUrl());
        }
    }
}