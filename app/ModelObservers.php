<?php

namespace App;

use App\Observers;

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
