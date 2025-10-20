<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'spotify_id',
        'name',
        'duration_ms',
        'explicit',
        'popularity',
        'preview_url',
        'external_url',
        'artists',
        'album'
    ];

    protected $casts = [
        'artists' => 'array',
        'album' => 'array',
        'explicit' => 'boolean',
        'duration_ms' => 'integer',
        'popularity' => 'integer',
    ];
}
