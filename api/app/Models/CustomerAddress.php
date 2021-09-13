<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    
    protected $fillable = [
        'customer_id',
        'name',
        'area_id',
        'street',
        'building',
        'floor',
        'apartment',
        'landmark',
        'notes',
        'phone_number'
    ];

    protected $visible = [
        'id',
        'name',
        'state',
        'area',
        'street',
        'building',
        'floor',
        'apartment',
        'landmark',
        'notes',
        'phone_number'
    ];

    protected $appends = [
        'state',
        'area',
    ];

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
        return $this->belongsTo('App\Models\Area');
    }

    public function __toString()
    {
        $address = "";
        $address .= $this->apartment ? $this->apartment : "";
        $address .= $this->floor ? " {$this->floor}" : "";
        $address .= $this->building ? " {$this->building}" : "";
        $address .= $this->street ? " {$this->street}" : "";
        $address .= $this->street ? " (Street)" : "";
        $address .= $this->area ? ", {$this->area->name}" : "";
        $address .= $this->area && $this->area->state ? " {$this->area->state->name}." : ".";
        $address .= $this->landmark ? " Near: {$this->landmark}" : "";
        $address .= $this->landmark ? " Tel: {$this->phone_number}" : "";

        return $address;
        
    }

}
