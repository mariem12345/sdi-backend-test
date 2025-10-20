<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'spotify_id',
        'name',
        'genres',
        'popularity',
        'followers',
        'image_url',
        'spotify_url',
    ];

    protected $casts = [
        'genres' => 'array',
        'popularity' => 'integer',
        'followers' => 'integer',
    ];

    /**
     * Scope a query to search artists by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Scope a query to filter artists by genre.
     */
    public function scopeByGenre($query, $genre)
    {
        return $query->whereJsonContains('genres', $genre);
    }

    /**
     * Scope a query to order by popularity.
     */
    public function scopePopular($query)
    {
        return $query->orderBy('popularity', 'desc');
    }
}
