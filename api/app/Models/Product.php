<?php

namespace App\Models;

use Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

	use SoftDeletes;

	protected $fillable=[
    	"name",
		"sku",
		"department_id",
		"category_id",
    	"subcategory_id",
		"brand_id",
		"type",
    	"description",
    	"packaging_quantity",
		"packaging_quantity_unit_id",
		"packaging_type",
    	"maximum_selling_price",
    	"valucart_price",
    	"is_admin_bundlable",
    	"admin_bundle_discount",
		"is_customer_bundlable",
		"customer_bundle_discount",
		"minimum_inventory",
		"published",
		"is_bulk",
		"bulk_quantity",
		"is_featured",
		"is_offer",
    ];

    protected $visible=[
		"id",
		"sku",
		"name",
		"brand",
		"department",
		"category",
		"subcategory",
    	"description",
    	"packaging_quantity",
		"maximum_selling_price",
		"percentage_discount",
    	"valucart_price",
        "packaging_quantity_unit",
		"images",
		"thumbnail",
		"is_bulk",
		"is_offer",
		"bulk_quantity",
		"inventory"
	];

	protected $casts = [
		"is_bulk" => "boolean",
		"is_offer" => "boolean",
    ];

	protected $appends = [
		"percentage_discount",
		"inventory",
	];

	public function brand()
    {
        return $this->hasOne("App\Models\Brand", "id", "brand_id");
	}
	
	public function department()
    {
        return $this->hasOne("App\Models\Departments", "id", "department_id");
	}
	
	public function category()
    {
        return $this->hasOne("App\Models\Category", "id", "category_id");
	}
	
	public function subcategory()
    {
        return $this->hasOne("App\Models\Subcategory", "id", "subcategory_id");
    }

    public function packaging_quantity_unit()
    {
        return $this->hasOne("App\Models\MetricUnit", "id", "packaging_quantity_unit_id");
    }

    public function communities()
    {
        return $this->belongsToMany("App\Models\Community", "products_communities");
    }

	public function images()
    {
        return $this->hasMany("App\Models\ProductImage");
	}

	public function vendors()
	{
		return $this->belongsToMany("App\Models\Vendor", "products_vendors");
	}

	public function getMaximumSellingPriceAttribute($value)
	{
		return round($value, 2);
	}

	public function getValucartPriceAttribute($value)
	{
		return round($value, 2);
	}

	public function getInventoryAttribute()
	{
		return 10;
		
///////////////// we have to check this code////////////////////
		//return $this->vendors()->withPivot("inventory")->get()->reduce(function($carry, $vendor) {
		//	return $carry + $vendor->pivot->inventory;
		//}, 0);
		
	}

	public function getImagesAttribute()
    {
        return $this->images();
	}

	public function getPercentageDiscountAttribute()
	{

		$valucart_price = $this->valucart_price;
		$maximum_selling_price = $this->maximum_selling_price;

        if ($valucart_price >= $maximum_selling_price) {
            return 0;
        }

        $percentage_discount = (($maximum_selling_price - $valucart_price) * 100) / $maximum_selling_price;
        
		return round($percentage_discount, 2);
		
	}

    public function toArray()
    {
        
        $attributes = parent::toArray();
		$attributes["id"] = Hashids::encode($this->id);

		$attributes["thumbnail"] = "";
		
		if (isset($attributes["images"])
			&& is_array($attributes["images"])
			&& !empty($attributes["images"])) {

			foreach ($attributes["images"] as $key => $image) {

				if (!!$image["is_thumb"]) {
					$attributes["thumbnail"] = $image["path"];
					array_splice($attributes["images"], $key, 1);
					break;
				}

				$image_lowercase = strtolower($image["path"]);

				if (strpos($image_lowercase, "thumb") !== false) {
					$attributes["thumbnail"] = $image["path"];
					array_splice($attributes["images"], $key, 1);
					break;
				}
				
			}
			
			$attributes["images"] = array_map(function($image) {
					$path = $image["path"];
					return url($path);
				},
				$attributes["images"]
			);

		}

		if (!array_key_exists("packaging_quantity_unit", $attributes) || is_null($attributes["packaging_quantity_unit"])) {

			$attributes["packaging_quantity_unit"] = new \App\Models\MetricUnit([
				"id" => "",
				"name" => "",
				"symbol" => ""
			]);

		}

		return $attributes;

	}

}
