<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $attribute_sets_id
 * @property string $name
 * @property boolean $is_filterable
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property AttributeSet $attributeSet
 * @property AttributeLink[] $attributeLinks
 * @property AttributeValue[] $attributeValues
 * @property ProductAttribute[] $productAttributes
 */
class Attribute extends Model
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
    protected $fillable = ['attribute_sets_id', 'name', 'is_filterable', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attributeSet()
    {
        return $this->belongsTo('App\Models\AttributeSet', 'attribute_sets_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeLinks()
    {
        return $this->hasMany('App\Models\AttributeLink', 'attributes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeValues()
    {
        return $this->hasMany('App\Models\AttributeValue', 'attributes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productAttributes()
    {
        return $this->hasMany('App\Models\ProductAttribute', 'attributes_id');
    }
}
