<?php

namespace App\Concerns;

use App\Domain;
use App\Scopes\DomainScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToDomain
{
    /**
     * Get the domain this model belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class, $this->getDomainKeyColumn());
    }

    /**
     * Get the name of the "domain key" column.
     *
     * @return string
     */
    protected function getDomainKeyColumn(): string
    {
        return 'domain_id';
    }

    /**
     * Boot the `belongsToDomain` trait.
     *
     * @return void
     */
    protected static function bootBelongsToDomain(): void
    {
        static::addGlobalScope(new DomainScope);
    }
}
