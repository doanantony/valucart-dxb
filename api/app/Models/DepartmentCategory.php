<?php

namespace App\Models;

use App\Models\Rest\Department;
use Hashids;
use Illuminate\Database\Eloquent\Model;

class DepartmentCategory extends Model
{
    
    protected $table = 'departments_categories';

    protected $fillable=[
        'name',
    ];

    protected $visible=[
        'name',
        'id',
        'icon',
        'description',
        'status'
    ];
    
    /**
     * Convert the model instance to an array.
     *
     * @return array
     */

    public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);
        return $attributes;

    }

}
