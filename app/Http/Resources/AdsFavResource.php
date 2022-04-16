<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsFavResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'product_brands'        => new ProductBrandResource( $this->productBrand ),
            'product_categories'    => new ProductCategoriesResource( $this->productCategory ),
            'product_types_id'      => new ProductTypeResource( $this->productType ),
            'companies'             => new CompanyResource( $this->company),
            'purchase_packages'     => new PackagePurchaseResource($this->purchasePackage),
            'products'              => new ProductResource( $this->product ),
            'reviews'               => AdReviewsResource::collection( $this->adsReviews ),
            'gallery'               => AdPhotosResource::collection( $this->adsPhotos ),
            'tags'                  => AdsTagsResource::collection( $this->adsTags ),
            'items'                 => AdsItemResource::collection( $this->adsItems ),
            'ads_attribute'         => AdAttributeResource::collection( $this->adsAttributes),
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
