<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentCategoryDepartment extends Model
{
    protected $table = 'vendor_location';

    // protected $hidden = [
    //     'email',
    //     'icon',
    //     'longitude',
    //     'latitude',
    //     'user_type_id'
    // ];

    public function settings()
    {
        return $this->hasOne("App\Models\Settings", "vendor_id", "id");

	}


}
