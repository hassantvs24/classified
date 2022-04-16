<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeLinkResource extends JsonResource
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
            'attribute' => $this->attribute->name ?? '',
            'attributes_id' => $this->attributes_id,
            'product_categories_id' => $this->product_categories_id,
            'product_category' => $this->productCategory->name ?? '',

        ];
    }
}
