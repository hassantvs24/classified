<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'id'                => $this->id,
            'ads_packages_id'   => $this->ads_packages_id,
            'users_id'          => $this->users_id,
            'code'              => $this->code,
            'amount'            => $this->amount,
            'is_percent'        => $this->is_percent,
            'expire'            => $this->expire,
            'status'            => $this->status
        ];
    }
}
