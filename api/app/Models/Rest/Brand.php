<?php

namespace App\Models\Rest;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable=[
    	'name'
    ];

    protected $visible=[
    	'name',
    	'id'
    ];

}
