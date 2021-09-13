<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $visible = [
        'id',
        'name2',
        'iso2',
        'iso3'
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
