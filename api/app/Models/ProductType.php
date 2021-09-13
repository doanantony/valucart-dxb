<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{

    protected $table = 'product_types';

    protected $fillable=[
    	'name'
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
