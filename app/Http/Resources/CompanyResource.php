<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'id'       => $this->id,
            'users_id' => $this->users_id,
            'name'     => $this->name,
            'logo'     => isset( $this->logo ) ? asset( $this->logo ) : '',
            'description' => isset( $this->description ) ? $this->description : '', 
            'contact' => isset( $this->contact ) ? $this->contact : '',
            'contact_person' => isset( $this->contact_person ) ? $this->contact_person : '',
            'website' => isset( $this->website ) ? $this->website : '',
            'status' => $this->status
        ];
    }
}
