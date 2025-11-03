<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = [
        'source_url',
        'destination_url',
        'status_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'status_code' => 'integer',
    ];

    /**
     * Slug otomatik oluşturma ve temizleme
     */
    protected static function booted()
    {
        static::saving(function ($redirect) {
            // Source URL temizleme
            $redirect->source_url = '/' . ltrim($redirect->source_url, '/');
            
            // Destination URL temizleme (harici URL değilse)
            if (!filter_var($redirect->destination_url, FILTER_VALIDATE_URL)) {
                $redirect->destination_url = '/' . ltrim($redirect->destination_url, '/');
            }
        });
    }
}
