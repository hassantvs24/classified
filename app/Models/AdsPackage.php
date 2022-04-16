<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $types
 * @property int $quantity
 * @property boolean $expire_day
 * @property float $price
 * @property string $banner
 * @property string $description
 * @property boolean $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Coupon[] $coupons
 * @property PurchasePackage[] $purchasePackages
 */
class AdsPackage extends Model
{

    protected $keyType = 'integer';

    protected $fillable = ['name', 'types', 'quantity', 'expire_day', 'price', 'banner', 'description', 'status', 'deleted_at', 'created_at', 'updated_at'];


    public function coupons()
    {
        return $this->hasMany('App\Models\Coupon', 'ads_packages_id');
    }

    public function purchasePackages()
    {
        return $this->hasMany('App\Models\PurchasePackage', 'ads_packages_id');
    }

    public function scopeActive( $query, $value ) {
        return $query->where( 'active', $value );
    }

    public function scopeType( $query, $value ) {
        return $query->where( 'types', $value );
    }
}
