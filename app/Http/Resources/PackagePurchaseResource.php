<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PackagePurchaseResource extends JsonResource
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
            'amount'  => $this->amount,
            'remaining'  => $this->remaining() ?? 0,
            'expire'       => Carbon::parse($this->expire)->format('d/m/Y h:i A'),
            'discount'      => $this->discount,
            'created_at'  => Carbon::parse($this->created_at)->format('d/m/Y'),
            'coupon'      => new CouponResource($this->coupon),
            'package'      => new AdPackageResource($this->adsPackage),
            'customer'      => new CustomerResource($this->user),
            'company'      => new CompanyResource($this->company)
        ];
    }
}
