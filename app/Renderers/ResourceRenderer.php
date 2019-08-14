<?php

namespace App\Renderers;

use Illuminate\Contracts\Support\Responsable;

abstract class ResourceRenderer
{
    protected $resourceInstance;

    public function fromResourceInstance(Responsable $resource)
    {
        $this->resourceInstance = $resource;

        return $this;
    }
}
