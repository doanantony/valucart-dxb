<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;

class DeliveryTimeSlot extends Model
{
    
    use SoftDeletes;

    protected $table = 'delivery_time_slots';

    protected $fillable = [
        'time_slots',
        'from',
        'to',
        'cut_off', // 11am | 7pm previous day
        'unpublished_at'
    ];

    protected $visible = [
        'id',
        'from',
        'to',
        'available',
        'tz',
    ];

    protected $appends = [
        'available',
        'tz',
    ];

    protected $dates = [
        'unpublished_at',
    ];

    public static $cart_type = null;
    public static $for = null;
    public static $with_extended_lead_time = false;

    public function __toString()
    {
        return $this->from->format('H:i') . ' - ' . $this->to->format('H:i');
    }

    public function orders()
    {
        return $this->hasMany("\App\Models\Orders", "time_slot_id");
    }

    public function getFromAttribute($value)
    {
        $from = Carbon::parse($value, 'UTC');
        $from->setTimeZone('Asia/Dubai');
        return $from;
    }

    public function getToAttribute($value)
    {
        $to = Carbon::parse($value, 'UTC');
        $to->setTimeZone('Asia/Dubai');
        return $to;
    }
    
    public function getAvailableAttribute()
    {
        return $this->is_available();
    }

    public function getTzAttribute()
    {
        return 'Asia/Dubai';
    }

    public function is_available()
    {

        // if (!is_null(self::$for) && strtotime(self::$for) === false) {
        //     return false;
        // }
        
        // if($this->unpublished_at) {
        //     return false;
        // }

        $timezone = $this->getTzAttribute();

        $now = Carbon::now($timezone);
        $today = Carbon::today($timezone);
        $tomorrow = Carbon::tomorrow($timezone);
        $opening_time = Carbon::parse($this->from, $timezone);

        $day = is_null(self::$for) ? Carbon::today($timezone) : Carbon::parse(self::$for, $timezone);
        $day->hour = $opening_time->hour;
        $day->minute = $opening_time->minute;
        $day->second = $opening_time->second;

        // delivery day is in past or is Friday
        // if ($day->lessThan($today) || $day->isFriday() || $day->isSunday() || $day->isMonday() ) {
        //     return false;
        // }

        // // normal cart and delivery day is monday
        // if(static::$cart_type == "cart" && $day->dayOfWeek == 1) {
        //     return false;
        // }

        // // meatmonday and delivery day is not monday
        // if(static::$cart_type == "meatmonday" && $day->dayOfWeek != 1) {
        //     return false;
        // }

        // // cut off time has been reached
        // if ($this->cut_off) {
        //     // find the difference between cut off and openning time
        //     // if delivery day is today, then apply that difference to
        //     // openning time on delivery day and compare the result to now 
        //     $cut_off = Carbon::parse($this->cut_off, $timezone);

        //     $diff_minutes = $opening_time->diffInMinutes($cut_off);

        //     $true_cut_off_time = $day->copy()->subMinutes($diff_minutes);

        //     if ($true_cut_off_time->lessThanOrEqualTo($now)) {
        //         return false;
        //     }

        // }

        // order limit has been reached
        $orders = $this->orders()->where("delivery_date", $day->format("Y-m-d"))->count();
        $orders_limit = env("MAX_DELIVERY_TIME_DELIVERIES");

        if ($orders >= $orders_limit) {
            return false;
        }

        // if ($day->greaterThanOrEqualTo($tomorrow) && self::$with_extended_lead_time == false) {
        //     return true;
        // }

        // $to = $day->addHours($this->to->hour)->addMinutes($this->to->minute);

        if (self::$with_extended_lead_time == true) {
            return $day->subHours(24)->greaterThanOrEqualTo($now);
        }

        if (self::$with_extended_lead_time == false) {
            return $day->subHours(6)->greaterThanOrEqualTo($now);
        }

    }

    public function is_vailable_for($date)
    {

        // if (strtotime($date) === false) {
        //     return false;
        // }

        $day = Carbon::parse($date);

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();


        // if ($day->lessThan($today) || $day->isFriday() || $day->isSunday() || $day->isMonday()) {
        //     return false;
        // }

        // if ($day->greaterThanOrEqualTo($tomorrow)) {
        //     return true;
        // }

        $now = Carbon::now('Asia/Dubai');

        return $this->to->subHours(6)->greaterThanOrEqualTo($now);

    }

    public function toArray()
    {

        $attributes = parent::toArray();
        $attributes['to'] = $attributes['to']->format('H:i');
        $attributes['from'] = $attributes['from']->format('H:i');

        return $attributes;

    }

    public static function all($for = null, $with_extended_lead_time = false, $cart_type = null)
    {
        static::$for = $for;
        static::$with_extended_lead_time = $with_extended_lead_time;
        static::$cart_type = $cart_type;

        return parent::whereNull("deleted_at")->orderBy("from", "asc")->get();

    }

}
