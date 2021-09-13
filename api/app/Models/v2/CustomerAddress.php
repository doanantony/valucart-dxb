<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    
    protected $table = "customer_addresses";

    protected $fillable = [
        'customer_id',
        'area_id',
        'location_type',
        'building',
        'street',
        'unit_number',
        'phone_number',
        'landmark',
        'name'
    ];

    protected $visible = [
        'id',
        'customer_id',
        'area_id',
        'location_type',
        'building',
        'street',
        'unit_number',
        'phone_number',
        'landmark',
        'name',
        'state',
        'area',
        'tag'
    ];

    protected $appends = [
        'state',
        'area',
        'tag'
    ];

    protected function getTagAttribute()
    {
        return $this->name;
    }
    
    protected function getStateAttribute()
    {
        return $this->area->state;
    }
    
    protected function getAreaAttribute()
    {
        return $this->area()->first();
    }

    public function area()
    {
        return $this->belongsTo("App\Models\Area");
    }

    protected function needs_label($string = "")
    {

        $match = preg_match("/villa|vila|apartment|apatment|suite|suit|office|room|street|stret/i", $string);

        return $match === 1 ? false : true ;

    }

    public function __toString()
    {

        $address = "";
        $address .= $this->unit_number;
        $address .= $this->unit_number && $this->needs_label($this->unit_number) ? $this->unit_number == "apartment" ? " (Apartment/Suite)" : " (Villa)" : "";
        $address .= $this->apartment ? $this->apartment : "";
        $address .= $this->floor ? " {$this->floor}" : "";
        $address .= $this->building ? " {$this->building}" : "";
        $address .= $this->street ? " {$this->street}" : "";
        $address .= $this->street && $this->needs_label($this->street) ? " (Street)" : "";
        $address .= $this->area ? ", {$this->area->name}" : "";
        $address .= $this->area && $this->area->state ? " {$this->area->state->name}." : "";
        $address .= $this->landmark ? " Near: {$this->landmark}" : "";
        $address .= $this->landmark ? " Tel: {$this->phone_number}" : "";

        return $address;

    }

    public function toArray()
    {
        
        $attributes = parent::toArray();

        if ($this->location_type == "apartment") {
            $attributes["apartment_number"] = $attributes["unit_number"];
            $attributes["villa_number"] = "";
        } else {
            $attributes["apartment_number"] = "";
            $attributes["villa_number"] = $attributes["unit_number"];
        }

        $attributes["tag"] = $attributes["name"];
    
        unset($attributes["unit_number"]);

        foreach ($attributes as $key => $value) {
            $attributes[$key] = is_null($value) ? "" : $value;
        }
        
        return $attributes;

    }

}
