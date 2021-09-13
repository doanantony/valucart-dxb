<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Systemsettings extends Model
{	
	protected $table = 'system_settings';

    protected $visible=[
    	'android_version',
    	'ios_version'
    ];
}
