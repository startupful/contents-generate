<?php

namespace Startupful\ContentsGenerate\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Arr;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\ToggleButtons;
use Startupful\ContentsGenerate\Models\Logic;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Startupful\ContentsGenerate\Resources\LogicResource\Pages;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Illuminate\Database\Eloquent\Model;

class LogicResource extends Resource
{
    protected static ?string $model = Logic::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Contents Generate';

    protected static ?string $pluralModelLabel = 'Contents Generate';

    public static function getNavigationGroup(): ?string
    {
        return __('AI');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 1, 'lg' => 4])
                    ->schema([
                        Forms\Components\Section::make('Logic Info')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->label('Logic Name')
                                    ->helperText('Enter a unique name for this logic. This will be used to identify the logic in the system.'),
                                Forms\Components\TextInput::make('description')
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->label('Description')
                                    ->helperText('Provide a brief description of what this logic does. This helps others understand the purpose of this logic.'),
                                TagsInput::make('tags')
                                    ->reorderable()
                                    ->nestedRecursiveRules([
                                        'min:3',
                                        'max:255',
                                    ])
                                    ->label('Tags')
                                    ->helperText('Add relevant tags to categorize this logic. Tags help in organizing and searching for logic later.')
                            ])
                            ->columnSpan(['lg' => 1]),

                        Forms\Components\Section::make('Logic Steps')
                            ->schema([
                                Forms\Components\Repeater::make('steps')
                                    ->label('')
                                    ->schema([
                                        ToggleButtons::make('type')
                                            ->options([
                                                'input' => 'Input',
                                                'scrap_webpage' => 'Scrap Webpage',
                                                'generate_text' => 'Generate Text',
                                                'generate_image' => 'Generate Image',
                                                'generate_ui_ux' => 'Generate UI/UX',
                                                'generate_audio' => 'Generate Audio',
                                                'generate_excel' => 'Generate Excel',
                                            ])
                                            ->icons([
                                                'input' => 'heroicon-o-cursor-arrow-rays',
                                                'scrap_webpage' => 'heroicon-o-globe-alt',
                                                'generate_text' => 'heroicon-o-document-text',
                                                'generate_image' => 'heroicon-o-photo',
                                                'generate_ui_ux' => 'heroicon-o-paint-brush',
                                                'generate_audio' => 'heroicon-o-speaker-wave',
                                                'generate_excel' => 'heroicon-o-table-cells',
                                            ])
                                            ->default('input')
                                            ->inline()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                                $defaultText = match ($state) {
                                                    'input' => 'Collect user input',
                                                    'scrap_webpage' => 'Extract data from a webpage',
                                                    'generate_text' => 'Generate text using AI',
                                                    'generate_image' => 'Generate image using AI',
                                                    'generate_ui_ux' => 'Generate UI/UX design using AI',
                                                    'generate_audio' => 'Generate audio using AI',
                                                    'generate_excel' => 'Generate Excel spreadsheet',
                                                    default => '',
                                                };
                                                $set('logic_process', $defaultText);
                                            })
                                            ->columnSpanFull(),
                                        
                                        // Input type fields
                                        Forms\Components\Repeater::make('input_fields')
                                            ->schema([
                                                // 1행: 타입 선택
                                                Select::make('type')
                                                    ->options([
                                                        'text' => 'Text Input',
                                                        'textarea' => 'Text Area',
                                                        'select' => 'Select',
                                                        'radio' => 'Radio Button',
                                                        'multiselect' => 'Multi Select',
                                                        'file' => 'File Upload',
                                                    ])
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(fn (callable $set) => $set('options', null))
                                                    ->columnSpan('full'),

                                                // 2행: 라벨, 설명 (각각 반씩 너비 차지)
                                                Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('label')
                                                            ->live(onBlur: true)
                                                            ->required(),
                                                        Forms\Components\TextInput::make('description'),
                                                    ])
                                                    ->columnSpan('full'),

                                                // 3행: 옵션 및 파일 유형 선택
                                                TextInput::make('options')
                                                    ->visible(fn (callable $get) => in_array($get('type'), ['select', 'multiselect', 'radio']))
                                                    ->helperText('Type an option and press Enter to add')
                                                    ->columnSpan('full'),

                                                Radio::make('file_type')
                                                    ->options([
                                                        'image' => 'Image',
                                                        'document' => 'Document(docs, pdf..)',
                                                        'spreadsheet' => 'Spreadsheet(Excel)',
                                                        'presentation' => 'Presentation(PPT)',
                                                        'audio' => 'Aduio',
                                                        'video' => 'Video',
                                                        'any' => 'Any File',
                                                    ])
                                                    ->inline()
                                                    ->inlineLabel(false)
                                                    ->visible(fn (callable $get) => $get('type') === 'file')
                                                    ->columnSpan('full'),
                                            ])
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'input')
                                            ->createItemButtonLabel('Add Input Field')
                                            ->columns(1)
                                            ->required()
                                            ->cloneable()
                                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                                $set('steps', array_filter($state, fn ($item) => !empty($item['logic_process'])));
                                            }),

                                            

                                        // Scrap Webpage type fields
                                        Forms\Components\Select::make('url_source')
                                            ->options([
                                                'user_input' => 'User Input',
                                                'fixed' => 'Fixed URL',
                                            ])
                                            ->required()
                                            ->default('user_input')
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['scrap_webpage', 'scrap_youtube']))
                                            ->reactive(),
                                        Forms\Components\TextInput::make('fixed_url')
                                            ->suffixIcon('heroicon-m-globe-alt')
                                            ->visible(fn (Forms\Get $get) =>  in_array($get('type'), ['scrap_webpage', 'scrap_youtube']) && $get('url_source') === 'fixed'),
                                        Forms\Components\Select::make('extraction_type')
                                            ->options([
                                                'text_only' => 'Text Only',
                                                'html' => 'HTML',
                                            ])
                                            ->default('text_only')
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'scrap_webpage'),

                                    // Internet Search fields
                                    TextInput::make('search_query')
                                        ->required()
                                        ->visible(fn (Forms\Get $get) => $get('type') === 'internet_search')
                                        ->label('Search Query')
                                        ->placeholder('Enter your search query'),

                                    TextInput::make('number_of_pages')
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(1) 
                                        ->visible(fn (Forms\Get $get) => $get('type') === 'internet_search')
                                        ->label('Number of Pages')
                                        ->placeholder('Enter number of pages to search'),

                                    Select::make('search_type')
                                        ->options([
                                            'all' => 'All',
                                            'news' => 'News',
                                            'images' => 'Images',
                                            'maps' => 'Maps',
                                            'books' => 'Books',
                                            'web' => 'Web',
                                        ])
                                        ->default('all')
                                        ->visible(fn (Forms\Get $get) => $get('type') === 'internet_search')
                                        ->label('Search Type'),

                                    Select::make('time_period')
                                        ->options([
                                            'all' => 'All Time',
                                            'hour' => 'Past Hour',
                                            'day' => 'Past 24 Hours',
                                            'week' => 'Past Week',
                                            'month' => 'Past Month',
                                            'year' => 'Past Year',
                                        ])
                                        ->default('all')
                                        ->visible(fn (Forms\Get $get) => $get('type') === 'internet_search')
                                        ->label('Time Period'),

                                        // Generate Text type fields
                                        Forms\Components\Textarea::make('prompt')
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_image', 'generate_excel', 'generate_ppt', 'generate_ui_ux'])),
                                        Forms\Components\Textarea::make('background_information')
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_image', 'generate_excel', 'generate_ppt', 'generate_ui_ux'])),
                                        Forms\Components\TextInput::make('reference_file')
                                            ->label('Reference File (Optional)')
                                            ->helperText('Enter the placeholder for the reference file (e.g., {{reference_file}})')
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_image', 'generate_excel', 'generate_ppt', 'generate_ui_ux'])),
                                        Forms\Components\Select::make('ai_provider')
                                            ->options([
                                                'anthropic' => 'Anthropic',
                                                'openai' => 'OpenAI',
                                                'gemini' => 'Gemini',
                                            ])
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_excel', 'generate_ppt', 'generate_ui_ux']))
                                            ->reactive()
                                            ->afterStateUpdated(fn (Forms\Set $set) => $set('ai_model', null)),
                                        Forms\Components\Select::make('ai_model')
                                            ->options(fn (Forms\Get $get): array => self::getAIModelOptions($get('ai_provider')))
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_excel', 'generate_ppt', 'generate_ui_ux']))
                                            ->required()
                                            ->reactive(),
                                        Forms\Components\TextInput::make('temperature')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(1)
                                            ->step(0.1)
                                            ->default(0.7)
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_excel', 'generate_ppt', 'generate_ui_ux']))
                                            ->helperText('Temperature (0.0 to 1.0)'),

                                            // Hidden fields for Generate Image
                                        Forms\Components\Hidden::make('ai_provider')
                                            ->default('openai')
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'generate_image'),
                                        Forms\Components\Hidden::make('ai_model')
                                            ->default('dall-e-3')
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'generate_image'),

                                        Forms\Components\TextInput::make('audio_text')
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'generate_audio')
                                            ->label('Text to Convert')
                                            ->helperText('Enter the text you want to convert to audio.'),
                                        Forms\Components\Select::make('audio_model')
                                            ->options([
                                                'tts-1' => 'TTS-1',
                                                'tts-1-hd' => 'TTS-1-HD',
                                            ])
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'generate_audio')
                                            ->label('Audio Model'),
                                        Forms\Components\Select::make('voice')
                                            ->options([
                                                'alloy' => 'Alloy',
                                                'echo' => 'Echo',
                                                'fable' => 'Fable',
                                                'onyx' => 'Onyx',
                                                'nova' => 'Nova',
                                                'shimmer' => 'Shimmer',
                                            ])
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'generate_audio')
                                            ->label('Voice'),

                                        // Generate Excel type fields
                                        Forms\Components\Repeater::make('excel_columns')
                                            ->schema([
                                                TextInput::make('sheet_name')
                                                    ->default('Sheet1')
                                                    ->required(),
                                                Forms\Components\Fieldset::make('Global Header Settings')
                                                    ->schema([
                                                        Grid::make(2)
                                                            ->schema([
                                                                ColorPicker::make('global_header_background')
                                                                    ->label('Header Background Color')
                                                                    ->format('#hex'),
                                                                ColorPicker::make('global_header_text_color')
                                                                    ->label('Header Text Color')
                                                                    ->format('#hex'),
                                                            ]),
                                                        Grid::make(2)
                                                            ->schema([
                                                                Forms\Components\TextInput::make('global_header_height')
                                                                    ->numeric()
                                                                    ->label('Header Height'),
                                                                Forms\Components\Select::make('global_header_alignment')
                                                                    ->options([
                                                                        'left' => 'Left',
                                                                        'center' => 'Center',
                                                                        'right' => 'Right',
                                                                    ])
                                                                    ->label('Header Alignment'),
                                                            ]),
                                                    ]),
                                                Repeater::make('columns')
                                                    ->schema([
                                                        Grid::make(3)
                                                            ->schema([
                                                                Forms\Components\TextInput::make('name')
                                                                    ->required()
                                                                    ->label('Column Name')
                                                                    ->columnSpan(1),
                                                                    Forms\Components\TextInput::make('description')
                                                                    ->label('Column Description')
                                                                    ->columnSpan(2),
                                                            ]),
                                                        Grid::make(3)
                                                            ->schema([
                                                                Forms\Components\TextInput::make('width')
                                                                    ->numeric()
                                                                    ->label('Width'),
                                                                Forms\Components\Select::make('alignment')
                                                                    ->options([
                                                                        'left' => 'Left',
                                                                        'center' => 'Center',
                                                                        'right' => 'Right',
                                                                    ])
                                                                    ->label('Alignment'),
                                                                Toggle::make('merge_duplicates')
                                                                    ->inline(false)
                                                                    ->label('Merge Duplicates')
                                                                    ->helperText('Merge cells with duplicate data'),
                                                            ]),
                                                    ])
                                                    ->columns(1)
                                                    ->createItemButtonLabel('Add Column')
                                                    ->label('Columns'),
                                                    
                                            ])
                                            ->columns(1)
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'generate_excel')
                                            ->createItemButtonLabel('Add Sheet')
                                            ->label('Excel Configuration'),


                           
                                            


                                    ])
                                    ->columnSpanFull()
                                    ->createItemButtonLabel('Add Step')
                                    ->reorderable()
                                    ->itemLabel(function (array $state): string {
                                        static $stepCounter = 0;
                                        $stepNumber = ++$stepCounter;
                                        $type = ucfirst($state['type'] ?? 'Unknown');
                                        return "Step {$stepNumber}: {$type}";
                                    })
                                    ->collapsible()
                                    ->defaultItems(1)
                                    ->live()
                                    ->afterStateHydrated(function (Repeater $component) {
                                        $items = $component->getState();
                                        if (is_array($items)) {
                                            $component->state(array_values($items));
                                        }
                                    }),
                            ])
                            ->columnSpan(['lg' => 3]),
                    ]),
            ]);
    }

    public static function getAIModelOptions(?string $provider): array
    {
        if (!$provider) {
            return [];
        }

        switch ($provider) {
            case 'anthropic':
                return [
                    'claude-2' => 'Claude 2',
                    'claude-instant-1' => 'Claude Instant 1',
                ];
            case 'openai':
                return [
                    'gpt-4o-2024-05-13' => 'GPT-4o(Max 4k Token)',
                    'gpt-4o-2024-08-06' => 'GPT-4o(Max 16k Token)',
                    'gpt-4o-mini-2024-07-18' => 'GPT-4o-mini',
                    'gpt-4-turbo-2024-04-09' => 'GPT-4-Turbo',
                    'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                ];
            case 'gemini':
                return [
                    'gemini-pro' => 'Gemini Pro',
                ];
            default:
                return [];
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TagsColumn::make('tags')
                        ->searchable()
                        ->extraAttributes(['class' => 'mb-2']),
                    TextColumn::make('name')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->extraAttributes(['class' => 'text-lg mb-2']),
                    TextColumn::make('description')
                        ->searchable()
                        ->limit(100)
                        ->tooltip(function (TextColumn $column): ?string {
                            $state = $column->getState();
                            return strlen($state) > 100 ? $state : null;
                        }),
                ])->space(2),
            ])
            ->contentGrid([
                'md' => 2,
                'lg' => 3,
                'xl' => 4,
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogics::route('/'),
            'create' => Pages\CreateLogic::route('/create'),
            'view' => Pages\ViewLogic::route('/{record}'),
            'edit' => Pages\EditLogic::route('/{record}/edit'),
        ];
    }

    public static function saving(Model $record, array $data): void
    {
        // 저장 전 steps 데이터 완전 리셋
        $record->steps = [];

        // 새로운 steps 데이터만 설정
        $newSteps = array_map(function ($step, $index) {
            $newStep = [
                'type' => $step['type'],
                'step_number' => $index + 1,
                'uuid' => $step['uuid'] ?? uniqid(),
            ];

            switch ($step['type']) {
                case 'input':
                    $newStep['input_fields'] = array_map(function ($field) {
                        return [
                            'label' => $field['label'] ?? null,
                            'description' => $field['description'] ?? null,
                            'type' => $field['type'] ?? null,
                            'options' => $field['options'] ?? null,
                            'file_type' => $field['file_type'] ?? null,
                        ];
                    }, $step['input_fields'] ?? []);
                    break;
                case 'scrap_youtube':
                case 'scrap_webpage':
                    $newStep['url_source'] = $step['url_source'] ?? null;
                    $newStep['fixed_url'] = $step['fixed_url'] ?? null;
                    $newStep['extraction_type'] = $step['extraction_type'] ?? null;
                    break;
                case 'generate_text':
                    $newStep['prompt'] = $step['prompt'] ?? null;
                    $newStep['background_information'] = $step['background_information'] ?? null;
                    $newStep['ai_provider'] = $step['ai_provider'] ?? null;
                    $newStep['ai_model'] = $step['ai_model'] ?? null;
                    $newStep['temperature'] = $step['temperature'] ?? null;
                    $newStep['reference_file'] = $step['reference_file'] ?? null;
                    break;
                case 'generate_image':
                case 'generate_ui_ux':
                    $newStep['prompt'] = $step['prompt'] ?? null;
                    $newStep['background_information'] = $step['background_information'] ?? null;
                    $newStep['ai_provider'] = $step['ai_provider'] ?? null;
                    $newStep['ai_model'] = $step['ai_model'] ?? null;
                    $newStep['temperature'] = $step['temperature'] ?? null;
                    break;
                case 'generate_audio':
                    $newStep['audio_text'] = $step['audio_text'] ?? null;
                    $newStep['audio_model'] = $step['audio_model'] ?? null;
                    $newStep['voice'] = $step['voice'] ?? null;
                    break;
                case 'generate_excel':
                    $newStep['prompt'] = $step['prompt'] ?? null;
                    $newStep['background_information'] = $step['background_information'] ?? null;
                    $newStep['ai_provider'] = $step['ai_provider'] ?? null;
                    $newStep['ai_model'] = $step['ai_model'] ?? null;
                    $newStep['temperature'] = $step['temperature'] ?? null;
                    $newStep['excel_columns'] = array_map(function ($sheet) {
                        return [
                            'sheet_name' => $sheet['sheet_name'] ?? null,
                            'global_header_background' => $sheet['global_header_background'] ?? null,
                            'global_header_text_color' => $sheet['global_header_text_color'] ?? null,
                            'global_header_height' => $sheet['global_header_height'] ?? null,
                            'global_header_alignment' => $sheet['global_header_alignment'] ?? null,
                            'columns' => array_map(function ($column) {
                                return [
                                    'name' => $column['name'] ?? null,
                                    'description' => $column['description'] ?? null,
                                    'width' => $column['width'] ?? null,
                                    'alignment' => $column['alignment'] ?? null,
                                    'merge_duplicates' => $column['merge_duplicates'] ?? false,
                                ];
                            }, $sheet['columns'] ?? []),
                        ];
                    }, $step['excel_columns'] ?? []);
                    break;
                case 'generate_excel':
                    $newStep['prompt'] = $step['prompt'] ?? null;
                    $newStep['background_information'] = $step['background_information'] ?? null;
                    $newStep['ai_provider'] = $step['ai_provider'] ?? null;
                    $newStep['ai_model'] = $step['ai_model'] ?? null;
                    $newStep['temperature'] = $step['temperature'] ?? null;
                    break;
            }

            return $newStep;
        }, $data['steps'] ?? [], array_keys($data['steps'] ?? []));

        $record->steps = $newSteps;

        // 다른 필드들 설정
        $record->name = $data['name'];
        $record->description = $data['description'] ?? null;
        $record->tags = $data['tags'] ?? null;
    }
}