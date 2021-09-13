<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'name',
        'icon',
        'is_popular',
    ];

    protected $visible = [
        'id',
        'name',
        'icon',
        'image',
        'email',
        'longitude',
        'latitude',
    ];
    
    // public function departments()
    // {
    //     return $this->hasMany('App\Models\Departments');
    // }

    // public function getIconAttribute()
    // {
    //     //echo "<pre>";print_r($this);die;

    //     return url('/img/departments/' . $this->icon . '.png');
    //    // return url('/img/departments_icons/' . Hashids::encode($this->id) . '/' . $this->icon);
    // }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    
    public function toArray()
    {
        
        $attributes = parent::toArray();
            $attributes['id'] = $this->id;

        // $attributes['id'] = Hashids::encode($this->id);
        $attributes["icon"] =  env("IMG_URL")."/". $this->icon;

        // if($attributes["image"]){
        //     $attributes["image"] = env("IMG_URL")."/". $this->image;
        // }
        
        $attributes['name'] = $this->name;
        return $attributes;

    }

}
