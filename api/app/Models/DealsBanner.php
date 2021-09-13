<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class DealsBanner extends Model
{

	protected $table = 'deals_banners';

    protected $visible=[
		"id",
		"type",
		"vendor_id",
		"href",
		"icon",
		"rating",
		"label1",
    	"label2",
    	"image",
		"published",
	];

	public function department()
    {
        return $this->hasOne("App\Models\Departments", "id", "vendor_id");
	}


	public function toArray()
    {
        
        $attributes = parent::toArray();
        return $attributes;

    }

}
