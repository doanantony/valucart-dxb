<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Hashids;

class HomeBanner extends Model
{
    
    protected $table = 'home_banners';

    protected $fillable = [
        'name',
        'landscape',
        'portrait',
        'href',
        'published_at',
    ];

    protected $visible = [
        'id',
        'name',
        'landscape',
        'portrait',
        'href',
        'published_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
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

        $attributes['landscape'] = url('/img/banners/' . $this->landscape);
        $attributes['portrait'] = url('/img/banners/' . $this->portrait);

        return $attributes;

    }

}
