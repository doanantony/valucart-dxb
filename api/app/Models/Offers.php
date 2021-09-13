<?php

namespace App\Models;
use Hashids;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    protected $table = 'offers';

	protected $fillable = [
    	'code',
    	'description',
    	'color_code',
    	'status',

    ];

    protected $visible = [
        'id',
        'code',
        'title',
    	'description',
    	'color_code',
    	'image'
    ];

    protected $appends = [
        'image	'
    ];

    // public function getImageAttribute()
    // {
    //     return url('/img/offericons/' . $this->image . '.png');
    // }

    public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);
        $attributes["image"] = url("/img/offericons/" .Hashids::encode($this->id) . "/" . $this->image);
        return $attributes;

    }
}
