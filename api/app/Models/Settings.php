<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'system_settings';


    protected $visible=[
    	'id',
        'vendor_id',
        'minimum_order',
        // 'freedelivery_minimum_order',
        // 'delivery_charge'
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
