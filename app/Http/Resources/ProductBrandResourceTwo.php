<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductBrandResourceTwo extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'origin' => $this->origin,
            'description' => $this->description,
            'logo' => isset($this->logo) ? asset($this->logo) : ''
        ];
    }
}
