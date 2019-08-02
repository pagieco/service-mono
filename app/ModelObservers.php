<?php

namespace App;

trait ModelObservers
{
    /**
     * All of the model observer mappings.
     *
     * @var array
     */
    protected $observers = [
        Asset::class => Observers\AssetObserver::class,
    ];
}
