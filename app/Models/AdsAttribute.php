<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $ads_id
 * @property string $name
 * @property mixed $values
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Ad $ad
 */
class AdsAttribute extends Model
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
    protected $fillable = ['ads_id', 'name', 'values', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad()
    {
        return $this->belongsTo('App\Models\Ads', 'ads_id');
    }
}
