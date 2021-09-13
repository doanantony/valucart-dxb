<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class MetricUnit extends Model
{
    protected $table = 'matric_units';

    protected $fillable=[
        'name',
        'symbol',    	
    ];
    
    protected $visible=[
        'id',
        'name',
        'symbol',    	
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
