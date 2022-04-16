<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResourceTwo extends JsonResource
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
            'values' => $this->name,
            //'product_categories' => AttributProductCategoryResource::collection($this->attribute->attributeLinks),
            'attributes_id' => $this->attributes_id,
            'attribute' => $this->attribute->name ?? ''
        ];
    }
}
