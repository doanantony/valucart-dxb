<?php

namespace App\Models;
use Hashids;

use Illuminate\Database\Eloquent\Model;

class Popularcustomers extends Model
{
    protected $table = 'popular_customers';

	protected $fillable = [
    	'name',
    	'description',
    	'status',

    ];

    protected $visible = [
        'id',
        'name',
        'description',
    	'image'
    ];

    protected $appends = [
        'image	'
    ];

    public function getImageAttribute()
    {
        return url('/img/popular_customers/' . $this->name . '.jpg');
    }

    public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);

        return $attributes;

    }
}
