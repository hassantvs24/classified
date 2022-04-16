<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdPhotosResource extends JsonResource
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
           'ads_id'    => $this->ads_id,
           'id'     => $this->id,
           'uid'     => '-'.$this->id,
           'status'     => 'done',
           'url'   => asset($this->name)
        ];
    }
}
