<?php

namespace App\Renderers;

use App\Services\Pagie;

class PageRenderer extends ResourceRenderer implements RendererInterface
{
    public function render()
    {
        return view('render-resources/page', [
            'resource' => $this->resourceInstance,
            'domain' => Pagie::getInstance()->resolvedDomain,
        ]);
    }
}
