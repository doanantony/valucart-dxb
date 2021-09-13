<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
    	'customer_id',
    	'order_id',
        'feedback',
    ];

    protected $visible = [
        'id',
        'order_id',
        'feedback'
    ];

}
