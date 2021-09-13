<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
     protected $fillable = [
    	'name',
    	'short_name',
        'path'
    ];

    protected $visible = [
		'id',
		'name',
    	'short_name',
        'image'
	];
	
    protected $appends = [
        'image'
    ];

    public function getImageAttribute()
    {   
        $full_path = 'img/vendors/' . Hashids::encode($this->id) . '/' .$this->path;

        if($this->path)
        {
            return url($full_path);
        }else{
            return '';
        }
        
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
