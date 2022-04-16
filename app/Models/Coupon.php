<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $ads_packages_id
 * @property integer $users_id
 * @property string $code
 * @property float $amount
 * @property boolean $is_percent
 * @property string $expire
 * @property string $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property AdsPackage $adsPackage
 * @property User $user
 * @property PurchasePackage[] $purchasePackages
 */
class Coupon extends Model
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
    protected $fillable = ['ads_packages_id', 'users_id', 'code', 'amount', 'is_percent', 'expire', 'status', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adsPackage()
    {
        return $this->belongsTo('App\Models\AdsPackage', 'ads_packages_id');
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
    public function purchasePackages()
    {
        return $this->hasMany('App\Models\PurchasePackage', 'coupons_id');
    }
}
