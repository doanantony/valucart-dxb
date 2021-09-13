<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersScheduleInterval extends Model
{
    
    protected $table = 'order_schedule_interval';

    protected $fillable = [
        'name',
        'interval',
    ];

    protected $visible = [
        'id',
        'name',
        'interval',
    ];

}
