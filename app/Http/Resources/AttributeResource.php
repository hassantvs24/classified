<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'is_filterable' => $this->is_filterable,
            'attribute_set' => new AttributeSetResource($this->attributeSet),
            'link_with' => AttributeLinkResource::collection($this->attributeLinks),
            'attr_value' => AttributeValueResource::collection($this->attributeValues)
        ];
    }
}
