<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AdPackageResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'types'       => $this->types,
            'quantity'    => $this->quantity,
            'expire_day'  => $this->expire_day,
            'price'       => $this->price,
            'status'      => $this->status,
            'description' => isset( $this->description ) ? $this->description : null,
            'banner'      => isset( $this->banner ) ? asset($this->banner) : null
        ];
    }
}
