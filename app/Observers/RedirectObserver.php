<?php

namespace App\Observers;

use App\Models\Redirect;
use Illuminate\Support\Facades\Cache;

class RedirectObserver
{
    /**
     * Cache'i temizle
     */
    private function clearCache(Redirect $redirect): void
    {
        Cache::forget('redirect:' . $redirect->source_url);
    }

    /**
     * Handle the Redirect "created" event.
     */
    public function created(Redirect $redirect): void
    {
        $this->clearCache($redirect);
    }

    /**
     * Handle the Redirect "updated" event.
     */
    public function updated(Redirect $redirect): void
    {
        $this->clearCache($redirect);
        
        // Eski URL değiştiyse onu da temizle
        if ($redirect->isDirty('source_url')) {
            Cache::forget('redirect:' . $redirect->getOriginal('source_url'));
        }
    }

    /**
     * Handle the Redirect "deleted" event.
     */
    public function deleted(Redirect $redirect): void
    {
        $this->clearCache($redirect);
    }
}
