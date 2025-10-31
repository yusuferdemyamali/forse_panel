<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'page_category_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'is_published',
        'order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Sayfanın kategorisi
     */
    public function pageCategory(): BelongsTo
    {
        return $this->belongsTo(PageCategory::class);
    }

    /**
     * Slug otomatik oluşturma
     */
    protected static function booted()
    {
        static::creating(function ($page) {
            if (empty($page->slug) && !empty($page->title)) {
                $page->slug = \Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->slug)) {
                $page->slug = \Str::slug($page->title);
            }
        });
    }
}
