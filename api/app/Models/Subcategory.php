<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
   protected $table = 'subcategories';

	protected $fillable=[
    	'name',
    	'category_id'
    ];

    protected $visible=[
    	'name',
    	'id'
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
	
}
