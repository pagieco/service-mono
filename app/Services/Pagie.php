<?php

namespace App\Services;

use App\Domain;
use Illuminate\Contracts\Support\Responsable;

class Pagie
{
    /**
     * The currently resolved request domain.
     *
     * @var \App\Domain|null
     */
    public $resolvedDomain = null;

    /**
     * The currently resolved request resource.
     *
     * @var \Illuminate\Contracts\Support\Responsable|null
     */
    public $resolvedResource = null;

    protected static $instance;

    /**
     * Set the resolved domain.
     *
     * @param  \App\Domain $domain
     * @return void
     */
    public function setResolvedDomain(Domain $domain): void
    {
        $this->resolvedDomain = $domain;
    }

    /**
     * Set the resolved resource.
     *
     * @param  \Illuminate\Contracts\Support\Responsable $resource
     * @return void
     */
    public function setResolvedResource(Responsable $resource): void
    {
        $this->resolvedResource = $resource;
    }

    public static function getInstance()
    {
        if (! static::$instance) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
