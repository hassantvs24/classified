<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $origin
 * @property string $description
 * @property string $logo
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Ads[] $ads
 * @property Product[] $products
 */
class ProductBrand extends Model
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
    protected $fillable = ['name', 'origin', 'description', 'logo','product_categories_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany('App\Models\Ad', 'product_brands_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'product_brands_id');
    }

    public function productCategory()
    {
        return $this->belongsTo('App\Models\ProductCategories', 'product_categories_id');
    }
}
