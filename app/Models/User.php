<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'types',
        'contact',
        'website',
        'address',
        'description',
        'photo',
        'password',
    ];


    public function ads()
    {
        return $this->hasMany('App\Models\Ad', 'users_id');
    }

    public function adsFavorites()
    {
        return $this->hasMany('App\Models\AdsFavorite', 'users_id');
    }


    public function adsMessages()
    {
        return $this->hasMany('App\Models\AdsMessage', 'users_id');
    }


    public function adsReviews()
    {
        return $this->hasMany('App\Models\AdsReview', 'users_id');
    }


    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'users_id');
    }


    public function coupons()
    {
        return $this->hasMany('App\Models\Coupon', 'users_id');
    }


    public function notifications()
    {
        return $this->hasMany('App\Models\Notification', 'users_id');
    }


    public function purchasePackages()
    {
        return $this->hasMany('App\Models\PurchasePackage', 'users_id');
    }


    public function searchSaves()
    {
        return $this->hasMany('App\Models\SearchSafe', 'users_id');
    }


    public function subscriptions()
    {
        return $this->hasMany('App\Models\Subscription', 'seller_id');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
