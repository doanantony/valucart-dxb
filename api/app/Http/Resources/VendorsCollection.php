<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorsCollection extends JsonResource
{   

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'vendor_id'            => $this->vendor_id,
            'vendor_name'          => $this->name,
            'vendor_image'         => $this->image,
            'vendor_short_name'    => $this->short_name,
            'minimum_order'        => $this->minimum_order,
            'delivery_charge'      => $this->delivery_charge,
            'delivery_time'        =>'9AM - 6PM'
        ];   
    }
}
