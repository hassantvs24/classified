<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_categories_id
 * @property integer $product_types_id
 * @property integer $product_brands_id
 * @property string $name
 * @property string $state
 * @property string $descriptions
 * @property boolean $is_disable
 * @property string $photo
 * @property float $price
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property ProductBrand $productBrand
 * @property ProductCategory $productCategory
 * @property ProductType $productType
 * @property Ad[] $ads
 * @property AdsItem[] $adsItems
 * @property ProductAttribute[] $productAttributes
 * @property ProductTag[] $productTags
 */
class Product extends Model
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
    protected $fillable = ['product_categories_id', 'product_types_id', 'product_brands_id', 'name', 'state', 'descriptions', 'is_disable', 'photo', 'price', 'deleted_at', 'created_at', 'updated_at'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany('App\Models\Ad', 'products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adsItems()
    {
        return $this->hasMany('App\Models\AdsItem', 'products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productAttributes()
    {
        return $this->hasMany('App\Models\ProductAttribute', 'products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTags()
    {
        return $this->hasMany('App\Models\ProductTag', 'products_id');
    }
}
