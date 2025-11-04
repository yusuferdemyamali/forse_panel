# Filament Admin Panel - Blade Kullanım Dokümantasyonu

Bu dokümantasyon, Filament Admin Paneli'nde yönetilen verilerin web sitesinin frontend'inde (Blade şablonlarında) nasıl kullanılacağını açıklar.

---

## 📋 İçindekiler

1. [Genel Bakış](#genel-bakış)
2. [Best Practices](#best-practices)
3. [Blog Modülü](#blog-modülü)
4. [Sayfalar Modülü (Pages)](#sayfalar-modülü-pages)
5. [Ürünler Modülü](#ürünler-modülü)
6. [Hizmetler Modülü](#hizmetler-modülü)
7. [Referanslar Modülü](#referanslar-modülü)
8. [Galeri Modülü](#galeri-modülü)
9. [Ekip Modülü](#ekip-modülü)
10. [SSS Modülü](#sss-modülü)
11. [Hakkımızda Modülü](#hakkımızda-modülü)
12. [İletişim Modülü](#iletişim-modülü)
13. [Site Ayarları](#site-ayarları)
14. [SEO Ayarları](#seo-ayarları)
15. [Yönlendirmeler (Redirects)](#yönlendirmeler-redirects)

---

## Genel Bakış

Bu proje, **modüler bir yapıya** sahiptir. Her modül `.env` dosyasından açılıp kapatılabilir. Frontend'de veri kullanırken, modülün aktif olup olmadığını kontrol etmeniz önerilir.

### Modül Durumu Kontrolü

```php
@if(config('modules.blog'))
    {{-- Blog verilerini kullan --}}
@endif
```

---

## Best Practices

### 1. **Eager Loading Kullanın**

N+1 sorgu problemini önlemek için ilişkileri önceden yükleyin:

```php
// ❌ Kötü (N+1 Problem)
$blogs = Blog::all();
foreach ($blogs as $blog) {
    echo $blog->category->name; // Her döngüde sorgu çalıştırır
}

// ✅ İyi
$blogs = Blog::with('category', 'tags')
    ->where('is_published', true)
    ->get();
```

### 2. **Chunk Kullanın (Büyük Veri Setleri)**

Bellek kullanımını optimize etmek için:

```php
Blog::where('is_published', true)
    ->chunk(100, function ($blogs) {
        foreach ($blogs as $blog) {
            // İşlemler
        }
    });
```

### 3. **Cache Kullanın**

Sık erişilen veriler için:

```php
$categories = Cache::remember('blog_categories', 60*24, function () {
    return BlogCategory::with('blogs')->get();
});
```

### 4. **Scope Kullanın**

Model'de tekrar eden sorguları scope olarak tanımlayın:

```php
// Model'de
public function scopePublished($query)
{
    return $query->where('is_published', true)
                 ->where('published_at', '<=', now());
}

// Blade'de
$blogs = Blog::published()->latest()->get();
```

### 5. **Soft Deletes Kontrolü**

Silinmiş kayıtları görüntülemek istemiyorsanız:

```php
$blogs = Blog::published()->get(); // Silinmişleri göstermez
```

---

## Blog Modülü

**Resource:** `BlogResource`  
**Model:** `Blog`  
**Config:** `modules.blog`

### Tüm Blog Yazılarını Listeleme

```php
// Controller veya Route
use App\Models\Blog;

$blogs = Blog::with(['category', 'tags', 'author'])
    ->where('is_published', true)
    ->where('published_at', '<=', now())
    ->latest('published_at')
    ->paginate(12);

return view('blog.index', compact('blogs'));
```

#### Blade Şablonu

```blade
{{-- resources/views/blog/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">Blog Yazıları</h1>

    @if(config('modules.blog') && $blogs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($blogs as $blog)
                <article class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($blog->featured_image)
                        <img src="{{ Storage::url($blog->featured_image) }}" 
                             alt="{{ $blog->title }}" 
                             class="w-full h-48 object-cover">
                    @endif

                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-2">
                            <a href="{{ route('blog.show', $blog->slug) }}" 
                               class="hover:text-blue-600">
                                {{ $blog->title }}
                            </a>
                        </h2>

                        <p class="text-gray-600 mb-4">{{ $blog->excerpt }}</p>

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>{{ $blog->published_at->format('d F Y') }}</span>
                            @if($blog->category)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    {{ $blog->category->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{ $blogs->links() }}
    @else
        <p class="text-gray-500">Henüz blog yazısı eklenmemiş.</p>
    @endif
</div>
@endsection
```

### Tek Bir Blog Yazısını Görüntüleme

```php
// Route
Route::get('/blog/{slug}', function ($slug) {
    $blog = Blog::with(['category', 'tags', 'author'])
        ->where('slug', $slug)
        ->where('is_published', true)
        ->firstOrFail();

    // Görüntülenme sayısını artır
    $blog->increment('view_count');

    return view('blog.show', compact('blog'));
})->name('blog.show');
```

#### Blade Şablonu

```blade
{{-- resources/views/blog/show.blade.php --}}
@extends('layouts.app')

@section('title', $blog->title)
@section('description', $blog->excerpt)

@section('content')
<article class="container mx-auto px-4 py-8 max-w-4xl">
    <header class="mb-8">
        <h1 class="text-5xl font-bold mb-4">{{ $blog->title }}</h1>

        <div class="flex items-center text-gray-600 mb-4">
            <span>{{ $blog->published_at->format('d F Y') }}</span>
            <span class="mx-2">•</span>
            <span>{{ $blog->view_count }} görüntülenme</span>
            @if($blog->category)
                <span class="mx-2">•</span>
                <a href="{{ route('blog.category', $blog->category->slug) }}" 
                   class="text-blue-600 hover:underline">
                    {{ $blog->category->name }}
                </a>
            @endif
        </div>

        @if($blog->featured_image)
            <img src="{{ Storage::url($blog->featured_image) }}" 
                 alt="{{ $blog->title }}" 
                 class="w-full h-96 object-cover rounded-lg">
        @endif
    </header>

    <div class="prose prose-lg max-w-none">
        {!! $blog->content !!}
    </div>

    @if($blog->tags->count() > 0)
        <div class="mt-8 flex flex-wrap gap-2">
            @foreach($blog->tags as $tag)
                <a href="{{ route('blog.tag', $tag->slug) }}" 
                   class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-full text-sm">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    @endif
</article>
@endsection
```

### Blog Kategorilerine Göre Filtreleme

```php
// Route
Route::get('/blog/kategori/{slug}', function ($slug) {
    $category = BlogCategory::where('slug', $slug)->firstOrFail();

    $blogs = $category->blogs()
        ->with(['tags', 'author'])
        ->where('is_published', true)
        ->latest('published_at')
        ->paginate(12);

    return view('blog.category', compact('category', 'blogs'));
})->name('blog.category');
```

---

## Sayfalar Modülü (Pages)

**Resource:** `PageResource`, `PageCategoryResource`  
**Model:** `Page`, `PageCategory`  
**Config:** `modules.pages`

### Navbar için Kategori ve Sayfaları Paylaşma

Navbar'da kategoriler ve sayfaları göstermek için `AppServiceProvider`'da view sharing kullanılmıştır:

```php
// app/Providers/AppServiceProvider.php
if (config('modules.pages')) {
    ViewFacade::share('navbarCategories', PageCategory::with([
        'pages' => function ($query) {
            $query->where('is_published', true)
                  ->orderBy('order', 'asc');
        }
    ])->orderBy('order', 'asc')->get());
}
```

#### Navbar Blade Şablonu

```blade
{{-- resources/views/layouts/navbar.blade.php --}}
<nav class="bg-white shadow">
    <div class="container mx-auto px-4">
        <ul class="flex space-x-6">
            <li><a href="/" class="py-4 inline-block hover:text-blue-600">Anasayfa</a></li>

            @if(config('modules.pages') && isset($navbarCategories))
                @foreach($navbarCategories as $category)
                    @if($category->pages->count() > 0)
                        <li class="relative group">
                            <a href="#" class="py-4 inline-block hover:text-blue-600">
                                {{ $category->name }}
                            </a>

                            {{-- Dropdown --}}
                            <ul class="absolute hidden group-hover:block bg-white shadow-lg py-2 w-48">
                                @foreach($category->pages as $page)
                                    <li>
                                        <a href="{{ route('page.show', $page->slug) }}" 
                                           class="block px-4 py-2 hover:bg-gray-100">
                                            {{ $page->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            @endif

            <li><a href="/iletisim" class="py-4 inline-block hover:text-blue-600">İletişim</a></li>
        </ul>
    </div>
</nav>
```

### Sayfa Görüntüleme

```php
// Route
Route::get('/sayfa/{slug}', function ($slug) {
    $page = Page::where('slug', $slug)
        ->where('is_published', true)
        ->firstOrFail();

    return view('page.show', compact('page'));
})->name('page.show');
```

#### Blade Şablonu

```blade
{{-- resources/views/page/show.blade.php --}}
@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    @if($page->featured_image)
        <img src="{{ Storage::url($page->featured_image) }}" 
             alt="{{ $page->title }}" 
             class="w-full h-96 object-cover rounded-lg mb-8">
    @endif

    <h1 class="text-4xl font-bold mb-6">{{ $page->title }}</h1>

    <div class="prose prose-lg max-w-none">
        {!! $page->content !!}
    </div>
</div>
@endsection
```

---

## Ürünler Modülü

**Resource:** `ProductResource`, `ProductCategoryResource`  
**Model:** `Product`, `ProductCategory`  
**Config:** `modules.products`

### Ürün Listesi

```php
// Controller
use App\Models\Product;

public function index()
{
    $products = Product::with(['category', 'media'])
        ->where('is_active', true)
        ->orderBy('order', 'asc')
        ->paginate(12);

    return view('products.index', compact('products'));
}
```

#### Blade Şablonu

```blade
{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">Ürünlerimiz</h1>

    @if(config('modules.products') && $products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($product->getFirstMediaUrl('products'))
                        <img src="{{ $product->getFirstMediaUrl('products', 'thumb') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-64 object-cover">
                    @endif

                    <div class="p-4">
                        <h2 class="text-xl font-bold mb-2">{{ $product->name }}</h2>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>

                        @if($product->price)
                            <p class="text-2xl font-bold text-blue-600 mb-4">
                                {{ number_format($product->price, 2) }} ₺
                            </p>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="block text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                            Detayları Gör
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $products->links() }}
    @else
        <p class="text-gray-500">Henüz ürün eklenmemiş.</p>
    @endif
</div>
@endsection
```

### Tek Ürün Detayı

```php
// Route
Route::get('/urunler/{slug}', function ($slug) {
    $product = Product::with(['category', 'media'])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    return view('products.show', compact('product'));
})->name('products.show');
```

#### Blade Şablonu

```blade
{{-- resources/views/products/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            @if($product->getFirstMediaUrl('products'))
                <img src="{{ $product->getFirstMediaUrl('products') }}" 
                     alt="{{ $product->name }}" 
                     class="w-full rounded-lg">
            @endif
        </div>

        <div>
            <h1 class="text-4xl font-bold mb-4">{{ $product->name }}</h1>

            @if($product->category)
                <p class="text-gray-600 mb-4">
                    Kategori: <span class="font-semibold">{{ $product->category->name }}</span>
                </p>
            @endif

            @if($product->price)
                <p class="text-3xl font-bold text-blue-600 mb-6">
                    {{ number_format($product->price, 2) }} ₺
                </p>
            @endif

            <div class="prose max-w-none mb-6">
                {!! $product->description !!}
            </div>

            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                Sepete Ekle
            </button>
        </div>
    </div>
</div>
@endsection
```

---

## Hizmetler Modülü

**Resource:** `ServiceResource`  
**Model:** `Service`, `ServiceCategory`  
**Config:** `modules.services`

### Hizmet Listesi

```php
// Controller
use App\Models\Service;

public function index()
{
    $services = Service::with(['category'])
        ->where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();

    return view('services.index', compact('services'));
}
```

#### Blade Şablonu

```blade
{{-- resources/views/services/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-center">Hizmetlerimiz</h1>

    @if(config('modules.services') && $services->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($services as $service)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
                    @if($service->icon)
                        <div class="text-4xl mb-4">{{ $service->icon }}</div>
                    @endif

                    <h2 class="text-2xl font-bold mb-3">{{ $service->name }}</h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 150) }}</p>

                    @if($service->price)
                        <p class="text-xl font-bold text-blue-600 mb-4">
                            {{ number_format($service->price, 2) }} ₺
                        </p>
                    @endif

                    <a href="{{ route('services.show', $service->slug) }}" 
                       class="text-blue-600 hover:underline font-semibold">
                        Detaylı Bilgi →
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center">Henüz hizmet eklenmemiş.</p>
    @endif
</div>
@endsection
```

---

## Referanslar Modülü

**Resource:** `ReferenceResource`  
**Model:** `Reference`  
**Config:** `modules.references`

### Referans Listesi (Slider)

```php
// Controller
use App\Models\Reference;

$references = Reference::where('is_active', true)
    ->orderBy('order', 'asc')
    ->get();

return view('home', compact('references'));
```

#### Blade Şablonu (Swiper ile Slider)

```blade
{{-- resources/views/sections/references.blade.php --}}
@if(config('modules.references') && $references->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12">Referanslarımız</h2>

        <div class="swiper references-swiper">
            <div class="swiper-wrapper">
                @foreach($references as $reference)
                    <div class="swiper-slide">
                        <div class="bg-white rounded-lg shadow-md p-8 text-center">
                            @if($reference->photo)
                                <img src="{{ Storage::url($reference->photo) }}" 
                                     alt="{{ $reference->client_name }}" 
                                     class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                            @endif

                            <p class="text-gray-700 italic mb-4">"{{ $reference->testimonial }}"</p>

                            <h3 class="font-bold text-lg">{{ $reference->client_name }}</h3>
                            @if($reference->company)
                                <p class="text-gray-600">{{ $reference->company }}</p>
                            @endif
                            @if($reference->position)
                                <p class="text-gray-500 text-sm">{{ $reference->position }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>

<script>
new Swiper('.references-swiper', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        768: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 3,
        },
    },
});
</script>
@endif
```

---

## Galeri Modülü

**Resource:** `GalleryResource`  
**Model:** `Gallery`  
**Config:** `modules.gallery`

### Galeri Görüntüleme (Lightbox ile)

```php
// Controller
use App\Models\Gallery;

$galleries = Gallery::with('media')
    ->where('is_active', true)
    ->orderBy('order', 'asc')
    ->get();

return view('gallery.index', compact('galleries'));
```

#### Blade Şablonu

```blade
{{-- resources/views/gallery/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-center">Galeri</h1>

    @if(config('modules.gallery') && $galleries->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($galleries as $gallery)
                @foreach($gallery->getMedia('gallery') as $media)
                    <a href="{{ $media->getUrl() }}" 
                       data-lightbox="gallery" 
                       data-title="{{ $gallery->title }}">
                        <img src="{{ $media->getUrl('thumb') }}" 
                             alt="{{ $gallery->title }}" 
                             class="w-full h-64 object-cover rounded-lg hover:opacity-75 transition">
                    </a>
                @endforeach
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center">Henüz görsel eklenmemiş.</p>
    @endif
</div>

{{-- Lightbox2 CDN --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endsection
```

---

## Ekip Modülü

**Resource:** `TeamResource`  
**Model:** `Team`  
**Config:** `modules.team`

### Ekip Üyeleri Listesi

```php
// Controller
use App\Models\Team;

$team = Team::where('is_active', true)
    ->orderBy('order', 'asc')
    ->get();

return view('about.team', compact('team'));
```

#### Blade Şablonu

```blade
{{-- resources/views/about/team.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-center">Ekibimiz</h1>

    @if(config('modules.team') && $team->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($team as $member)
                <div class="text-center">
                    @if($member->photo)
                        <img src="{{ Storage::url($member->photo) }}" 
                             alt="{{ $member->name }}" 
                             class="w-48 h-48 rounded-full mx-auto mb-4 object-cover">
                    @endif

                    <h3 class="text-xl font-bold">{{ $member->name }}</h3>
                    <p class="text-gray-600 mb-2">{{ $member->position }}</p>

                    @if($member->bio)
                        <p class="text-gray-500 text-sm mb-4">{{ Str::limit($member->bio, 100) }}</p>
                    @endif

                    {{-- Sosyal Medya Linkleri --}}
                    <div class="flex justify-center space-x-3">
                        @if($member->linkedin_url)
                            <a href="{{ $member->linkedin_url }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        @endif

                        @if($member->twitter_url)
                            <a href="{{ $member->twitter_url }}" target="_blank" 
                               class="text-blue-400 hover:text-blue-600">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center">Henüz ekip üyesi eklenmemiş.</p>
    @endif
</div>
@endsection
```

---

## SSS Modülü

**Resource:** `FaqResource`  
**Model:** `Faq`  
**Config:** `modules.faq`

### SSS Listesi (Accordion)

```php
// Controller
use App\Models\Faq;

$faqs = Faq::where('is_active', true)
    ->orderBy('order', 'asc')
    ->get();

return view('faq', compact('faqs'));
```

#### Blade Şablonu (Alpine.js ile Accordion)

```blade
{{-- resources/views/faq.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-4xl font-bold mb-8 text-center">Sıkça Sorulan Sorular</h1>

    @if(config('modules.faq') && $faqs->count() > 0)
        <div class="space-y-4" x-data="{ activeIndex: null }">
            @foreach($faqs as $index => $faq)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button @click="activeIndex = activeIndex === {{ $index }} ? null : {{ $index }}"
                            class="w-full text-left p-4 bg-gray-50 hover:bg-gray-100 flex justify-between items-center">
                        <span class="font-semibold">{{ $faq->question }}</span>
                        <svg class="w-5 h-5 transition-transform" 
                             :class="{ 'rotate-180': activeIndex === {{ $index }} }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="activeIndex === {{ $index }}" 
                         x-collapse
                         class="p-4 bg-white">
                        <p class="text-gray-700">{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center">Henüz soru eklenmemiş.</p>
    @endif
</div>

{{-- Alpine.js CDN --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
@endsection
```

---

## Hakkımızda Modülü

**Resource:** `AboutResource`  
**Model:** `About`  
**Config:** `modules.about`

### Hakkımızda Sayfası

```php
// Route
Route::get('/hakkimizda', function () {
    $about = About::orderBy('order', 'asc')->first();

    return view('about.index', compact('about'));
})->name('about');
```

#### Blade Şablonu

```blade
{{-- resources/views/about/index.blade.php --}}
@extends('layouts.app')

@section('content')
@if(config('modules.about') && $about)
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold mb-6 text-center">{{ $about->title }}</h1>

        @if($about->image)
            <img src="{{ Storage::url($about->image) }}" 
                 alt="{{ $about->title }}" 
                 class="w-full h-96 object-cover rounded-lg mb-8">
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $about->content !!}
        </div>
    </div>
</div>
@else
    <div class="container mx-auto px-4 py-8 text-center">
        <p class="text-gray-500">Hakkımızda bilgisi henüz eklenmemiş.</p>
    </div>
@endif
@endsection
```

---

## İletişim Modülü

**Resource:** `ContactMessageResource`  
**Model:** `ContactMessage`  
**Config:** `modules.contact`

### İletişim Formu

```blade
{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-center">İletişim</h1>

    <div class="max-w-2xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(config('modules.contact'))
            <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-gray-700 font-semibold mb-2">
                        Adınız *
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required
                           value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-gray-700 font-semibold mb-2">
                        E-posta *
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           required
                           value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-gray-700 font-semibold mb-2">
                        Telefon
                    </label>
                    <input type="tel" 
                           name="phone" 
                           id="phone"
                           value="{{ old('phone') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject" class="block text-gray-700 font-semibold mb-2">
                        Konu *
                    </label>
                    <input type="text" 
                           name="subject" 
                           id="subject" 
                           required
                           value="{{ old('subject') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('subject')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-gray-700 font-semibold mb-2">
                        Mesajınız *
                    </label>
                    <textarea name="message" 
                              id="message" 
                              rows="6" 
                              required
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Gönder
                </button>
            </form>
        @else
            <p class="text-gray-500 text-center">İletişim formu şu anda kullanılamıyor.</p>
        @endif
    </div>
</div>
@endsection
```

#### Controller (Form İşleme)

```php
// app/Http/Controllers/ContactController.php
<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Modül kontrolü
        if (!config('modules.contact')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.');
    }
}
```

---

## Site Ayarları

**Resource:** `SiteSettingResource`  
**Model:** `SiteSettings` (Spatie Settings)

Site ayarları, Spatie Settings paketi kullanılarak yönetilir. Bu ayarlar tüm blade dosyalarında kullanılabilir.

### Kullanım

```php
// Controller veya Blade'de
$settings = app(\App\Settings\SiteSettings::class);

echo $settings->site_name;
echo $settings->site_logo;
echo $settings->site_favicon;
```

#### Layout Şablonunda

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        $settings = app(\App\Settings\SiteSettings::class);
    @endphp
    
    <title>@yield('title', $settings->site_name)</title>
    
    @if($settings->site_favicon)
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($settings->site_favicon) }}">
    @endif
    
    {{-- Bakım Modu Kontrolü --}}
    @if($settings->is_maintenance && !auth()->check())
        <meta http-equiv="refresh" content="0;url={{ route('maintenance') }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <a href="/">
            @if($settings->site_logo)
                <img src="{{ Storage::url($settings->site_logo) }}" alt="{{ $settings->site_name }}">
            @else
                <h1>{{ $settings->site_name }}</h1>
            @endif
        </a>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        {{-- Footer içeriği --}}
    </footer>
</body>
</html>
```

### Şirket Bilgileri

```blade
{{-- Footer'da şirket bilgilerini gösterme --}}
@php
    $company = app(\App\Settings\CompanySettings::class);
@endphp

<footer class="bg-gray-800 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">İletişim</h3>
                <p>{{ $company->address }}</p>
                <p>Tel: {{ $company->phone }}</p>
                <p>E-posta: {{ $company->email }}</p>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Sosyal Medya</h3>
                <div class="flex space-x-4">
                    @if($company->facebook_url)
                        <a href="{{ $company->facebook_url }}" target="_blank">Facebook</a>
                    @endif
                    @if($company->instagram_url)
                        <a href="{{ $company->instagram_url }}" target="_blank">Instagram</a>
                    @endif
                    @if($company->linkedin_url)
                        <a href="{{ $company->linkedin_url }}" target="_blank">LinkedIn</a>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Çalışma Saatleri</h3>
                <p>{{ $company->working_hours }}</p>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center">
            <p>&copy; {{ date('Y') }} {{ $company->name }}. Tüm hakları saklıdır.</p>
        </div>
    </div>
</footer>
```

---

## SEO Ayarları

**Model:** `SeoSettings` (Spatie Settings)  
**Page:** `ManageSeoSettings`

### Meta Tags Ekleme

```blade
{{-- resources/views/layouts/app.blade.php --}}
@php
    $seo = app(\App\Settings\SeoSettings::class);
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Sayfa Başlığı --}}
    <title>@yield('title', $seo->site_name)</title>
    
    {{-- Meta Açıklama --}}
    <meta name="description" content="@yield('description', $seo->site_description)">
    
    {{-- Open Graph Tags --}}
    <meta property="og:title" content="@yield('title', $seo->site_name)">
    <meta property="og:description" content="@yield('description', $seo->site_description)">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    @if($seo->og_image)
        <meta property="og:image" content="{{ Storage::url($seo->og_image) }}">
    @endif
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $seo->site_name)">
    <meta name="twitter:description" content="@yield('description', $seo->site_description)">
    
    {{-- Google Analytics --}}
    @if($seo->google_analytics_id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $seo->google_analytics_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $seo->google_analytics_id }}');
        </script>
    @endif
    
    {{-- Head Scripts --}}
    @if($seo->head_scripts)
        {!! $seo->head_scripts !!}
    @endif
</head>
<body>
    @yield('content')
    
    {{-- Body Scripts --}}
    @if($seo->body_scripts)
        {!! $seo->body_scripts !!}
    @endif
</body>
```

### Dinamik SEO (Sayfa Bazlı)

```blade
{{-- Blog detay sayfasında --}}
@extends('layouts.app')

@section('title', $blog->title . ' - ' . config('app.name'))
@section('description', $blog->excerpt)

@section('content')
    {{-- İçerik --}}
@endsection
```

---

## Yönlendirmeler (Redirects)

**Resource:** `RedirectResource`  
**Model:** `Redirect`

Yönlendirmeler, middleware üzerinden otomatik olarak işlenir. Frontend'de manuel kullanıma gerek yoktur.

### Middleware (Otomatik Yönlendirme)

```php
// app/Http/Middleware/RedirectMiddleware.php
<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();

        // Cache'den yönlendirmeleri al (performans için)
        $redirects = Cache::remember('active_redirects', 60*24, function () {
            return Redirect::where('is_active', true)->get();
        });

        foreach ($redirects as $redirect) {
            if ($redirect->source_url === '/' . $path) {
                return redirect($redirect->destination_url, $redirect->status_code);
            }
        }

        return $next($request);
    }
}
```

Middleware'i `app/Http/Kernel.php` dosyasına ekleyin:

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\RedirectMiddleware::class,
    ],
];
```

---

## Ekstra: Veri Çekme Komutları (Tüm Modüller)

### Anasayfa için Tüm Verileri Çekme

```php
// app/Http/Controllers/HomeController.php
<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use App\Models\Service;
use App\Models\Reference;
use App\Models\Gallery;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];

        // Blog Modülü
        if (config('modules.blog')) {
            $data['latestBlogs'] = Cache::remember('home_latest_blogs', 60, function () {
                return Blog::with(['category'])
                    ->where('is_published', true)
                    ->latest('published_at')
                    ->take(3)
                    ->get();
            });
        }

        // Ürünler Modülü
        if (config('modules.products')) {
            $data['featuredProducts'] = Cache::remember('home_featured_products', 60, function () {
                return Product::with(['category'])
                    ->where('is_active', true)
                    ->where('is_featured', true)
                    ->take(4)
                    ->get();
            });
        }

        // Hizmetler Modülü
        if (config('modules.services')) {
            $data['services'] = Cache::remember('home_services', 60, function () {
                return Service::where('is_active', true)
                    ->orderBy('order', 'asc')
                    ->take(6)
                    ->get();
            });
        }

        // Referanslar Modülü
        if (config('modules.references')) {
            $data['references'] = Cache::remember('home_references', 60, function () {
                return Reference::where('is_active', true)
                    ->orderBy('order', 'asc')
                    ->get();
            });
        }

        // Galeri Modülü
        if (config('modules.gallery')) {
            $data['galleryImages'] = Cache::remember('home_gallery', 60, function () {
                return Gallery::with('media')
                    ->where('is_active', true)
                    ->take(6)
                    ->get();
            });
        }

        return view('home', $data);
    }
}
```

---

## Performans İpuçları

### 1. Query Optimization

```php
// ❌ Kötü (N+1 Problem)
$blogs = Blog::all();
foreach ($blogs as $blog) {
    echo $blog->category->name;
}

// ✅ İyi
$blogs = Blog::with('category')->get();
```

### 2. Cache Kullanımı

```php
// Sidebar için kategorileri cache'le
$categories = Cache::remember('blog_categories_sidebar', 60*24, function () {
    return BlogCategory::withCount('blogs')->get();
});
```

### 3. Pagination

```php
// Büyük veri setleri için mutlaka pagination kullanın
$blogs = Blog::paginate(12);
```

### 4. Image Optimization

```php
// Spatie Media Library thumbnail'lerini kullanın
$product->getFirstMediaUrl('products', 'thumb'); // 300x300
$product->getFirstMediaUrl('products', 'medium'); // 800x600
```

---

## Güvenlik İpuçları

### 1. XSS Koruması

```blade
{{-- Otomatik HTML encode eder --}}
{{ $blog->title }}

{{-- Raw HTML (sadece güvenilir içerik için) --}}
{!! $blog->content !!}
```

### 2. CSRF Koruması

```blade
<form method="POST">
    @csrf
    {{-- Form alanları --}}
</form>
```

### 3. SQL Injection Koruması

```php
// ✅ Laravel Query Builder otomatik olarak korur
$blogs = Blog::where('slug', $slug)->first();

// ❌ Raw SQL kullanmayın (gerekmedikçe)
DB::select('SELECT * FROM blogs WHERE slug = ' . $slug);
```

---

## Hata Yönetimi

### 404 Sayfası

```php
// Route
Route::fallback(function () {
    return view('errors.404');
});
```

```blade
{{-- resources/views/errors/404.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <h1 class="text-6xl font-bold mb-4">404</h1>
    <p class="text-2xl mb-8">Sayfa Bulunamadı</p>
    <a href="/" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
        Anasayfaya Dön
    </a>
</div>
@endsection
```

---

## Sonuç

Bu dokümantasyon, Filament panelinde yönetilen tüm verilerin frontend'de nasıl kullanılacağını göstermektedir.

### Önemli Notlar:

1. **Modül Kontrolü:** Her zaman `config('modules.xxx')` ile modül aktif mi kontrol edin
2. **Eager Loading:** İlişkileri önceden yükleyin (performans için kritik)
3. **Cache:** Sık kullanılan verileri cache'leyin
4. **Security:** Kullanıcı girdilerini her zaman validate edin
5. **SEO:** Meta tag'leri doğru kullanın

### İlgili Dosyalar:

- `MODULER_SISTEM_KILAVUZU.md` - Modül yönetimi
- `app/Providers/AppServiceProvider.php` - View sharing
- `app/Providers/Filament/AdminPanelProvider.php` - Resource yönetimi

---

**✨ İyi çalışmalar!**

_Güncelleme Tarihi: 04 Kasım 2025_
