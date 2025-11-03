@php
    use App\Settings\SeoSettings;
    
    try {
        $seoSettings = app(SeoSettings::class);
    } catch (\Exception $e) {
        $seoSettings = null;
    }
@endphp

@if($seoSettings)
    {{-- Meta Tags --}}
    <meta name="description" content="{{ $seoSettings->site_description }}">
    
    {{-- Open Graph Tags --}}
    <meta property="og:title" content="{{ $seoSettings->site_name }}">
    <meta property="og:description" content="{{ $seoSettings->site_description }}">
    @if($seoSettings->og_image)
        <meta property="og:image" content="{{ Storage::url($seoSettings->og_image) }}">
    @endif
    
    {{-- Google Analytics --}}
    @if($seoSettings->google_analytics_id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $seoSettings->google_analytics_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $seoSettings->google_analytics_id }}');
        </script>
    @endif
    
    {{-- Custom Head Scripts --}}
    @if($seoSettings->head_scripts)
        {!! $seoSettings->head_scripts !!}
    @endif
@endif
