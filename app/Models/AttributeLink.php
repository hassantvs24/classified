<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_categories_id
 * @property integer $attributes_id
 * @property string $created_at
 * @property string $updated_at
 * @property Attribute $attribute
 * @property ProductCategories $productCategory
 */
class AttributeLink extends Model
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
    protected $fillable = ['product_categories_id', 'attributes_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute', 'attributes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productCategory()
    {
        return $this->belongsTo('App\Models\ProductCategories', 'product_categories_id');
    }
}
