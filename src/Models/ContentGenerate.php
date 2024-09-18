<?php

namespace Startupful\ContentsGenerate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Illuminate\Support\HtmlString;

class ContentGenerate extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'title', 'content', 'type',
        'published_date',
        'status','audio_content',
        'audio_text',
        'audio_model',
        'audio_voice',
        'file_path', 'file_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCodeContent()
    {
        if ($this->type !== 'code' || empty($this->content)) {
            return null;
        }

        return new HtmlString(e($this->content));
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($contentGenerate) {
            Log::info('Deleting ContentGenerate record: ' . $contentGenerate->id);

            if ($contentGenerate->file_path) {
                Log::info('File path from record: ' . $contentGenerate->file_path);
                $relativeFilePath = self::getRelativeFilePath($contentGenerate->file_path);
                Log::info('Relative file path: ' . $relativeFilePath);

                if (Storage::exists($relativeFilePath)) {
                    Log::info('File exists, attempting to delete: ' . $relativeFilePath);
                    $result = Storage::delete($relativeFilePath);
                    Log::info('Delete result: ' . ($result ? 'success' : 'failure'));
                } else {
                    Log::warning('File does not exist: ' . $relativeFilePath);
                }
            } else {
                Log::info('No file path for record: ' . $contentGenerate->id);
            }
        });
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