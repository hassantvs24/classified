<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResourceTwo extends JsonResource
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
            'state' => $this->state,
            'descriptions' => $this->descriptions,
            'is_disable' => $this->is_disable ? 'Yes':'No',
            'price' => $this->price,
            'photo' => isset($this->photo) ? asset($this->photo) : ''
        ];
    }
}
