<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// ÖNEMLİ: SİZİN SITEMAP ZAMANLAMANIZ
$schedule = app(Schedule::class);

// DÜZELTME: call() ve closure yerine, doğrudan komut ismini command() ile çağırıyoruz.
$schedule->command('sitemap:generate')
         ->dailyAt('03:00') // Sitemapi her gün 03:00'da çalıştır.
         ->runInBackground() // Artık çalışacaktır!
         ->onOneServer();