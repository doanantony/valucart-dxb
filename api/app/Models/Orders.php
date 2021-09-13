<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    

    // Statuses
    const CREATED = 1;
    const PLACED = 2;
    const DISPATCHED = 3;
    const DELIVERED = 4;
    const CANCELED = 19;

    const CARD_PAYMENT_INITIATED = 5;
    const CARD_PAYMENT_FAILED = 6;
    const CARD_PAYMENT_SUCCESS = 7;

    const COD_INITIATED = 8;
    const COD_FAILED = 9;
    const COD_SUCCESS = 10;

    const CANCELED_BY_CUSTOMER = 11;
    const CANCELED_BY_VALUCART = 12;

    const RETURN_INITIATED = 13;
    const RETURN_IN_PROGRESS = 14;
    const RETURN_COMPLETE = 15;

    const REFUND_INITIATED = 16;
    const REFUND_IN_PROGRESS = 17;
    const REFUND_COMPLETE = 18;

    public static $status_strings = [
        
        self::CREATED => 'Create',
        self::PLACED => 'Placed',
        self::DISPATCHED => 'Dispatched',
        self::DELIVERED => 'Delivered',
        self::CANCELED => 'Canceled',

        self::CARD_PAYMENT_INITIATED => 'Pending payment by card',
        self::CARD_PAYMENT_FAILED => 'Payment by card failed',
        self::CARD_PAYMENT_SUCCESS => 'Payment completed by card',

        self::COD_INITIATED => 'Pending payment on delivery',
        self::COD_FAILED => 'Payment on delivery (NOT PAID)',
        self::COD_SUCCESS => 'Paid on delivery',
        
        self::CANCELED_BY_CUSTOMER => 'Canceled by customer',
        self::CANCELED_BY_VALUCART => 'Canceled by ValuCart',

        self::RETURN_INITIATED => 'Return initiated',
        self::RETURN_IN_PROGRESS => 'Return in progress',
        self::RETURN_COMPLETE => 'Return complete',

        self::REFUND_INITIATED => 'Refund initiated',
        self::REFUND_IN_PROGRESS => 'Refund in progress',
        self::REFUND_COMPLETE => 'Refund complete',

    ];

    protected $table = 'orders';

	protected $fillable = [
        'vendor_id',
    	'customer_id',
    	'order_reference',
    	'payment_type',
    	'delivery_date',
    	'snapshots',
    	'created_at',
    	'updated_at',
    	'time_slot_id',
        'sub_total_price',
        'first_order_discount',
        'price',
        'cart_id',
        'address_id',
        'status',
        'note',
        'is_meat_monday',
    ];

    protected $visible = [
        'id',
        'customer_id',
        'order_reference',
        'payment_type',
        'delivery_date',
        'created_at',
        'updated_at',
        'time_slot_id',
        'sub_total_price',
        'first_order_discount',
        'price',
        'cart_id',
        'address_id',
        'is_scheduled',
        'delivery_fee',
        'status',
        'snapshots',
        'interval',
        'is_meat_monday',
    ];

    protected $appends = [
        'is_scheduled',
        'delivery_fee',
        'interval',
    ];

    protected $dates = [
        'delivery_date',
        'schedule_next_date',
    ];

    public function customer()
    {
        return $this->belongsTo('\App\Models\Customer');
    }

    public function cart()
    {
        return $this->hasOne('\App\Models\Cart', 'id', 'cart_id');
    }

    public function address()
    {
        return $this->hasOne('\App\Models\v2\CustomerAddress', 'id', 'address_id');
    }

    public function interval()
    {
        return $this->hasOne('\App\Models\OrdersScheduleInterval', 'id', 'schedule_interval_id');
    }

    public function getIntervalAttribute()
    {
        return $this->interval()->first();
    }

    public static function get_status_string(int $status)
    {
        return self::$status_strings[$status];
    }

    public function getIsScheduledAttribute()
    {
        return !is_null($this->schedule_interval_id);
    }

    public function getDeliveryFeeAttribute()
    {
        return 0;
    }

    public function getStatusAttribute($value)
    {
        
        if (!is_null($value)) {
            $status = static::$status_strings[$value];
        }

        // if (!is_null($this->payment_status)) {
            
        //     if (strlen($status) > 0) {
        //         $status .= ' . ';
        //     }
            
        //     $status .= static::$status_strings[$this->payment_status];
        // }

        // if (!is_null($this->payment_status)) {
            
        //     if (strlen($status) > 0) {
        //         $status .= ' . ';
        //     }
            
        //     $status .= static::$status_strings[$this->payment_status];
        // }

        // if (!is_null($this->cancelation_status)) {
            
        //     if (strlen($status) > 0) {
        //         $status .= ' . ';
        //     }
            
        //     $status .= static::$status_strings[$this->cancelation_status];
        // }

        return $status;

    }

    public static function get_next_schedule_order_date($interval, $from_date = null) {

        $days_of_week = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday'
        ];

        $date = Carbon::now();

        if (in_array($interval, $days_of_week)) {
            
            $date = Carbon::parse($interval);
        
        } else {
            
            [
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            ];

            $date = Carbon::parse($from_date)->add(CarbonInterval::fromString($interval));
        
        }

        if ($date->isFriday()) {
            $date->addDay();
        }

        return $date;

    }


     public function toArray()
    {
        
        $attributes = parent::toArray();

        $attributes["snapshots"] =  unserialize($this->snapshots);
        return $attributes;

    }





}
