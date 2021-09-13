<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CustomerBundle extends Model
{   
    protected $table = 'customer_bundles'; 

    protected $fillable = [
        'name',
        'description',
        'customer_id',   
        'vendor_id', 
        'status'
    ];

    protected $price = null;
    protected $discounted_price = null;

    protected $visible = [
        'id',
        'name',
        'description',
        'customer_id',
        'created_at',
        'maximum_selling_price',
        'discount',
        'valucart_price',
        'delivery_charge',
        'total_price',
        'total_items',
        'inventory',
        'thumbnail',
        'products',
        'can_checkout',
        'can_checkout_str',
    ];

    protected $appends = [
        'maximum_selling_price',
        'discount',
        'valucart_price',
        'delivery_charge',
        'total_price',
        'total_items',
        'inventory',
        'thumbnail',
        'products',
        'can_checkout',
        'can_checkout_str',
    ];

    public function getCanCheckoutAttribute()
    {
        return $this->valucart_price >= (integer) env("MEAT_MONDAY_MINIMUM_ORDER_VALUE");
    }

    public function getCanCheckoutStrAttribute()
    {
        return !$this->can_checkout ? "Order should be at least AED" . (integer) env("MEAT_MONDAY_MINIMUM_ORDER_VALUE") : "";
    }

    public function getThumbnailAttribute()
    {   
        $full_path = 'https://s3.eu-central-1.amazonaws.com/assets.valucart.com/customerbundle/ic_add_byob.png';

        return url($full_path);
        
    }

    public function get_price()
    {

        if (!is_null($this->price)) {
            return $this->price;
        }

        $price = 0;

        $products = $this->products;

        foreach ($products as $product) {

            $price += $product['product']['maximum_selling_price'] * $product['quantity'];

        }

        return $this->price = round($price, 2);

    }

    public function get_discounted_price()
    {
        return $this->valucart_price;
    }

    public function getMaximumSellingPriceAttribute()
    {
        return $this->get_price();
    }

    public function getDiscountAttribute()
    {
        return round($this->get_price() - $this->get_discounted_price(),2);
    }

    public function getValucartPriceAttribute()
    {

        $valucart_price = 0;

        foreach ($this->products as $product) {
            $valucart_price += $product['product']['valucart_price'] * $product['quantity'];
        }

        return round($valucart_price, 2);

    }

    public function getTotalPriceAttribute()
    {
        return round($this->valucart_price + $this->delivery_charge, 2);
    }


    public function getTotalItemsAttribute()
    {

        $count = 0;

        $products = $this->products;

        foreach ($products as $product) {

            $count++;

        }
        return $count;

    }


    public function getProductsAttribute()
    {
        $products = $this->products()->with('images', 'packaging_quantity_unit')->get();

        return $products->map(function($product) {
            return [
                'quantity' => $product->pivot->quantity,
                'product' => $product,
            ];
        });

    }
    
    public function products()
    {

        return $this->belongsToMany(
            'App\Models\Product',
            'customer_bundles_products',
            'bundle_id',
            'product_id'
        )->withPivot('quantity');

    }
    
    public function getInventoryAttribute()
    {

        $inventory = [];

        foreach($this->products as $product) {
            $inventory[] = $product['product']->inventory;
        }

        return (count($inventory) > 0) ? min($inventory) : 0;
        
    }

    public function getDeliveryChargeAttribute()
    {

        $free_delivery_minimum_order = env("MEAT_MONDAY_FREE_DELIVERY_MINIMUM_ORDER");
        $delivery_charge = env("DELIVERY_CHARGE");
        $vat = env("VAT");

        $delivery_charge = ($this->valucart_price < $free_delivery_minimum_order) ? $delivery_charge : 0;

        if ($delivery_charge > 0) {
            $vat_amount = ($vat / 100) * $delivery_charge;
            $delivery_charge = $delivery_charge + $vat_amount;
        }

        return round(($delivery_charge * 1.0), 2);

    }

    public static function exists($cart_id)
    {  
        return is_numeric($cart_id);
    }
    
    public function toArray()
    {
        
        $attributes = parent::toArray();

        $attributes['products'] = $this->products;

        return $attributes;

    }

}
