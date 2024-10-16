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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\DB;

class LogicResource extends Resource
{
    protected static ?string $model = Logic::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    public static function getNavigationGroup(): ?string
    {
        return __('AI');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationLabel(): string
    {
        return __('startupful-plugin.content_creation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('startupful-plugin.content_creation');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 1, 'lg' => 4])
                    ->schema([
                        Forms\Components\Section::make(__('startupful-plugin.logic_info'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->label(__('startupful-plugin.logic_name'))
                                    ->helperText(__('startupful-plugin.logic_name_help')),
                                Forms\Components\TextInput::make('description')
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->label(__('startupful-plugin.description'))
                                    ->helperText(__('startupful-plugin.logic_description_help')),
                                TagsInput::make('tags')
                                    ->reorderable()
                                    ->nestedRecursiveRules([
                                        'min:1',
                                        'max:255',
                                    ])
                                    ->label(__('startupful-plugin.tags'))
                                    ->helperText(__('startupful-plugin.tags_help'))
                            ])
                            ->columnSpan(['lg' => 1]),

                        Forms\Components\Section::make(__('startupful-plugin.logic_steps'))
                            ->schema([
                                Forms\Components\Repeater::make('steps')
                                    ->label('')
                                    ->schema([
                                        ToggleButtons::make('type')
                                            ->options([
                                                'input' => __('startupful-plugin.input'),
                                                'scrap_webpage' => __('startupful-plugin.web_scrap'),
                                                'generate_text' => __('startupful-plugin.text_generation'),
                                                'generate_image' => __('startupful-plugin.image_generation'),
                                                'generate_ui_ux' => __('startupful-plugin.uiux_generation'),
                                                'generate_audio' => __('startupful-plugin.audio_generation'),
                                                'generate_excel' => __('startupful-plugin.excel_generation'),
                                                'content_integration' => __('startupful-plugin.content_integration'),
                                            ])
                                            ->icons([
                                                'input' => 'heroicon-o-cursor-arrow-rays',
                                                'web_crawling' => 'heroicon-o-magnifying-glass',
                                                'scrap_webpage' => 'heroicon-o-globe-alt',
                                                'generate_text' => 'heroicon-o-document-text',
                                                'generate_image' => 'heroicon-o-photo',
                                                'generate_ui_ux' => 'heroicon-o-paint-brush',
                                                'generate_audio' => 'heroicon-o-speaker-wave',
                                                'generate_excel' => 'heroicon-o-table-cells',
                                                'content_integration' => 'heroicon-o-squares-plus',
                                            ])
                                            ->default('input')
                                            ->inline()
                                            ->label(__('startupful-plugin.type'))
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
                                            ->label(__('startupful-plugin.input_fields'))
                                            ->schema([
                                                // 1행: 타입 선택
                                                Select::make('type')
                                                    ->options([
                                                        'text' => __('startupful-plugin.short_text'),
                                                        'textarea' => __('startupful-plugin.long_text'),
                                                        'select' => __('startupful-plugin.select'),
                                                        'radio' => __('startupful-plugin.radio_button'),
                                                        'multiselect' => __('startupful-plugin.multi_select'),
                                                        'file' => __('startupful-plugin.file_upload'),
                                                        'time' => __('startupful-plugin.time'),
                                                    ])
                                                    ->required()
                                                    ->reactive()
                                                    ->label(__('startupful-plugin.type'))
                                                    ->afterStateUpdated(fn (callable $set) => $set('options', null))
                                                    ->columnSpan('full'),

                                                // 2행: 라벨, 설명 (각각 반씩 너비 차지)
                                                Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('label')
                                                            ->live(onBlur: true)
                                                            ->label(__('startupful-plugin.label'))
                                                            ->required(),
                                                        Forms\Components\TextInput::make('description')
                                                            ->label(__('startupful-plugin.description')),
                                                    ])
                                                    ->columnSpan('full'),

                                                // 3행: 옵션 및 파일 유형 선택
                                                TextInput::make('options')
                                                    ->visible(fn (callable $get) => in_array($get('type'), ['select', 'multiselect', 'radio']))
                                                    ->helperText('각 옵션은 쉼표(,)로 구분하여 항목을 추가하세요. 예: 옵션1, 옵션2, 옵션3')
                                                    ->label(__('startupful-plugin.option'))
                                                    ->columnSpan('full'),

                                                Radio::make('file_type')
                                                    ->options([
                                                        'image' => __('startupful-plugin.image'),
                                                        'document' => __('startupful-plugin.document'),
                                                        'spreadsheet' => 'Spreadsheet(Excel)',
                                                        'presentation' => __('startupful-plugin.presentation'),
                                                        'audio' => __('startupful-plugin.audio'),
                                                        'video' => __('startupful-plugin.video'),
                                                    ])
                                                    ->inline()
                                                    ->label(__('startupful-plugin.file_type'))
                                                    ->inlineLabel(false)
                                                    ->visible(fn (callable $get) => $get('type') === 'file')
                                                    ->columnSpan('full'),
                                            ])
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'input')
                                            ->createItemButtonLabel(__('startupful-plugin.add_input'))
                                            ->columns(1)
                                            ->required()
                                            ->cloneable()
                                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                                $set('steps', array_filter($state, fn ($item) => !empty($item['logic_process'])));
                                            }),


                                            Forms\Components\TextInput::make('search_query')
                                                ->label(__('startupful-plugin.search_query'))
                                                ->required()
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'web_crawling'),
                                            Forms\Components\TextInput::make('num_results')
                                                ->label(__('startupful-plugin.num_results'))
                                                ->numeric()
                                                ->default(10)
                                                ->required()
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'web_crawling'),
                                            Forms\Components\Select::make('search_type')
                                                ->label(__('startupful-plugin.search_type'))
                                                ->options([
                                                    'web' => __('startupful-plugin.web'),
                                                    'image' => __('startupful-plugin.image'),
                                                ])
                                                ->default('web')
                                                ->required()
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'web_crawling'),
                                            Forms\Components\TextInput::make('file_type')
                                                ->label(__('startupful-plugin.file_type'))
                                                ->placeholder('pdf,doc,xls')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'web_crawling'),
                                            Forms\Components\DatePicker::make('date_restrict')
                                                ->label(__('startupful-plugin.date_restrict'))
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'web_crawling'),
                                            Forms\Components\TextInput::make('site_search')
                                                ->label(__('startupful-plugin.site_search'))
                                                ->placeholder('example.com')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'web_crawling'),

                                            

                                        // Scrap Webpage type fields
                                        Forms\Components\Select::make('url_source')
                                            ->options([
                                                'user_input' => __('startupful-plugin.apply_input'),
                                                'fixed' => __('startupful-plugin.apply_directly'),
                                            ])
                                            ->required()
                                            ->label(__('startupful-plugin.url_source'))
                                            ->default('user_input')
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['scrap_webpage', 'scrap_youtube']))
                                            ->reactive(),
                                        Forms\Components\TextInput::make('fixed_url')
                                            ->suffixIcon('heroicon-m-globe-alt')
                                            ->visible(fn (Forms\Get $get) =>  in_array($get('type'), ['scrap_webpage', 'scrap_youtube']) && $get('url_source') === 'fixed'),
                                        Forms\Components\Select::make('extraction_type')
                                            ->options([
                                                'text_only' => __('startupful-plugin.text_only'),
                                                'html' => __('startupful-plugin.html'),
                                                //'specific_data' => __('startupful-plugin.specific_data'),
                                            ])
                                            ->default('text_only')
                                            ->label(__('startupful-plugin.extraction_type'))
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'scrap_webpage'),
                                        //Forms\Components\CheckboxList::make('specific_data_types')
                                        //    ->options([
                                        //        'site' => __('startupful-plugin.site'),
                                        //        'author' => __('startupful-plugin.author'),
                                        //        'url' => __('startupful-plugin.url'),
                                        //        'email' => __('startupful-plugin.email'),
                                        //        'phone' => __('startupful-plugin.phone'),
                                        //        'address' => __('startupful-plugin.address'),
                                        //    ])
                                        //    ->label(__('startupful-plugin.specific_data_types'))
                                        //    ->visible(fn (Forms\Get $get) => $get('type') === 'scrap_webpage' && $get('extraction_type') === 'specific_data')
                                        //    ->columns(2),

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
                                            ->label(__('startupful-plugin.prompt'))
                                            ->helperText(__('startupful-plugin.prompt_help'))
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_image', 'generate_excel', 'generate_ppt', 'generate_ui_ux'])),
                                        Forms\Components\Textarea::make('background_information')
                                            ->required()
                                            ->helperText(__('startupful-plugin.background_info_help'))
                                            ->label(__('startupful-plugin.background_info'))
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_image', 'generate_excel', 'generate_ppt', 'generate_ui_ux'])),
                                        Forms\Components\TextInput::make('reference_file')
                                            ->label(__('startupful-plugin.reference_file_optional'))
                                            ->helperText(__('startupful-plugin.reference_file_help'))
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_image', 'generate_excel', 'generate_ppt', 'generate_ui_ux'])),
                                        Forms\Components\Select::make('ai_provider')
                                            ->options([
                                                'anthropic' => 'Anthropic(Claude)',
                                                'openai' => 'OpenAI(GPT)',
                                                'gemini' => 'Google(Gemini)',
                                            ])
                                            ->label(__('startupful-plugin.ai_service'))
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_excel', 'generate_ppt', 'generate_ui_ux']))
                                            ->reactive()
                                            ->afterStateUpdated(fn (Forms\Set $set) => $set('ai_model', null)),
                                        Forms\Components\Select::make('ai_model')
                                            ->options(fn (Forms\Get $get): array => self::getAIModelOptions($get('ai_provider')))
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_excel', 'generate_ppt', 'generate_ui_ux']))
                                            ->required()
                                            ->label(__('startupful-plugin.ai_model'))
                                            ->reactive(),
                                        Forms\Components\TextInput::make('temperature')
                                            ->numeric()
                                            ->minValue(0)
                                            ->label(__('startupful-plugin.temperature'))
                                            ->maxValue(1)
                                            ->step(0.1)
                                            ->default(0.7)
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['generate_text', 'generate_excel', 'generate_ppt', 'generate_ui_ux']))
                                            ->helperText('온도 설정 (0.0 ~ 1.0). 낮은 값 (0에 가까움)은 더 일관된 출력을, 높은 값 (1에 가까움)은 더 다양하고 창의적인 출력을 생성합니다. 기본값 0.7은 균형 잡힌 결과를 제공합니다.'),

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
                                            ->label(__('startupful-plugin.text_to_convert'))
                                            ->helperText(__('startupful-plugin.reference_file_help')),
                                        Forms\Components\Select::make('audio_model')
                                            ->options([
                                                'tts-1' => 'TTS-1',
                                                'tts-1-hd' => 'TTS-1-HD',
                                            ])
                                            ->required()
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'generate_audio')
                                            ->label(__('startupful-plugin.audio_model')),
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
                                            ->label(__('startupful-plugin.voice')),

                                            Forms\Components\Select::make('ai_provider')
                                                ->options([
                                                    'huggingface' => 'Hugging Face',
                                                    'openai' => 'OpenAI',
                                                ])
                                                ->label(__('startupful-plugin.ai_service'))
                                                ->required()
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'generate_image')
                                                ->reactive()
                                                ->afterStateUpdated(fn (Forms\Set $set) => $set('ai_model', null)),
                                            Forms\Components\Select::make('ai_model')
                                                ->options(fn (Forms\Get $get): array => self::getImageAIModelOptions($get('ai_provider')))
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'generate_image')
                                                ->required()
                                                ->label(__('startupful-plugin.ai_model'))
                                                ->reactive(),

                                        // Generate Excel type fields
                                        Forms\Components\Repeater::make('excel_columns')
                                            ->schema([
                                                TextInput::make('sheet_name')
                                                    ->default('Sheet1')
                                                    ->label(__('startupful-plugin.sheet_name'))
                                                    ->required(),
                                                Forms\Components\Fieldset::make('Global Header Settings')
                                                    ->label(__('startupful-plugin.global_header_settings'))
                                                    ->schema([
                                                        Grid::make(2)
                                                            ->schema([
                                                                ColorPicker::make('global_header_background')
                                                                    ->label(__('startupful-plugin.header_background_color'))
                                                                    ->format('#hex'),
                                                                ColorPicker::make('global_header_text_color')
                                                                    ->label(__('startupful-plugin.header_text_color'))
                                                                    ->format('#hex'),
                                                            ]),
                                                        Grid::make(2)
                                                            ->schema([
                                                                Forms\Components\TextInput::make('global_header_height')
                                                                    ->numeric()
                                                                    ->label(__('startupful-plugin.header_height')),
                                                                Forms\Components\Select::make('global_header_alignment')
                                                                    ->options([
                                                                        'left' => __('startupful-plugin.left'),
                                                                        'center' => __('startupful-plugin.center'),
                                                                        'right' => __('startupful-plugin.right'),
                                                                    ])
                                                                    ->label(__('startupful-plugin.header_alignment')),
                                                            ]),
                                                    ]),
                                                Repeater::make('columns')
                                                    ->schema([
                                                        Grid::make(3)
                                                            ->schema([
                                                                Forms\Components\TextInput::make('name')
                                                                    ->required()
                                                                    ->label(__('startupful-plugin.column_name'))
                                                                    ->columnSpan(1),
                                                                    Forms\Components\TextInput::make('description')
                                                                    ->label(__('startupful-plugin.column_description'))
                                                                    ->columnSpan(2),
                                                            ]),
                                                        Grid::make(3)
                                                            ->schema([
                                                                Forms\Components\TextInput::make('width')
                                                                    ->numeric()
                                                                    ->label(__('startupful-plugin.width')),
                                                                Forms\Components\Select::make('alignment')
                                                                    ->options([
                                                                        'left' => 'Left',
                                                                        'center' => 'Center',
                                                                        'right' => 'Right',
                                                                    ])
                                                                    ->label(__('startupful-plugin.alignment')),
                                                                Toggle::make('merge_duplicates')
                                                                    ->inline(false)
                                                                    ->label(__('startupful-plugin.merge_duplicates')),
                                                            ]),
                                                    ])
                                                    ->columns(1)
                                                    ->createItemButtonLabel(__('startupful-plugin.add_column'))
                                                    ->label(__('startupful-plugin.column')),
                                                    
                                            ])
                                            ->columns(1)
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'generate_excel')
                                            ->createItemButtonLabel(__('startupful-plugin.add_sheet'))
                                            ->label(__('startupful-plugin.excel_configuration')),

                                            // Content Integration 필드 추가
                                            Forms\Components\Textarea::make('content_template')
                                            ->label(__('startupful-plugin.content_template'))
                                            ->helperText(__('startupful-plugin.content_template_help'))
                                            ->rows(10)
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'content_integration')
                                            ->columnSpanFull(),
                           
                                            


                                    ])
                                    ->columnSpanFull()
                                    ->createItemButtonLabel(__('startupful-plugin.add_step'))
                                    ->reorderable()
                                    ->itemLabel(function ($state) {
                                        $stepNumber = $state['step_number'] ?? 0;
                                        $type = isset($state['type']) ? ucfirst(str_replace('_', ' ', $state['type'])) : 'Unknown';
                                        return "Step {$stepNumber}: {$type}";
                                    })
                                    ->collapsible()
                                    ->afterStateUpdated(function (Repeater $component, $state) {
                                        if (!is_array($state)) {
                                            return;
                                        }
                                
                                        // Update step_number based on the new order
                                        $updatedState = collect($state)
                                            ->values()
                                            ->map(function ($item, $index) {
                                                $item['step_number'] = $index + 1;
                                                return $item;
                                            })
                                            ->all();
                                
                                        $component->state($updatedState);
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
                    'claude-3-5-sonnet-20240620' => 'Claude 3.5 sonnet',
                    'claude-3-opus-20240229' => 'Claude 3 opus',
                    'claude-3-sonnet-20240229' => 'Claude 3 sonnet',
                    'claude-3-haiku-20240307' => 'Claude 3 haiku',
                ];
            case 'openai':
                return [
                    'o1-preview-2024-09-12' => 'GPT-o1-preview',
                    'o1-mini-2024-09-12' => 'GPT-o1-mini',
                    'gpt-4o-2024-05-13' => 'GPT-4o(Max 4k Token)',
                    'gpt-4o-2024-08-06' => 'GPT-4o(Max 16k Token)',
                    'gpt-4o-mini-2024-07-18' => 'GPT-4o-mini',
                    'gpt-4-turbo-2024-04-09' => 'GPT-4-Turbo',
                    'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                ];
            case 'gemini':
                return [
                    'gemini-1.5-flash-latest' => 'Gemini Pro',
                ];
            default:
                return [];
        }
    }

    public static function getImageAIModelOptions(?string $provider): array
    {
        if (!$provider) {
            return [];
        }

        switch ($provider) {
            case 'huggingface':
                return [
                    'black-forest-labs/FLUX.1-dev' => 'FLUX.1-dev',
                    'black-forest-labs/FLUX.1-schnell' => 'FLUX.1-schnell',
                    'Shakker-Labs/FLUX.1-dev-LoRA-add-details' => 'FLUX-details',
                    'alvdansen/pola-photo-flux' => 'FLUX-Pola-photo',
                    'alvdansen/flux-koda' => 'FLUX-KODA-Flim',
                    'nerijs/animation2k-flux' => 'FLUX-20s Anime',
                    'Shakker-Labs/FLUX.1-dev-LoRA-Children-Simple-Sketch' => 'FLUX-Child-Sketch',
                    'renderartist/simplevectorflux' => 'FLUX-Vector',
                    'alvdansen/haunted_linework_flux' => 'FLUX-linework',
                    'UmeAiRT/FLUX.1-dev-LoRA-Modern_Pixel_art' => 'FLUX-pixelart',
                    'sWizad/pokemon-trainer-sprites-pixelart-flux' => 'FLUX-pixel(feat.pokemon style)',
                    'aleksa-codes/flux-ghibsky-illustration' => 'FLUX-Ghibsky Illustrate',
                    'Shakker-Labs/FLUX.1-dev-LoRA-Logo-Design' => 'FLUX-Logo',
                    'enhanceaiteam/Flux-Uncensored-V2' => 'FLUX-uncensored(NSFW)',
                    'stabilityai/stable-diffusion-2-1' => 'Stable Diffusion v2.1',
                    'stable-diffusion-v1-5/stable-diffusion-v1-5' => 'Stable Diffusion v1.5',
                    'stabilityai/stable-diffusion-xl-base-1.0' => 'stable-diffusion-xl-base-1.0',
                    'OnomaAIResearch/Illustrious-xl-early-release-v0' => 'Illustrious XL v0.1',
                    'glif/90s-anime-art' => '90s Anime Art',
                    'alvdansen/enna-sketch-style' => 'Sketch Anime',
                    'mgwr/Cine-Aesthetic' => 'Cine-Aesthetic',
                    'glif/how2draw' => 'Drawing',
                ];
            case 'openai':
                return [
                    'dall-e-2' => 'DALL-E 2',
                    'dall-e-3' => 'DALL-E 3',
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
            ->filters([
                Tables\Filters\SelectFilter::make('tags')
                    ->multiple()
                    ->label('Tags')
                    ->options(function () {
                        return Logic::query()
                            ->whereNotNull('tags')
                            ->pluck('tags')
                            ->flatMap(function ($tags) {
                                return is_array($tags) ? $tags : [];
                            })
                            ->unique()
                            ->sort()
                            ->mapWithKeys(function ($tag) {
                                return [$tag => $tag];
                            });
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['values'],
                            fn (Builder $query, array $values): Builder => $query->where(function($query) use ($values) {
                                foreach ($values as $value) {
                                    $query->orWhereJsonContains('tags', $value);
                                }
                            })
                        );
                    })
            ])
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filters')
                    ->slideOver()
            )
            ->actions([
            ])
            ->bulkActions([
            ])
            ->paginationPageOptions([16, 32, 48, 64])
            ->defaultPaginationPageOption(16);
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
        \Log::info('Saving method called with data:', ['data' => $data]);

        $steps = $data['steps'] ?? [];
        if (!is_array($steps)) {
            \Log::warning('Invalid steps data in saving method:', ['steps' => $steps]);
            $steps = [];
        }
    
        $filteredSteps = array_filter($steps, function ($step) {
            return is_array($step) && isset($step['type']);
        });
    
        // Pass the array and its keys to array_map
        $newSteps = array_map(function ($step, $index) {
            $newStep = [
                'type' => $step['type'],
                'step_number' => $index + 1, // Update step_number based on index
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
                case 'web_crawling':
                    $newStep['search_query'] = $step['search_query'] ?? null;
                    $newStep['num_results'] = $step['num_results'] ?? null;
                    $newStep['search_type'] = $step['search_type'] ?? null;
                    $newStep['file_type'] = $step['file_type'] ?? null;
                    $newStep['date_restrict'] = $step['date_restrict'] ?? null;
                    $newStep['site_search'] = $step['site_search'] ?? null;
                case 'scrap_webpage':
                    $newStep['url_source'] = $step['url_source'] ?? null;
                    $newStep['fixed_url'] = $step['fixed_url'] ?? null;
                    $newStep['extraction_type'] = $step['extraction_type'] ?? null;
                    $newStep['specific_data_types'] = $step['specific_data_types'] ?? null;
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
                    $newStep['reference_file'] = $step['reference_file'] ?? null;
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
                case 'content_integration':
                    $newStep['content_template'] = $step['content_template'] ?? null;
                    break;
            }

            return $newStep;
        }, array_values($filteredSteps), array_keys($filteredSteps));

        \Log::info('New steps after processing:', ['steps' => $newSteps]);
    
        // Update other fields
        $record->steps = $newSteps;
        $record->name = $data['name'];
        $record->description = $data['description'] ?? null;
        $record->tags = $data['tags'] ?? null;
    
        \Log::info('Final record state:', ['record' => $record->toArray()]);
    }
}