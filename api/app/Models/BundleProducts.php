<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleProducts extends Model
{
    protected $table = 'bundles_products';


    public function products()
    {

        return $this->belongsToMany(
            'App\Models\Product',
            'bundles_products_alternatives',
            'bundles_products_id',
            'product_id'
        )->withPivot('quantity');

    }


    
}
