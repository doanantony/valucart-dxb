<?php

namespace App\Models;
use Hashids;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    
    protected $table = "banners";

    protected $fillable = [
        "position",
        "name",
        "landscape",
        "portrait",
        "href",
        "resource_type",
        "resource_identifier",
        "published_at",
        "order",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    protected $visible = [
        "id",
        "position",
        "name",
        "landscape",
        "portrait",
        "href",
        "resource_type",
        "resource_identifier",
        "published_at",
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        "published_at",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public static $positions = [
        'home_banners',
        'Popular_Department_1',
        'Popular_Department_2',
        'VC_Certified'
    ];

    public static $resource_types = [
        "product",
        "bundle",
        "product_department",
        "product_category",
        "product_sub_category",
        "product_brand",
        "bundle_category",
    ];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        
        $attributes = parent::toArray();

        $attributes["landscape"] = $this->landscape;
        $attributes["portrait"] = $this->portrait;
        $attributes['resource_identifier'] = Hashids::encode($this->resource_identifier);
        
        return $attributes;

    }

    //  public function toArray()
    // {
        
    //     $attributes = parent::toArray();
    //     $attributes['id'] = Hashids::encode($this->id);

    //     return $attributes;

    // }
    
}
