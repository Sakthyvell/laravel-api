<?php

namespace App\Observers;

use App\Models\LegalEntity;
use Illuminate\Support\Facades\Cache;

class LegalEntityObserver
{
    /**
     * Handle the LegalEntity "created" event.
     */
    public function created(LegalEntity $legalEntity): void
    {
        Cache::forget('entity-list');
    }
    
    /**
     * Handle the LegalEntity "updated" event.
     */
    public function updated(LegalEntity $legalEntity): void
    {
        //
        Cache::forget('entity-list');
    }
    
    /**
     * Handle the LegalEntity "deleted" event.
     */
    public function deleted(LegalEntity $legalEntity): void
    {
        //
        Cache::forget('entity-list');
    }

    /**
     * Handle the LegalEntity "restored" event.
     */
    public function restored(LegalEntity $legalEntity): void
    {
        //
    }

    /**
     * Handle the LegalEntity "force deleted" event.
     */
    public function forceDeleted(LegalEntity $legalEntity): void
    {
        //
    }
}
