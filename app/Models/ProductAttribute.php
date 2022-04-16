<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $attributes_id
 * @property integer $products_id
 * @property mixed $values
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Attribute $attribute
 * @property Product $product
 */
class ProductAttribute extends Model
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
    protected $fillable = ['attributes_id', 'products_id', 'values', 'deleted_at', 'created_at', 'updated_at'];

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
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products_id');
    }
}
