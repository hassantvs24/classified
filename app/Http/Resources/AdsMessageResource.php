<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsMessageResource extends JsonResource
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
            'message'        => $this->message,
            'is_buyer'       => $this->is_buyer,//	0 mean it is seller end message & 1 mean buyer end message
            'ads_id'    => $this->ads_id,
            'user_id'    => $this->user->id,
            'customer'      => new CustomerResource($this->user)
        ];
    }
}
