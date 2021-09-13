<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
	protected $table = 'categories';

	protected $fillable=[
    	'name',
        'department_id'
    ];

    protected $visible=[
    	'name',
    	'id',
        'description',
        'department_id'
    ];
    
    public function subcategories()
    {
        return $this->hasMany('App\Models\Subcategory');
    }

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
