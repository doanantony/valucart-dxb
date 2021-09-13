<?php

namespace App\Models\Rest;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{

    protected $visible = [
		"id",
		"name",
    	"short_name",
        "image"
	];
	
    protected $appends = [
        "image"
    ];

    public function getImageAttribute()
    {
        $full_path = "img/vendors/" . Hashids::encode($this->id) . "/" .$this->path;

        return $this->path ? url($full_path) : "" ;
        
    }

}
