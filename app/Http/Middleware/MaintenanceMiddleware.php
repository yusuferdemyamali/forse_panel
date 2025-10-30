<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Symfony\Component\HttpFoundation\Response;
// Artık Filament Facade'ına ihtiyacımız kalmadı
// use Filament\Facades\Filament; 

class MaintenanceMiddleware
{
    /**
     * Filament panelinizin rotası.
     * Genellikle 'admin' olur. Değilse buradan değiştirin.
     */
    protected string $filamentPath = 'admin';

    public function handle(Request $request, Closure $next): Response
    {
        // Panel yolunu dinamik olarak almak yerine doğrudan kullanıyoruz.
        // Bu, middleware sıralaması sorunlarını ortadan kaldırır.
        

        if ($request->is(
            $this->filamentPath,
            $this->filamentPath . '/',  
            $this->filamentPath . '/*',
            'livewire/*'  // <-- SORUNU ÇÖZECEK OLAN EKLEME
        )) {
            return $next($request);
        }

        // Sitenin kalanı için bakım modunu kontrol et
        if (SiteSetting::getCachedMaintenanceStatus()) {
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}