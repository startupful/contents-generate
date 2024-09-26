<?php

namespace Startupful\ContentsGenerate\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\MarkdownEditor;
use Startupful\ContentsGenerate\Models\ContentGenerate;
use Startupful\ContentsGenerate\Resources\ContentsGenerateResource\Pages;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ContentsGenerateResource extends Resource
{
    protected static ?string $model = ContentGenerate::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function getNavigationLabel(): string
    {
        return __('startupful-plugin.contents_archive');
    }

    public static function getPluralModelLabel(): string
    {
        return __('startupful-plugin.contents_archive');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('AI');
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                
                Forms\Components\Hidden::make('uuid'),
                Forms\Components\Hidden::make('type'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => static::getTypeColor($state))
                    ->icon(fn (string $state): string => static::getTypeIcon($state)),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'image' => 'Image',
                        'code' => 'Code',
                        'audio' => 'Audio',
                        'excel' => 'Excel',
                        'integration' => 'Integration',
                    ])
                    ->label('Content Type')
                    ->multiple()
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            $records->each(function (ContentGenerate $record) {
                                self::deleteRelatedFiles($record);
                            });
                        }),
                ]),
            ])
            ->headerActions([
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
            'index' => Pages\ListContentsGenerates::route('/'),
            'setting' => Pages\SettingContentsGenerate::route('/setting'),
            'view' => Pages\ViewContentsGenerate::route('/{record}'),
            'edit' => Pages\EditContentsGenerate::route('/{record}/edit'),
        ];
    }
    
    public static function getTypeColor(string $state): string
    {
        return match ($state) {
            'text' => 'info',
            'image' => 'success',
            'code' => 'warning',
            'audio' => 'danger',
            'excel' => 'primary',
            'integration' => '',
            default => 'gray',
        };
    }

    public static function getTypeIcon(string $state): string
    {
        return match ($state) {
            'text' => 'heroicon-o-document-text',
            'image' => 'heroicon-o-photo',
            'code' => 'heroicon-o-code-bracket',
            'audio' => 'heroicon-o-musical-note',
            'excel' => 'heroicon-o-table-cells',
            'integration' => 'heroicon-o-squares-plus',
            default => 'heroicon-o-question-mark-circle',
        };
    }

    protected static function deleteRelatedFiles(ContentGenerate $record): void
    {
        Log::info('Attempting to delete related files for record: ' . $record->id);

        if ($record->file_path) {
            Log::info('File path from record: ' . $record->file_path);
            $relativeFilePath = self::getRelativeFilePath($record->file_path);
            Log::info('Relative file path: ' . $relativeFilePath);

            if (Storage::exists($relativeFilePath)) {
                Log::info('File exists, attempting to delete: ' . $relativeFilePath);
                $result = Storage::delete($relativeFilePath);
                Log::info('Delete result: ' . ($result ? 'success' : 'failure'));
            } else {
                Log::warning('File does not exist: ' . $relativeFilePath);
            }
        } else {
            Log::info('No file path for record: ' . $record->id);
        }
    }

    protected static function getRelativeFilePath(string $fullPath): string
    {
        $storagePath = storage_path('app/public/');
        Log::info('Storage path: ' . $storagePath);
        Log::info('Full path: ' . $fullPath);

        if (strpos($fullPath, $storagePath) === 0) {
            $relativePath = 'public/' . substr($fullPath, strlen($storagePath));
            Log::info('Relative path: ' . $relativePath);
            return $relativePath;
        }

        Log::info('Path not modified: ' . $fullPath);
        return $fullPath;
    }
}