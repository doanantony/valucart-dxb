<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    
    use SoftDeletes;

    protected $visible = [
        'id',
        'name',
        'state_id'
    ];

    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

}
