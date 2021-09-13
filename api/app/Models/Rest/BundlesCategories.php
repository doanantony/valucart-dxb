<?php

namespace App\Models\Rest;

use Illuminate\Database\Eloquent\Model;

class BundlesCategories extends Model
{
   protected $fillable = [
    	"name"
    ];

    protected $visible = [
        "id",
        "name",
    ];
    
}
