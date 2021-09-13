<?php

namespace App\Models\Rest;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
   protected $table = "subcategories";

	protected $fillable=[
    	"name",
    	"category_id"
    ];

    protected $visible=[
      "id", 
      "name",
      "category_id"
	];
	
}
