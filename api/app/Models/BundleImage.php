<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class BundleImage extends Model
{
    protected $table = 'bundles_images';

    protected $fillable = [
        'bundle_id',
        'path'
    ];

    protected $visible = [
    	'path'
    ];
}
