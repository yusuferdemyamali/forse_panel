<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modül Yönetimi
    |--------------------------------------------------------------------------
    |
    | Bu dosya, projedeki tüm modüllerin açık/kapalı durumunu yönetir.
    | Kapatılan modüller için:
    | - Migration'lar çalışmaz
    | - Filament Resource, Widget ve Navigation'lar yüklenmez
    |
    | Değerler .env dosyasından okunur, varsayılan değer 'true'dur.
    |
    */

    'blog' => env('MODULE_BLOG_ENABLED', true),

    'references' => env('MODULE_REFERENCES_ENABLED', true),
    
    'contact' => env('MODULE_CONTACT_ENABLED', true),

    'products' => env('MODULE_PRODUCTS_ENABLED', true),

    'services' => env('MODULE_SERVICES_ENABLED', true),

    'gallery' => env('MODULE_GALLERY_ENABLED', true),

    'faq' => env('MODULE_FAQ_ENABLED', true),

    'team' => env('MODULE_TEAM_ENABLED', true),

    'about' => env('MODULE_ABOUT_ENABLED', true),

    'pages' => env('MODULE_PAGES_ENABLED', true),

];
