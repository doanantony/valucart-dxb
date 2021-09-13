<?php

namespace App\Models;
use Hashids;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{	
	 protected $table = 'wishlist';

    protected $fillable=[
    	'customer_id',
    	'item_type',
    	'item_id'
    ];

    protected $visible=[
    	'customer_id',
    	'item_type',
    	'item_id',
    	'created_at',
    	'updated_at'
    ];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);

        return $attributes;

    }


    public function products()
    {

        return $this->belongsToMany(
            'App\Models\Product',
            'wishlist',
            'customer_id',
            'item_id'
        )
        ->wherePivot('item_type', 'product');

    }

    public function bundles()
    {

        return $this->belongsToMany(
            'App\Models\Bundles',
            'wishlist',
            'customer_id',
            'item_id'
        )
       // ->withPivot('quantity')
        ->where('item_type', 'bundle');

    }



}
