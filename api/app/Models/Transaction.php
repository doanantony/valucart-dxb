<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    
    protected $table = 'transactions';

    protected $fillable = [
        'valucart_order_id',
        'network_reference',
        'amount',
        'currency',
        'status',
        'network_payment_url',
        'network_created_at',
    ];

    protected $dates = [
        'network_created_at',
        'created_at',
        'updated_at',
    ];

    protected $visible = [
        'id',
        'valucart_order_id',
        'network_reference',
        'amount',
        'currency',
        'status',
        'network_payment_url',
        'network_created_at',
        'created_at',
        'updated_at',
    ];

}
