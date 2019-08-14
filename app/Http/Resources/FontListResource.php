<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FontListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'origin' => $this->origin,
            'family' => $this->family,
            'variants' => $this->variants,
            'subsets' => $this->subsets,
        ];
    }
}
