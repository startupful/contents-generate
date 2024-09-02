<?php

namespace Startupful\ContentsSummary\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\MarkdownEditor;
use Startupful\ContentsSummary\Models\ContentSummary;
use Startupful\ContentsSummary\Resources\ContentsSummaryResource\Pages;

class ContentsSummaryResource extends Resource
{
    protected static ?string $model = ContentSummary::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Contents Summary';

    protected static ?string $pluralModelLabel = 'Contents Summary';

    public static function getNavigationGroup(): ?string
    {
        return __('AI');  // 또는 원하는 그룹 이름
    }

    public static function getNavigationSort(): ?int
    {
        return 1;  // 네비게이션 순서
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();  // 선택사항: 배지 표시
    }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Content')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->columnSpanFull(),
                    MarkdownEditor::make('content')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
            
            // 숨겨진 필드들
            Forms\Components\Hidden::make('uuid'),
            Forms\Components\Hidden::make('type'),
            Forms\Components\Hidden::make('thumbnail'),
            Forms\Components\Hidden::make('favicon'),
            Forms\Components\Hidden::make('brand'),
            Forms\Components\Hidden::make('author'),
            Forms\Components\Hidden::make('published_date'),
            Forms\Components\Hidden::make('user_id'),
            Forms\Components\Hidden::make('original_url'),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->color(fn (string $state): string => static::getTypeColor($state))
                    ->icon(fn (string $state): string => static::getTypeIcon($state)),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('author'),
                Tables\Columns\TextColumn::make('published_date')->date(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'Article' => '아티클',
                        'Video' => '비디오',
                        'Audio' => '오디오',
                        'PPT' => 'PPT',
                        'PDF' => 'PDF',
                    ])
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListContentsSummaries::route('/'),
            'view' => Pages\ViewContentsSummary::route('/{record}'),
            'edit' => Pages\EditContentsSummary::route('/{record}/edit'),
        ];
    }

    public static function getTypeColor(string $type): string
    {
        return match ($type) {
            'Article' => 'success',
            'Video' => 'danger',
            'Audio' => 'warning',
            'PPT' => 'info',
            'PDF' => 'info',
            default => 'primary',
        };
    }

    public static function getTypeIcon(string $type): string
    {
        return match ($type) {
            'Article' => 'heroicon-o-document-text',
            'Video' => 'heroicon-o-video-camera',
            'Audio' => 'heroicon-o-speaker-wave',
            'PPT' => 'heroicon-o-document',
            'PDF' => 'heroicon-o-document',
            default => 'heroicon-o-question-mark-circle',
        };
    }
}