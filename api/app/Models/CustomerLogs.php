<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CustomerLogs extends Model
{   
    protected $table = 'customer_logs'; 

    protected $fillable = [
    	'name',
    	'customer_id',   
    ];


    protected $visible = [
        'id',
        'name',
        'customer_id',
    ];

    protected $appends = [
        'vendors',
    ];

    public function vendors()
    {

        return $this->belongsToMany(
            'App\Models\Departments',
            'customer_log_vendors',
            'log_id',
            'vendor_id'
        );

    }
    
    
    
    public function toArray()
    {
        
        $attributes = parent::toArray();

        $attributes['vendors'] = $this->vendors;

        return $attributes;

    }

}
