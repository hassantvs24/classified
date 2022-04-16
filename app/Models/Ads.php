<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_brands_id
 * @property integer $product_categories_id
 * @property integer $product_types_id
 * @property integer $companies_id
 * @property integer $ads_packages_id
 * @property integer $products_id
 * @property integer $users_id
 * @property string $name
 * @property string $state
 * @property string $seller
 * @property string $email
 * @property string $phone
 * @property string $contact_time
 * @property string $brand
 * @property string $category
 * @property string $product_types
 * @property string $photo
 * @property float $price
 * @property string $descriptions
 * @property boolean $is_used
 * @property boolean $is_shipping
 * @property string $status
 * @property string $expire
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property AdsPackage $adsPackage
 * @property Company $company
 * @property ProductBrand $productBrand
 * @property ProductCategory $productCategory
 * @property ProductType $productType
 * @property Product $product
 * @property User $user
 * @property AdsAttribute[] $adsAttributes
 * @property AdsFavorite[] $adsFavorites
 * @property AdsItem[] $adsItems
 * @property AdsMessage[] $adsMessages
 * @property AdsPhoto[] $adsPhotos
 * @property AdsReview[] $adsReviews
 * @property AdsTag[] $adsTags
 * @property Notification[] $notifications
 */
class Ads extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['areas_id', 'product_brands_id', 'product_categories_id', 'product_types_id', 'companies_id', 'purchase_packages_id', 'products_id', 'users_id', 'name', 'state', 'seller', 'email', 'phone', 'contact_time', 'brand', 'category', 'product_types', 'photo', 'price', 'descriptions', 'is_used', 'is_shipping', 'status', 'expire', 'deleted_at', 'created_at', 'updated_at'];

    public function areas()
    {
        return $this->belongsTo('App\Models\Area', 'areas_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchasePackage()
    {
        return $this->belongsTo('App\Models\PurchasePackage', 'purchase_packages_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companies()
    {
        return $this->belongsTo('App\Models\Company', 'companies_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productBrand()
    {
        return $this->belongsTo('App\Models\ProductBrand', 'product_brands_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productCategory()
    {
        return $this->belongsTo('App\Models\ProductCategories', 'product_categories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productType()
    {
        return $this->belongsTo('App\Models\ProductType', 'product_types_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adsAttributes()
    {
        return $this->hasMany('App\Models\AdsAttribute', 'ads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adsFavorites()
    {
        return $this->hasMany('App\Models\AdsFavorite', 'ads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adsItems()
    {
        return $this->hasMany('App\Models\AdsItem', 'ads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adsMessages()
    {
        return $this->hasMany('App\Models\AdsMessage', 'ads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adsPhotos()
    {
        return $this->hasMany('App\Models\AdsPhoto', 'ads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adsReviews()
    {
        return $this->hasMany('App\Models\AdsReview', 'ads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adsTags()
    {
        return $this->hasMany('App\Models\AdsTag', 'ads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('App\Models\Notification', 'ads_id');
    }

    public function scopeState( $query, $value ) {
        return $query->where( 'state', $value );
    }

    public function scopeStatus( $query, $value ) {
        return $query->where( 'status', $value );
    }

    public function scopeUsed( $query, $value ) {
        return $query->where( 'is_used', $value );
    }

    public function scopeShipping( $query, $value ) {
        return $query->where( 'is_shipping', $value );
    }
}
