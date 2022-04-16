<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd( $this->productType );

        return [
            'id'                    => $this->id,
            'product_brands'        => new ProductBrandResourceTwo( $this->productBrand ),
            'product_categories'    => new ProductCategoriesResourceTwo( $this->productCategory ),
            'product_types_id'      => new ProductTypeResourceTwo( $this->productType ),
            'companies'             => new CompanyResource( $this->companies),
            'purchase_packages'     => new PackagePurchaseResource($this->purchasePackage),
            'products'              => new ProductResourceTwo( $this->product ),
            'users'                 => new UserResource( $this->user ),
            'reviews'               => AdReviewsResource::collection( $this->adsReviews ),
            'gallery'               => AdPhotosResource::collection( $this->adsPhotos ),
            'tags'                  => AdsTagsResourceTwo::collection( $this->adsTags ),
            'items'                 => AdsItemResourceTwo::collection( $this->adsItems ),
            'ads_attribute'         => AdAttributeResource::collection( $this->adsAttributes),
            'name'                  => $this->name,
            'state'                 => $this->state,
            'seller'                => isset( $this->seller ) ? $this->seller : '',
            'email'                 => isset( $this->email ) ? $this->email : '',
            'phone'                 => isset( $this->phone ) ? $this->phone : '',
            'contact_time'          => isset( $this->contact_time ) ? $this->contact_time : '',
            'brand'                 => isset( $this->brand ) ? $this->brand : '',
            'category'              => isset( $this->category ) ? $this->category : '',
            'product_types'         => isset( $this->product_types ) ? $this->product_types : '',
            'photo'                 => isset( $this->photo ) ? asset( $this->photo ) : '',
            'price'                 => $this->price,
            'descriptions'          => isset( $this->descriptions ) ? $this->descriptions : '',
            'is_used'               => $this->is_used,
            'is_shipping'           => $this->is_shipping,
            'status'                => $this->status,
            'expire'                => $this->expire
        ];
    }
}
