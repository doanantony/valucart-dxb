<?php

namespace App\Models\Rest;

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

}
