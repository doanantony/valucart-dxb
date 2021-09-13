<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{

    protected $table = 'products_images';

    protected $fillable = [
        'product_id',
        'path'
    ];

    protected $visible = [
        'path',
        'is_thumb'
    ];

}
