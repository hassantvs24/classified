<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $users_id
 * @property string $name
 * @property string $logo
 * @property string $description
 * @property boolean $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property Ad[] $ads
 */
class Company extends Model
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
    protected $fillable = ['users_id', 'name', 'logo', 'description', 'contact', 'contact_person', 'website', 'status', 'deleted_at', 'created_at', 'updated_at'];

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
    public function ads()
    {
        return $this->hasMany('App\Models\Ad', 'companies_id');
    }
}
