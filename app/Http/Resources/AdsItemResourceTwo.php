<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsItemResourceTwo extends JsonResource
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
            'id'           => $this->id,
            'quantity'     => $this->quantity,
            'descriptions' => $this->descriptions,
            'photo'        => asset($this->photo)
        ];
    }
}
