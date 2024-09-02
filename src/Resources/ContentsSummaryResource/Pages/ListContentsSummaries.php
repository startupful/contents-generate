<?php

namespace Startupful\ContentsSummary\Resources\ContentsSummaryResource\Pages;

use Startupful\ContentsSummary\Resources\ContentsSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ListContentsSummaries extends ListRecords
{
    protected static string $resource = ContentsSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateSummary')
                ->label('Generate Summary')
                ->form([
                    Select::make('type')
                        ->label('Content Type')
                        ->options([
                            'article' => 'Website',
                            'youtube' => 'YouTube',
                            'audio' => 'Sound',
                            'pdf' => 'PDF',
                            'ppt' => 'PPT',
                        ])
                        ->required()
                        ->reactive(),
                    TextInput::make('url')
                        ->label('URL')
                        ->url()
                        ->visible(fn (callable $get) => in_array($get('type'), ['article', 'youtube']))
                        ->requiredIf('type', ['article', 'youtube']),
                    FileUpload::make('file')
                        ->label('File')
                        ->disk('public')
                        ->directory('uploads')
                        ->acceptedFileTypes(['audio/*', 'application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])
                        ->visible(fn (callable $get) => in_array($get('type'), ['audio', 'pdf', 'ppt']))
                        ->requiredIf('type', ['audio', 'pdf', 'ppt']),
                ])
                ->action(function (array $data) {
                    $response = $this->summarizeContent($data);
                    
                    if ($response && $response->successful()) {
                        $summaryData = $response->json();
                        // Here you would typically save the summary to your database
                        // For example:
                        // ContentSummary::create($summaryData);
                        
                        Notification::make()
                            ->title('Summary generated successfully')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Failed to generate summary')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function summarizeContent(array $data)
    {
        $type = $data['type'];
        $url = $data['url'] ?? null;
        $file = $data['file'] ?? null;

        $endpoint = route('contents-summary.summarize', ['type' => $type]);

        try {
            if ($url) {
                return Http::post($endpoint, ['url' => $url]);
            } elseif ($file) {
                \Log::info('File upload information', [
                    'file' => $file,
                    'disk' => 'public',
                    'directory' => 'uploads',
                ]);

                $filePath = Storage::disk('public')->path($file);
                
                if (!file_exists($filePath)) {
                    \Log::error('File does not exist', ['filePath' => $filePath]);
                    throw new \Exception("File not found: {$filePath}");
                }

                \Log::info('File exists', ['filePath' => $filePath]);

                // 파일을 UploadedFile 객체로 변환
                $uploadedFile = new UploadedFile($filePath, basename($file));

                // multipart/form-data 형식으로 파일 전송
                return Http::attach(
                    'file',  // 'file'로 변경
                    $uploadedFile->getContent(),
                    $uploadedFile->getClientOriginalName(),
                    ['Content-Type' => $uploadedFile->getMimeType()]
                )->post($endpoint);
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error processing file')
                ->body($e->getMessage())
                ->danger()
                ->send();

            \Log::error('File processing error', [
                'error' => $e->getMessage(),
                'file' => $file,
                'filePath' => $filePath ?? null
            ]);

            return null;
        }

        return null;
    }
}