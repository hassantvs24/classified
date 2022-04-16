<?php

namespace App\Http\Resources;

use App\Models\AttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $values_json = json_decode($this->values);

        $values = [];
        foreach ($values_json as $attr_id){
            $values[] = new AttributeValueResource(AttributeValue::find($attr_id));
        }

        return [
            'id' => $this->id,
            'attributes_id' => $this->attributes_id,
            'values' => $values
        ];
    }
}
