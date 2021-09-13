<?php

namespace App\Models\Rest;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
	protected $table = "categories";

	protected $fillable = [
    	"name",
        "department_id"
    ];

    protected $visible = [
        "id",
        "name",
        "department_id"
	];
    
    public function subcategories()
    {
        return $this->hasMany("App\Models\Subcategory");
    }

}
