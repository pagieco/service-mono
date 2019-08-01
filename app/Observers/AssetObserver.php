<?php

namespace App\Observers;

use App\Asset;

class AssetObserver
{
    /**
     * Handle the database "saving" event.
     *
     * @param  \App\Asset  $asset
     * @return void
     */
    public function creating(Asset $asset)
    {
        $asset->setAttribute('original_filename', $asset->filename);
    }
}
