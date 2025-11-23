<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Blog extends Model implements HasMedia
{
    use HasFactory;
    use HasTags;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'author',
        'thumbnail',
        'slug',
        'is_active',
        'order',
        'published_at',
        'blog_category_id',
        // SEO Fields
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keyword',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'index_page',
        'follow_links',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'order' => 'integer',
        'index_page' => 'boolean',
        'follow_links' => 'boolean',
    ];

    // Eager loading - performance için
    protected $with = ['category'];

    /**
     * Blog kategorisi
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Scope: Aktif bloglar
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Yayınlanan bloglar
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope: Sıraya göre
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Son bloglar
     */
    public function scopeLatest(Builder $query, int $limit = 10): Builder
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Cache'li aktif blogları getir
     */
    public static function getCachedActiveBlogs(int $limit = 10)
    {
        $key = CacheService::generateKey('active_blogs', $limit);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::with('category')
                    ->published()
                    ->ordered()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li blog kategorisine göre getir
     */
    public static function getCachedBlogsByCategory(int $categoryId, int $limit = 10)
    {
        $key = CacheService::generateKey('blogs_by_category', $categoryId, $limit);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($categoryId, $limit) {
                return static::with('category')
                    ->where('blog_category_id', $categoryId)
                    ->published()
                    ->ordered()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li tek blog getir
     */
    public static function getCachedBlogBySlug(string $slug)
    {
        $key = CacheService::generateKey('blog_by_slug', $slug);

        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($slug) {
                return static::with(['category', 'tags'])
                    ->where('slug', $slug)
                    ->published()
                    ->first();
            }
        );
    }

    /**
     * Cache'li benzer bloglar getir
     */
    public function getCachedRelatedBlogs(int $limit = 5)
    {
        $key = CacheService::generateKey('related_blogs', $this->id, $this->blog_category_id, $limit);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::with('category')
                    ->where('blog_category_id', $this->blog_category_id)
                    ->where('id', '!=', $this->id)
                    ->published()
                    ->ordered()
                    ->limit($limit)
                    ->get();
            }
        );
    }
    
    /**
     * SEO: Meta title'ı getir (yoksa başlığı kullan)
     */
    public function getMetaTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }
    
    /**
     * SEO: Meta description'ı getir (yoksa excerpt kullan)
     */
    public function getMetaDescription(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }
        
        if ($this->excerpt) {
            return \Str::limit($this->excerpt, 160);
        }
        
        return \Str::limit(strip_tags($this->content), 160);
    }
    
    /**
     * SEO: OG Title'ı getir (yoksa meta_title veya title kullan)
     */
    public function getOgTitle(): string
    {
        return $this->og_title ?: $this->getMetaTitle();
    }
    
    /**
     * SEO: OG Description'ı getir
     */
    public function getOgDescription(): string
    {
        return $this->og_description ?: $this->getMetaDescription();
    }
    
    /**
     * SEO: OG Image'ı getir
     */
    public function getOgImage(): ?string
    {
        if ($this->og_image) {
            return \Storage::url($this->og_image);
        }
        
        if ($this->thumbnail) {
            return \Storage::url($this->thumbnail);
        }
        
        $media = $this->getFirstMediaUrl('blog');
        if ($media) {
            return $media;
        }
        
        return null;
    }
    
    /**
     * SEO: Canonical URL'i getir
     */
    public function getCanonicalUrl(): string
    {
        return $this->canonical_url ?: route('blog.show', $this->slug);
    }
    
    /**
     * SEO: Robots meta tag'i getir
     */
    public function getRobotsTag(): string
    {
        $index = $this->index_page ? 'index' : 'noindex';
        $follow = $this->follow_links ? 'follow' : 'nofollow';
        
        return "{$index}, {$follow}";
    }
    
    /**
     * SEO: Structured Data (JSON-LD) oluştur
     */
    public function getStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $this->title,
            'description' => $this->getMetaDescription(),
            'image' => $this->getOgImage(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->author ?: 'Ares Asansör',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Ares Asansör',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => config('app.url') . '/images/logo.png',
                ],
            ],
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified' => $this->updated_at->toIso8601String(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->getCanonicalUrl(),
            ],
        ];
    }
}
