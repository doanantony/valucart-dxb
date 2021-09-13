<?php

use Hashids;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedProduct extends Model
{
	
	protected $table = 'featured_products';	
	
	protected $fillable=[
    	'product_id',
    ];

    protected $visible=[
    	'product_id',
    ];


	public function product()
	{
	    return $this->hasOne('App\Models\Product', 'id', 'product_id');
	}
	
	public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);

        return $attributes;

    }
}
