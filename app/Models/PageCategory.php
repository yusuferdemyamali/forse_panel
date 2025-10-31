<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Kategoriye ait sayfalar
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'page_category_id');
    }

    /**
     * Sadece yayındaki sayfalar
     */
    public function publishedPages(): HasMany
    {
        return $this->pages()->where('is_published', true);
    }

    /**
     * Slug otomatik oluşturma
     */
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug) && !empty($category->name)) {
                $category->slug = \Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = \Str::slug($category->name);
            }
        });
    }
}
