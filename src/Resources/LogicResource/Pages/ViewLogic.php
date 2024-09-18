<?php

namespace Startupful\ContentsGenerate\Resources\LogicResource\Pages;

use Startupful\ContentsGenerate\Resources\LogicResource;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ViewLogic extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = LogicResource::class;

    public $record;
    public $formData = [];

    protected static string $view = 'contents-generate::pages.view-logic';

    public function mount($record): void
    {
        $this->record = static::getResource()::resolveRecordRouteBinding($record);
        
        if (!$this->record) {
            abort(404);
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Input Data')
                    ->schema($this->generateInputFields())
                    ->columns(1)
            ])
            ->statePath('formData');
    }

    protected function generateInputFields(): array
    {
        $steps = $this->record->steps ?? [];
        $fields = [];

        foreach ($steps as $step) {
            if ($step['type'] === 'input') {
                foreach ($step['input_fields'] as $field) {
                    $fields[] = $this->createFormField($field, $field['label']);
                }
            }
        }

        return $fields;
    }

    protected function createFormField(array $field, string $name): Forms\Components\Field
    {
        $baseField = match ($field['type']) {
            'text' => Forms\Components\TextInput::make($name),
            'textarea' => Forms\Components\Textarea::make($name),
            'select' => Forms\Components\Select::make($name)->options($this->parseOptions($field['options'])),
            'radio' => Forms\Components\Radio::make($name)->options($this->parseOptions($field['options'])),
            'multiselect' => Forms\Components\Select::make($name)->multiple()->options($this->parseOptions($field['options'])),
            'file' => Forms\Components\FileUpload::make($name)->acceptedFileTypes($this->getAcceptedFileTypes($field['file_type'])),
            default => Forms\Components\TextInput::make($name),
        };

        return $baseField
            ->label($field['label'])
            ->helperText($field['description'] ?? '')
            ->required();
    }

    protected function parseOptions(?string $options): array
    {
        if (empty($options)) {
            return [];
        }
        return array_combine(
            array_map('trim', explode(',', $options)),
            array_map('trim', explode(',', $options))
        );
    }

    protected function getAcceptedFileTypes(?string $fileType): array
    {
        return match ($fileType) {
            'image' => ['image/*'],
            'document' => [
                'application/pdf', 
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                'text/plain'
            ],
            'spreadsheet' => [
                'application/vnd.ms-excel', 
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ],
            'presentation' => [
                'application/vnd.ms-powerpoint', 
                'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ],
            'audio' => ['audio/*', 'video/mp4'],
            'video' => ['video/*'],
            'any' => ['*/*'],
            default => [],
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editLogic')
                ->label('Edit Logic')
                ->url(fn () => $this->getResource()::getUrl('edit', ['record' => $this->record]))
                ->icon('heroicon-o-pencil'),
            Action::make('processLogic')
                ->label('Process Logic')
                ->action('processLogic')
                ->icon('heroicon-o-sparkles'),
        ];
    }

    public function processLogic()
    {
        try {
            $inputData = $this->formData;
            Log::info('Starting to process logic', ['steps' => $this->record->steps, 'inputData' => $inputData]);

            $processedInputData = $this->processInputDataRecursively($inputData);

            Log::info('Processed input data', ['processedInputData' => $processedInputData]);

            $response = Http::post(route('process.logic'), [
                'steps' => $this->record->steps,
                'inputData' => $processedInputData,
                'logic_id' => $this->record->id,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Logic processed successfully', ['result' => $result]);

                if (isset($result['success']) && $result['success']) {
                    Notification::make()
                        ->title('Success')
                        ->body($result['message'] ?? "Content generated and stored successfully.")
                        ->success()
                        ->send();
                } else {
                    throw new \Exception($result['message'] ?? 'Unknown error occurred');
                }
            } else {
                $errorMessage = $response->body();
                Log::error('Failed to process logic', ['response' => $errorMessage]);
                throw new \Exception('Failed to process logic: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error processing logic', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            Notification::make()
                ->title('Error')
                ->body('Failed to process logic: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function processInputDataRecursively($data)
    {
        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->processInputDataRecursively($value);
            }
            return $result;
        } elseif ($data instanceof TemporaryUploadedFile) {
            try {
                if ($data->exists()) {
                    $path = $data->store('uploads', 'public');
                    $fullPath = Storage::disk('public')->path($path);
                    
                    Log::info('File uploaded', [
                        'original_name' => $data->getClientOriginalName(),
                        'mime_type' => $data->getMimeType(),
                        'size' => $data->getSize(),
                        'stored_path' => $path,
                        'full_path' => $fullPath,
                    ]);

                    return [
                        'path' => $path,
                        'full_path' => $fullPath,
                        'original_name' => $data->getClientOriginalName(),
                        'mime_type' => $data->getMimeType(),
                    ];
                } else {
                    Log::error('Temporary file does not exist', ['file' => $data->getFilename()]);
                    return null;
                }
            } catch (\Exception $e) {
                Log::error('Error processing file upload', [
                    'error' => $e->getMessage(),
                    'file' => $data->getFilename()
                ]);
                return null;
            }
        }

        return $data;
    }

    public function getTitle(): string
    {
        $title = $this->record->name ?? 'View Logic';
        return $title;
    }

    public function getSubheading(): ?string
    {
        return $this->record->description ?? null;
    }

    private function replacePlaceholdersInSteps($steps, $processedInputData)
    {
        return array_map(function ($step) use ($processedInputData) {
            if (isset($step['reference_file'])) {
                $placeholder = trim($step['reference_file'], '{}');
                if (isset($processedInputData[$placeholder])) {
                    $step['reference_file'] = $processedInputData[$placeholder];
                }
            }
            return $step;
        }, $steps);
    }
}