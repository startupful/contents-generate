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
                        
                        Notification::make()
                            ->title('Summary generated successfully')
                            ->success()
                            ->send();

                        // 여기에 추가적인 성공 처리 로직을 넣을 수 있습니다.
                        // 예: 데이터베이스에 요약 저장, 페이지 새로고침 등
                    } else {
                        Notification::make()
                            ->title('Failed to generate summary')
                            ->body('Please check the logs for more information.')
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
                $response = Http::post($endpoint, ['url' => $url]);
            } elseif ($file) {
                $filePath = Storage::disk('public')->path($file);
                
                if (!file_exists($filePath)) {
                    throw new \Exception("File not found: {$filePath}");
                }

                $uploadedFile = new UploadedFile($filePath, basename($file));

                $response = Http::attach(
                    'file',
                    $uploadedFile->getContent(),
                    $uploadedFile->getClientOriginalName(),
                    ['Content-Type' => $uploadedFile->getMimeType()]
                )->post($endpoint);
            } else {
                throw new \Exception("Invalid input: Neither URL nor file provided");
            }

            if ($response->successful()) {
                $summaryData = $response->json();
        
                Notification::make()
                    ->title('Summary generated successfully')
                    ->success()
                    ->send();
        
                // 리다이렉션 대신 현재 페이지에서 데이터 표시
                $this->refreshList();
                return;
            } else {
                \Log::error('Failed to generate summary', [
                    'type' => $type,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Failed to generate summary: " . $response->body());
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error processing request')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }
}