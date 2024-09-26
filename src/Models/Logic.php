<?php

namespace Startupful\ContentsGenerate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'steps', 'status', 'tags'];

    protected $casts = [
        'tags' => 'array',
    ];


    public function getStepsAttribute($value)
    {
        $decoded = json_decode($value, true);
        return is_array($decoded) ? array_values(array_filter($decoded, 'is_array')) : [];
    }

    public function setStepsAttribute($value)
    {
        $this->attributes['steps'] = json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiContents()
    {
        return $this->hasMany(AIContent::class);
    }
}