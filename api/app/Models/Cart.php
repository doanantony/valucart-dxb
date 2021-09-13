<?php

namespace App\Models;

use BadMethodCallException;
use InvalidArgumentException;
use App\Exceptions\CartException;

use Hashids;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Cart
{

    const ITEM_PRODUCT = 0;

    const ITEM_BUNDLE = 1;

    const ITEM_CUSTOMER_BUNDLE = 2;

    protected $id = null;

    protected $coupon = null;

    protected $customer = null;

    protected $discount = null;

    protected $items = null;

    public static $items_types = [
        0 => "product",
        1 => "bundle",
        2 => "customer_bundle"
    ];
    
    protected function __construct($cart, $items)
    {

        $this->id = $cart->id;
        $this->customer = $cart->customer_id;
        $this->coupon = $cart->coupon;
        $this->discount = $cart->discount;
        
        $this->items = $items;

    }

    public static function find($id)
    {
        
        $id = (string) $id;
        
        $cart = DB::table("carts")
            ->where("id", $id)
            ->orWhere("customer_id", (int) $id)
            ->first();

        return is_null($cart) ? null : new static($cart, static::fetch_items($cart->id)) ;

    }

    public static function create($customer = null)
    {

        $id = md5((string) \Str::uuid());

        $cart_data = [
            "id" => $id,
            "customer_id" => $customer,
            "coupon" => null,
            "discount" => 0
        ];

        DB::table("carts")->insert($cart_data);
        
        $cart = (object) $cart_data;
        $items = collect([]);

        return new static($cart, $items);

    }

    public function save()
    {

        return DB::table("carts")
            ->where("id", $this->id)
            ->update([
                "customer_id" => $this->customer,
                "coupon" => $this->coupon,
                "discount" => $this->discount
            ]);

    }

    public function reload()
    {
       return static::find($this->id);
    }

    protected function item_count()
    {
        
        return $this->items->reduce(function ($carry, $item) {
            return $carry + $item->count();
        }, 0);

    }

    public static function exists($id)
    {
        return DB::table("carts")->where("id", $id)->exists();
    }

    public static function clear_cart($cart_id)
    {
        DB::table("cart_items")->where("cart_id", $cart_id)->delete();
    }

    public function clear()
    {
        static::clear_cart($this->id);
    }

    protected static function fetch_items($cart_id)
    {

        $items = DB::table("cart_items")->where("cart_id", $cart_id)->get();

        $grouped_items = $items->mapToGroups(function($item, $key) {
            return [$item->item_type . "s" => $item];
        });
    
        return $grouped_items->map(function($group, $key) use($items) {
            
            $item_ids = $group->map(function($item) {
                return $item->item_id;
            });

            if ($key == "products") {

                $models = Product::with("images","packaging_quantity_unit", "brand", "subcategory")
                    ->whereIn("id", $item_ids->toArray())
                    ->get();
                
            } else if ($key == "bundles") {

                $models = Bundles::whereIn("id", $item_ids->toArray())->get();

                $models = $models->map(function($model) use($items) {
                    
                    $cart_item = $items->where("item_type", "bundle")->where("item_id", $model->id)->first();
                    $product_list = explode(",", $cart_item->product_list);

                    $bundle_products_ids = DB::table("bundles_products")
                        ->where("bundle_id", $model->id)
                        ->get(["id"])
                        ->map(function($x) {
                            return $x->id;
                        })
                        ->toArray();
                    
                    $q1 = DB::table("bundles_products")
                        ->select(["product_id", "quantity"])
                        ->where("bundle_id", $model->id)
                        ->whereIn("product_id", $product_list);

                    $product_list_with_quantity = DB::table("bundles_products_alternatives")
                        ->whereIn("bundles_products_id", $bundle_products_ids)
                        ->whereIn("product_id", $product_list)
                        ->union($q1)
                        ->get(["product_id", "quantity"]);
                    
                    $model->selected_products_list = Product::with("images","packaging_quantity_unit", "brand", "subcategory")
                        ->whereIn("id", $product_list)
                        ->get()
                        ->map(function($product) use($product_list_with_quantity) {

                        $x = $product_list_with_quantity->firstWhere("product_id", $product->id);
                        
                        if (!is_null($x)) {
                            $product->bundled_quantity = $x->quantity;
                        } else {
                            $product->bundled_quantity = 1;
                        }

                        return $product;
                        
                    });
                    
                    return $model;

                });
                
            } else if ($key == "customer_bundles") {

                $models = CustomerBundle::whereIn("id", $item_ids->toArray())->get();
                
            }
    
            return $group->map(function($item, $key) use($models) {
                return [
                    "quantity" => $item->quantity,
                    "item" => $models->firstWhere("id", $item->item_id),
                    "allow_alternatives" => !!$item->allow_alternatives,
                ];
            });
    
        });

    }

    protected function price()
    {
    
        // Get products total price
        $total_products_price = 0;
        
        $products = $this->items->get("products", []);
        
        foreach ($products as $product) {
            
            $quantity = Arr::get($product, "quantity");
            $product = Arr::get($product, "item");
            
            $total_products_price += $product->valucart_price * $quantity;

        }

        // Get bundles total price

        $total_bundles_price = 0;

        $bundles = $this->items->get("bundles", []);

        foreach ($bundles as $bundle) {
            
            $quantity = Arr::get($bundle, "quantity");
            $bundle = Arr::get($bundle, "item");
            
            $total_bundles_price += $bundle->valucart_price * $quantity;

        }

        // Get customer bundle
        $total_customer_bundles_price = 0;

        $customer_bundles = $this->items->get("customer_bundles", []);

        foreach ($customer_bundles as $customer_bundle) {

            $quantity = Arr::get($customer_bundle, "quantity");
            $bundle = Arr::get($customer_bundle, "item");

            $total_customer_bundles_price += $bundle->get_discounted_price() * $quantity;

        }
        
        $price = $total_products_price + $total_bundles_price + $total_customer_bundles_price;

        return round($price, 2);

    }

    protected function discounted_price()
    {

        $price = $this->price();

        if (!$this->discount) {
            return $price;
        }

        if (preg_match("/%$/", $this->discount)) {
            $discount =  ((float) $this->discount / 100) * $price;
            $discounted_price = $price -  $discount;
            return round($discounted_price, 2);
        }

        if (preg_match("/AED$/i", $this->discount)) {
            $discounted_price =  $price - (float) $this->discount;
            return round($discounted_price, 2);
        }

    }

    protected function delivery_charge()
    {

        if ($this->item_count() <= 0) {
            return 0;
        }

        $free_delivery_minimum_order = env("FREE_DELIVERY_MINIMUM_ORDER");
        $delivery_charge = env("DELIVERY_CHARGE");
        $vat = env("VAT");

        $discounted_price = (float) $this->discounted_price();

        $delivery_charge = ($discounted_price < $free_delivery_minimum_order) ? $delivery_charge : 0;

        if ($delivery_charge > 0) {
            $vat_amount = ($vat / 100) * $delivery_charge;
            $delivery_charge = $delivery_charge + $vat_amount;
        }

        return round(($delivery_charge * 1.0), 2);

    }

    public function has($item)
    {

        $item_class = get_class($item);
        $item_class = explode("\\", $item_class);
        $item_class = end($item_class);

        switch ($item_class) {

            case "Product":
                $item_type = "products";
                break;
            
            case "Bundles":
                $item_type = "bundles";
                break;
            
            case "CustomerBundle":
                $item_type = "customer_bundles";
                break;

            default:
                $item_type = null;

        }

        if (is_null($item_type)) {
            throw new InvalidArgumentException("Invalid argument 1 passed Cart::add");
        }

        return (boolean) $this->items->get($item_type, collect([]))->firstWhere("item.id", $item->id);
        
    }

    public function item($item)
    {

        $item_class = get_class($item);
        $item_class = explode("\\", $item_class);
        $item_class = end($item_class);

        switch ($item_class) {

            case "Product":
                $item_type = "products";
                break;
            
            case "Bundles":
                $item_type = "bundles";
                break;
            
            case "CustomerBundle":
                $item_type = "customer_bundles";
                break;

            default:
                $item_type = null;

        }

        if (is_null($item_type)) {
            throw new InvalidArgumentException("Invalid argument 1 passed Cart::add");
        }

        return $this->items->get($item_type)->firstWhere("item.id", $item->id);

    }

    public function can_checkout()
    {
        $price = $this->discounted_price();
        $minimum_order = (integer) env("MINIMUM_ORDER_VALUE");

        return $price >= $minimum_order;

    }

    public function can_checkout_str()
    {
        return !$this->can_checkout ? "Order should be at least AED" . (integer) env("MINIMUM_ORDER_VALUE") : "";
    }

    protected function http_response()
    {
    
        $empty_colletion = collect([]);
        $price = $this->price();
        $discounted_price = $this->discounted_price();
        $delivery_charge = $this->delivery_charge();
        $total_price = $discounted_price + $delivery_charge;
        $delivery_charge = ($delivery_charge != 0) ? $delivery_charge . " AED" : "FREE";

        $coupon = !$this->coupon ? "" : $this->coupon;

        return [
            "id" => $this->id,
            "customer" => $this->customer,
            "price" => (float) $price,
            "coupon" => $coupon,
            "discount" => ($this->discount == 0) ? "-" : $this->discount,
            "discounted_price" => (float) $discounted_price,
            "delivery_charge" => $delivery_charge,
            "total_price" => (float) round($total_price, 2),
            "item_count" => $this->item_count(),
            "products" => $this->items->get("products", $empty_colletion),
            "bundles" => $this->items->get("bundles", $empty_colletion),
            "customer_bundles" => $this->items->get("customer_bundles", $empty_colletion),
            "can_checkout" => $this->can_checkout,
            "can_checkout_str" => $this->can_checkout_str
        ];

    }

    public function get_snapshot()
    {

        $bundles = $this->items->get("bundles", []);

        $empty_colletion = collect([]);
        $price = $this->price();

        $discounted_price = $this->discounted_price();
        $delivery_charge = $this->delivery_charge();
        $total_price = $discounted_price + $delivery_charge;

        $discounted_price = ($discounted_price != 0) ? $discounted_price . " AED" : "0";
        $delivery_charge = ($delivery_charge != 0) ? $delivery_charge . " AED" : "FREE";
        $total_price = ($total_price != 0) ? round($total_price, 2) . " AED" : "0";
        $price = ($price != 0) ? $price . " AED" : "0";

        $coupon = !$this->coupon ? "" : $this->coupon;

        return [
            "id" => $this->id,
            "customer" => $this->customer,
            "price" => $price,
            "coupon" => $coupon,
            "discount" => (string) $this->discount,
            "discounted_price" => $discounted_price . " AED",
            "delivery_charge" => $delivery_charge,
            "total_price" => $total_price,
            "item_count" => $this->item_count(),
            "products" => $this->items->get("products", $empty_colletion),
            "bundles" => $this->items->get("bundles", $empty_colletion),
            "customer_bundles" => $this->items->get("customer_bundles", $empty_colletion),
        ];

    }

    public function add($item, $quantity = 1, $allow_alternatives = true, $products_list = [])
    {

        $item_class = get_class($item);
        $item_class = explode("\\", $item_class);
        $item_class = end($item_class);

        switch ($item_class) {

            case "Product":
                $item_type = "product";
                break;
            
            case "Bundles":
                $item_type = "bundle";
                break;
            
            case "CustomerBundle":
                $item_type = "customer_bundle";
                break;

            default:
                $item_type = null;

        }

        if (is_null($item_type)) {
            throw new InvalidArgumentException("Invalid argument 1 passed Cart::add");
        }

        $quantity = (int) $quantity;

        $products_list = (is_array($products_list) && count($products_list) > 0) ? implode(",", $products_list) : null ;

        $sql = "IF(`quantity` + " . $quantity . " <= 10000000, `quantity` + " . $quantity . ", 10000000)";

        return (boolean) DB::table("cart_items")->updateOrInsert([
                "cart_id" => $this->id, 
                "item_id" => $item->id,
                "item_type" => $item_type,
                "allow_alternatives" => (($allow_alternatives == true || is_null($allow_alternatives)) && $item_type == "product") ? 1 : 0
            ], [
                "quantity" => DB::raw($sql),
                "product_list" => $products_list,
            ]);

    }

    public function update_allow_alternatives($item, $allow_alternatives) {

        $item_class = get_class($item);
        $item_class = explode("\\", $item_class);
        $item_class = end($item_class);

        switch ($item_class) {

            case "Product":
                $item_type = "product";
                break;

            default:
                $item_type = null;

        }

        if ($item_type == "product") {
            return (boolean) DB::table("cart_items")->where("item_id", $item->id)->update(["allow_alternatives" => (integer) $allow_alternatives]);
        }

        return false;

    }

    public function update($item, $products_list)
    {

        $item_class = get_class($item);
        $item_class = explode("\\", $item_class);
        $item_class = end($item_class);

        if ($item_class != "Bundle") {
            throw new InvalidArgumentException("Invalid argument 1 passed Cart::update");
        }

        $products_list = count($products_list) > 0 ? implode(",", $product_list) : null ;

        return (boolean) DB::table("cart_items")->where([
            "cart_id" => $this->id, 
            "item_id" => $item->id,
            "item_type" => "bundle",
        ])->update([
            "product_list" => $products_list
        ]);

    }

    public function reduce($item, $quantity = 1, $current_quantity = null)
    {
        
        $item_class = get_class($item);
        $item_class = explode("\\", $item_class);
        $item_class = end($item_class);

        switch ($item_class) {

            case "Product":
                $item_type = "product";
                break;
            
            case "Bundles":
                $item_type = "bundle";
                break;
            
            case "CustomerBundle":
                $item_type = "customer_bundle";
                break;

            default:
                $item_type = null;

        }

        if (is_null($item_type)) {
            throw new InvalidArgumentException("Invalid argument 1 passed Cart::reduce");
        }

        $quantity = (int) $quantity;

        $q = DB::table("cart_items")->where([
            "cart_id" => $this->id, 
            "item_id" => $item->id,
            "item_type" => $item_type,
        ]);
        
        $current_quantity = !is_null($current_quantity) ? $current_quantity : $q->first()->quantity ;

        if (($current_quantity - $quantity) <= 0) {

            return (boolean) $q->delete();

        } else {

            return (boolean) $q->update([
                "quantity" => ($current_quantity - $quantity),
            ]);

        }

    }

    public function remove($item)
    {
        
        $item_class = get_class($item);
        $item_class = explode("\\", $item_class);
        $item_class = end($item_class);

        switch ($item_class) {

            case "Product":
                $item_type = "product";
                break;
            
            case "Bundles":
                $item_type = "bundle";
                break;
            
            case "CustomerBundle":
                $item_type = "customer_bundle";
                break;

            default:
                $item_type = null;

        }

        if (is_null($item_type)) {
            throw new InvalidArgumentException("Invalid argument 1 passed Cart::remove");
        }

        return (boolean) DB::table("cart_items")->where([
                "cart_id" => $this->id, 
                "item_id" => $item->id,
                "item_type" => $item_type,
            ])
            ->delete();

    }

    public function merge($cart)
    {

        if (gettype($cart) == "string") {

            $merged = DB::table("cart_items")->where("cart_id", $cart)->update(["cart_id" => $this->id]);
            
            if ($merged >= 0) {
                DB::table("carts")->where("id", $cart)->delete();
            }
        
        } else if ($cart instanceof \App\Models\Cart) {

            $merged = DB::table("cart_items")->where("cart_id", $cart->id)->update(["cart_id" => $this->id]);

            if ($merged >= 0) {
                DB::table("carts")->where("id", $cart)->delete();
            }

        }
        
        return $this;
    }

    public function can_add($item, &$current_quantity = null)
    {

        $item_class = get_class($item);
        $item_class = explode("\\", $item_class);
        $item_class = end($item_class);

        switch ($item_class) {

            case "Product":
                $item_type = "product";
                break;
            
            case "Bundles":
                $item_type = "bundle";
                break;
            
            case "CustomerBundle":
                $item_type = "customer_bundle";
                break;

            default:
                $item_type = null;

        }

        if (is_null($item_type)) {
            throw new InvalidArgumentException("Invalid argument 1 passed Cart::can_add");
        }

        $cart_item = DB::table("cart_items")
            ->where("cart_id", $this->id)
            ->where("item_type", $item_type)
            ->where("item_id", $item->id)
            ->first();
        
        if (is_null($cart_item)) {
            return true;
        }
        
        $product_limit = env("CART_QUANTITY_LIMIT_PRODUCT");
        $offer_limit = env("CART_QUANTITY_LIMIT_OFFER_PRODUCT");
        $bundle_limit = env("CART_QUANTITY_LIMIT_BUNDLE");

        if ($item_type == "product" && $cart_item->quantity >= $product_limit) {
            throw new CartException('Maximum allowed quatity reached.');
        }

        // Check on offer item limit
        if ($item_type == "product" && $item->is_offer && $cart_item->quantity >= $offer_limit) {
            throw new CartException('Maximum allowed quatity for offer product reached.');
        }

        if ($item_type == "bundle" &&  $cart_item->quantity >= $bundle_limit) {
            throw new CartException('Maximum allowed quatity reached.');
        }

        $current_quantity = $cart_item->quantity;

        return true;

    }

    public function remove_coupon()
    {

        $this->coupon = null;
        $this->discount = 0;

        $this->save();
        
        return $this;

    }

    public function needs_extended_delivery_time()
    {

        $customer_bundles = $this->items->get("customer_bundles", collect([]));

        if ($customer_bundles->count() > 0) {
            return true;
        }

        $products = $this->items->get("products", collect([]));

        foreach($products as $product) {
            if ($product["item"]->is_bulk) {
                return true;
            }
        }

        return false;
        
    }

    public function __get($name)
    {

        if (property_exists($this, $name)) {
            
            return is_null($this->{$name}) ? "" : $this->{$name};

        } else if(method_exists($this, $name)) {

            return $this->{$name}();

        }

        throw new BadMethodCallException("Unkown property or method {$name} on " . get_class($this));
        
    }

    public function __set($name, $value)
    {

        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

    }
    
}
