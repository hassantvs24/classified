<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $products_id
 * @property integer $ads_id
 * @property float $quantity
 * @property string $descriptions
 * @property string $photo
 * @property string $created_at
 * @property string $updated_at
 * @property Ad $ad
 * @property Product $product
 */
class AdsItem extends Model
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
    protected $fillable = ['products_id', 'ads_id', 'quantity', 'descriptions', 'photo', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad()
    {
        return $this->belongsTo('App\Models\Ads', 'ads_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products_id');
    }
}
