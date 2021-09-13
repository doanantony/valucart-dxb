<?php

namespace App\Models\Rest;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $visible = [
        'id',
        'name2',
        'iso2',
        'iso3'
    ];

}
