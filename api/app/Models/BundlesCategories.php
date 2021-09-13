<?php

namespace App\Models;

use Hashids;

use Illuminate\Database\Eloquent\Model;

class BundlesCategories extends Model
{
   protected $fillable=[
    	'name'
    ];

    protected $visible=[
    	'name',
    	'id'
    ];


    public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);

        return $attributes;

    }

    
}
