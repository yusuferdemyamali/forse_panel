<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin paneli ve API rotalarını atla
        if ($request->is('admin', 'admin/*', 'api/*', 'livewire/*')) {
            return $next($request);
        }

        $requestPath = '/' . ltrim($request->path(), '/');

        // Cache'den yönlendirmeleri al (performans için)
        $redirect = Cache::remember(
            'redirect:' . $requestPath,
            now()->addHours(24),
            function () use ($requestPath) {
                return Redirect::where('source_url', $requestPath)
                    ->where('is_active', true)
                    ->first();
            }
        );

        if ($redirect) {
            return redirect($redirect->destination_url, $redirect->status_code);
        }

        return $next($request);
    }
}
