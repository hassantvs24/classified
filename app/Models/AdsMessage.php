<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $ads_id
 * @property integer $users_id
 * @property string $message
 * @property boolean $is_buyer
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Ad $ad
 * @property User $user
 */
class AdsMessage extends Model
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
    protected $fillable = ['ads_id', 'users_id', 'message', 'is_buyer', 'deleted_at', 'created_at', 'updated_at'];

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
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id');
    }
}
