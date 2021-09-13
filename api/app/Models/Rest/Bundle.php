<?php


namespace App\Models\Rest;

use Hashids;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{

    protected $table = 'bundles';

	protected $fillable = [
    	'name',
    	'category_id',
        'description',
        'valucart_price',
        'is_popular',
    ];

    protected $visible = [
    	'id',
        'category',
        'name',
        'description',
        'item_count',
        'price',
        'valucart_price',
        'percentage_discount',
    	'is_popular',
        'images',
        'thumbnail',
        'inventory',
        'products'
    ];

    protected $appends = [
        'item_count',
        'price',
        'percentage_discount',
        'thumbnail',
        'inventory',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_popular' => 'boolean',
    ];

    protected $price = null;

    public function products()
    {

        return $this->belongsToMany(
            'App\Models\Product',
            'bundles_products',
            'bundle_id',
            'product_id'
        );

    }

    public function get_products_with_quantity()
    {

        $products = $this->products()->with('images','packaging_quantity_unit')->withPivot('quantity')->get();

        return $products->map(function($product, $key) {

            return [
                'quantity' => $product->pivot->quantity,
                'data' => $product
            ];

        });

    }

    public function get_products_with_alternatives()
    {

        $products = $this->products()->with('images','packaging_quantity_unit')->withPivot(['quantity', 'id'])->get();

        return $products->map(function($product, $key) {

            $alternatives = Product::query()
                ->join('bundles_products_alternatives', function($join) use ($product) {
                    $join->on('products.id', '=', 'bundles_products_alternatives.product_id')
                            ->where('bundles_products_alternatives.bundles_products_id', '=', $product->pivot->id);
                })->with('images','packaging_quantity_unit')
                ->get(['products.*', 'bundles_products_alternatives.quantity']);

            

            $alternatives = $alternatives->map(function($product) {
                return [
                    'quantity' => $product->quantity,
                    'data' => $product,
                ];
            });

            return [
                'quantity' => $product->pivot->quantity,
                'data' => $product,
                'alternatives' => $alternatives
            ];

        });

    }

    public function category()
    {

        return $this->hasOne('App\Models\BundlesCategories', 'id', 'category_id');

    }

    public function getItemCountAttribute()
    {
        return (int) $this->products->count();
    }

    public function getPriceAttribute()
    {

        if (!is_null($this->price)) {
            return (float) $this->price;
        }

        $products = $this->products()->withPivot('quantity')->get();

        $price = 0;

        foreach ($products as $product) {
            $price = $price + ($product->valucart_price * $product->pivot->quantity);
        }

        $this->price = (float) round($price, 2);

        return $this->price;

    }

    public function getPercentageDiscountAttribute()
    {

        $price = $this->price;
        $valucart_price = $this->valucart_price;

        if ($valucart_price >= $price) {
            return 0;
        }

        $percentage_discount = (($price - $valucart_price) * 100) / $price;
        
        return round($percentage_discount, 2);

    }

    public function images()
    {   
        return $this->hasMany('App\Models\BundleImage', 'bundle_id');
    }
    
    public function get_image_urls()
    {

        $images = $this->images->filter(function($image) {
            return $image->is_thumb != 1;
        });

        return $this->images->map(function($image) {
            return url('/img/bundles/' . Hashids::encode($this->id) . '/' . $image->path);
        });

    }

    public function getThumbnailAttribute()
    {
        
        $thumbnail = $this->images->filter(function($image) {
            return $image->is_thumb == 1;
        })->first();

        if (!is_null($thumbnail)) {
            return url('/img/bundles/' . Hashids::encode($this->id) . '/' . $thumbnail->path);
        }

        return '';

    }

    public function getInventoryAttribute()
	{

        return 10;

        
//////////////// we have to check this code////////////////////
        // $inventory = [];

        // foreach($this->products as $product) {
        //     $inventory[] = $product->inventory;
        // }
        
        // return (count($inventory) > 0) ? min($inventory) : 0;
		
	}

    public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);

        return [
            'id' => Hashids::encode($this->id),
            'category' => $this->category,
            'name' => $this->name,
            'description' => $this->description,
            'item_count' => $this->item_count,
            'maximum_selling_price' => $this->price,
            'valucart_price' => $this->valucart_price,
            'percentage_discount' => $this->percentage_discount,
            'is_popular' => (boolean) $this->is_popular,
            'thumbnail' => $this->thumbnail,
            'inventory' => $this->inventory,
            'products' => $this->get_products_with_alternatives(),
        ];

        return $attributes;

    }
    
}
