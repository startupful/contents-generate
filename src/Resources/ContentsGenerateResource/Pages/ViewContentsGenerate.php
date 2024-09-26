<?php

namespace Startupful\ContentsGenerate\Resources\ContentsGenerateResource\Pages;

use Startupful\ContentsGenerate\Resources\ContentsGenerateResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\ImageEntry;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Filament\Actions\Action;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ViewContentsGenerate extends ViewRecord
{
    protected static string $resource = ContentsGenerateResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('original_url')
                    ->label('Original URL')
                    ->url(fn ($record) => $record->original_url)
                    ->visible(fn ($record) => $record->original_url !== null),
                
                ViewEntry::make('content')
                    ->view('contents-generate::pages.text-content')
                    ->visible(fn ($record) => $record->type === 'text')
                    ->columnSpanFull(),
                
                ViewEntry::make('audio_content')
                    ->view('contents-generate::pages.audio-content')
                    ->visible(fn ($record) => $record->type === 'audio')
                    ->columnSpanFull(),
                
                TextEntry::make('audio_text')
                    ->label('Audio Transcript')
                    ->markdown()
                    ->visible(fn ($record) => $record->type === 'audio')
                    ->columnSpanFull(),

                ViewEntry::make('excel_content')
                    ->view('contents-generate::pages.excel-content')
                    ->visible(fn ($record) => $record->type === 'excel')
                    ->columnSpanFull(),

                ViewEntry::make('code_content')
                    ->view('contents-generate::pages.code-content')
                    ->visible(fn ($record) => $record->type === 'code')
                    ->columnSpanFull(),

                ImageEntry::make('file_path')
                    ->label('')
                    ->visible(fn ($record) => $record->type === 'image')
                    ->columnSpanFull()
                    ->extraImgAttributes([
                        'class' => 'max-w-none object-cover object-center ring-white dark:ring-gray-900 object-contain w-full h-auto max-h-screen',
                        'style' => 'height: 100%; max-height: 80vh;'
                    ])
                    ->getStateUsing(function ($record) {
                        if ($record->file_path) {
                            // Extract just the filename from the full path
                            $filename = basename($record->file_path);
                            // Construct the correct URL
                            return url("storage/{$filename}");
                        }
                        return null;
                    }),

                ViewEntry::make('integration_content')
                    ->view('contents-generate::pages.integration-content')
                    ->visible(fn ($record) => $record->type === 'integration')
                    ->columnSpanFull(),
            ]);
    }

    public function getTitle(): string
    {
        return $this->record->title ?? 'View Content Generate';
    }

    public function getBreadcrumbs(): array
    {
        return [
        ];
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if ($this->record->type === 'text' || $this->record->type === 'integration') {
            $actions[] = Action::make('copyMarkdown')
                ->label(__('startupful-plugin.markdown_copy'))
                ->icon('heroicon-o-clipboard-document')
                ->action(function () {
                    $this->js('window.dispatchEvent(new CustomEvent("copy-markdown", { detail: ' . json_encode($this->record->content) . ' }))');
                    Notification::make()->title('Markdown content copied to clipboard')->success()->send();
                });

            $actions[] = Action::make('copyHtml')
                ->label(__('startupful-plugin.html_copy'))
                ->icon('heroicon-o-clipboard')
                ->action(function () {
                    $content = $this->record->content;
                    $content = str_replace("\\n", "\n", $content);
                    $content = preg_replace('/\n/m', "  \n", $content);
                    $htmlContent = Str::markdown($content);
                    $this->js('window.dispatchEvent(new CustomEvent("copy-html", { detail: ' . json_encode($htmlContent) . ' }))');
                    Notification::make()->title('HTML content copied to clipboard')->success()->send();
                });
        }
        
        if ($this->record->type === 'excel') {
            $actions[] = Action::make('downloadExcel')
                ->label(__('startupful-plugin.excel_down'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => $this->downloadExcel());
        }
   
        if ($this->record->type === 'image') {
            $actions[] = Action::make('downloadImage')
                ->label(__('startupful-plugin.image_down'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => $this->downloadImage());

            $actions[] = Action::make('viewPrompt')
                ->label(__('startupful-plugin.view_prompt'))
                ->icon('heroicon-o-eye')
                ->action(function () {
                    $this->viewPrompt();
                });
        }

        if ($this->record->type === 'audio') {
            $actions[] = Action::make('downloadAudio')
                ->label(__('startupful-plugin.audio_down'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => $this->downloadAudio());
        }

        $actions[] = Action::make('edit')
            ->url(fn () => $this->getResource()::getUrl('edit', ['record' => $this->record]))
            ->label(__('startupful-plugin.edit'))
            ->icon('heroicon-o-pencil');

        return $actions;
    }

    public function getExcelContent()
    {
        if ($this->record->type !== 'excel' || !$this->record->file_path) {
            return null;
        }

        $spreadsheet = IOFactory::load($this->record->file_path);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $data = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = [
                    'value' => $cell->getValue(),
                    'style' => $this->getCellStyle($cell),
                ];
            }
            $data[] = $rowData;
        }

        $mergedCells = [];
        foreach ($worksheet->getMergeCells() as $mergeCell) {
            $mergedCells[] = $mergeCell;
        }

        return [
            'data' => $data,
            'mergedCells' => $mergedCells,
        ];
    }

    private function getCellStyle($cell)
    {
        $style = $cell->getStyle();
        return [
            'font' => [
                'bold' => $style->getFont()->getBold(),
                'color' => $style->getFont()->getColor()->getRGB(),
            ],
            'fill' => [
                'color' => $style->getFill()->getStartColor()->getRGB(),
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => $style->getBorders()->getBottom()->getBorderStyle(),
                    'color' => $style->getBorders()->getBottom()->getColor()->getRGB(),
                ],
            ],
            'alignment' => [
                'horizontal' => $style->getAlignment()->getHorizontal(),
                'vertical' => $style->getAlignment()->getVertical(),
            ],
        ];
    }

    public function downloadExcel(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $filePath = $this->record->file_path;
            if (file_exists($filePath)) {
                readfile($filePath);
            } else {
                throw new \Exception("Excel file not found");
            }
        }, $this->record->file_name ?? 'download.xlsx');
    }

    public function downloadAudio(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $filePath = storage_path('app/public/' . basename($this->record->file_path));
            if (file_exists($filePath)) {
                readfile($filePath);
            } else {
                throw new \Exception("Audio file not found: {$filePath}");
            }
        }, $this->record->file_name ?? 'audio.mp3');
    }

    public function getCodeContent()
    {
        if ($this->record->type !== 'code' || !$this->record->file_path) {
            return null;
        }

        return file_get_contents($this->record->file_path);
    }

    public function downloadImage(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $filePath = storage_path('app/public/' . basename($this->record->file_path));
            if (file_exists($filePath)) {
                readfile($filePath);
            } else {
                throw new \Exception("Image file not found: {$filePath}");
            }
        }, $this->record->file_name ?? 'download.png');
    }

    protected function viewPrompt(): void
    {
        if ($this->record->type !== 'image') {
            Notification::make()
                ->title('Prompt is only available for image type')
                ->warning()
                ->send();
            return;
        }

        $prompt = $this->record->content;

        if (empty($prompt)) {
            Notification::make()
                ->title('No prompt available')
                ->warning()
                ->send();
            return;
        }

        $this->showPromptNotification($prompt);
    }

    protected function showPromptNotification(string $prompt): void
    {
        Notification::make()
            ->title('Image Prompt')
            ->body($prompt)
            ->info()
            ->persistent()
            ->actions([
                \Filament\Notifications\Actions\Action::make('close')
                    ->label('Close')
                    ->close(),
            ])
            ->send();
    }

}