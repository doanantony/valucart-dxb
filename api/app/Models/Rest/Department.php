<?php

namespace App\Models\Rest;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $visible = [
        'id',
        'name',
        'icon'
	];
    

    public function getIconAttribute()
    {
        return url('/img/departments/' . $this->name . '.png');
    }

}
