<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    
	protected $table = 'communities';

	protected $fillable = [
    	'name',
    	'published'
    ];

    protected $visible = [
        'id',
        'name',
        'icon'
    ];

    protected $appends = [
        'icon'
    ];

    public function getIconAttribute()
    {
        return url('/img/communities/' . $this->name . '.png');
    }

    public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);

        return $attributes;

    }

}	
