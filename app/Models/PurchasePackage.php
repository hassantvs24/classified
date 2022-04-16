<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $coupons_id
 * @property integer $ads_packages_id
 * @property integer $users_id
 * @property string $name
 * @property string $types
 * @property int $quantity
 * @property float $amount
 * @property float $discount
 * @property string $status
 * @property string $expire
 * @property boolean $is_percent
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property AdsPackage $adsPackage
 * @property Coupon $coupon
 * @property User $user
 */
class PurchasePackage extends Model
{

    protected $keyType = 'integer';


    protected $fillable = ['coupons_id', 'ads_packages_id', 'companies_id', 'users_id', 'name', 'types', 'quantity', 'amount', 'discount', 'status', 'expire', 'is_percent', 'deleted_at', 'created_at', 'updated_at', 'stripe_payment_id'];


    public function adsPackage()
    {
        return $this->belongsTo('App\Models\AdsPackage', 'ads_packages_id');
    }


    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon', 'coupons_id');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

    public function company(){
        return $this->belongsTo('App\Models\Company', 'companies_id');
    }

    public function usePackage(){
        return $this->hasMany('App\Models\Ads', 'purchase_packages_id');
    }

    public function remaining(){
        $total = $this->quantity;
        $use_package = $this->usePackage()->count();
        return $total - $use_package;
    }
}
