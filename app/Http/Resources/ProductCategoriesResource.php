<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProductCategoriesResource extends JsonResource
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
            'parents_id' => $this->parents_id,
            'slug' => Str::slug($this->name, '-'),
            'parent' => $this->parent->name ?? '',
            'icon' => isset($this->icon) ? asset($this->icon) : '',
            'link_attribute' => AttributeLinkResource::collection($this->attributeLinks()->get())
        ];
    }
}
