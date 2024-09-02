<?php

namespace Startupful\ContentsSummary\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ContentSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'title', 'content', 'type', 'thumbnail', 'favicon',
        'brand', 'author', 'published_date', 'original_url', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}