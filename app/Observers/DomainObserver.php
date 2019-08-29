<?php

namespace App\Observers;

use App\Domain;
use Illuminate\Support\Str;

class DomainObserver
{
    /**
     * Handle the domain "saving" event.
     *
     * @param  \App\Domain $domain
     * @return void
     */
    public function creating(Domain $domain)
    {
        $domain->setAttribute('api_token', $domain->generateApiToken());
    }
}
