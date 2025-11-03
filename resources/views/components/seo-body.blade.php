@php
    use App\Settings\SeoSettings;
    
    try {
        $seoSettings = app(SeoSettings::class);
    } catch (\Exception $e) {
        $seoSettings = null;
    }
@endphp

@if($seoSettings && $seoSettings->body_scripts)
    {!! $seoSettings->body_scripts !!}
@endif
