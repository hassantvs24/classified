<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property integer $parents_id
 * @property string $icon
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Ad[] $ads
 * @property AttributeLink[] $attributeLinks
 * @property ProductType[] $productTypes
 * @property Product[] $products
 */
class ProductCategories extends Model
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
    protected $fillable = ['name', 'parents_id', 'icon', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany('App\Models\Ad', 'product_categories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeLinks()
    {
        return $this->hasMany('App\Models\AttributeLink', 'product_categories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTypes()
    {
        return $this->hasMany('App\Models\ProductType', 'product_categories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'product_categories_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\ProductCategories','parents_id')->where('parents_id', null)->with('parent')->select('id','name', 'parents_id', 'icon');
    }

    public function children()
    {
        return $this->hasMany('App\Models\ProductCategories','parents_id')->with('children')->select('id','name', 'parents_id', 'icon');
    }
}
