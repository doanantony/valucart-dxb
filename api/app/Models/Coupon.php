<?php

namespace App\Models;

use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\Cart;
use App\Models\Customer;


class Coupon extends Model
{
    
    const PRODUCT = 'product';
    const PRODUCT_DEPARTMENT = 'product_department';
    const PRODUCT_CATEGORY = 'product_category';
    const PRODUCT_SUB_CATEGORY = 'product_sub_category';
    const PRODUCT_BRAND = 'product_brand';
    const BUNDLE = 'bundle';
    const BUNDLE_CATEGORY = 'bundle_category';

    const CASH_PAYMENT = 'cash';
    const CARD_PAYMENT = 'card';

    protected $table = 'coupons';

    protected $primaryKey = 'coupon';

    protected $keyType ='string';

    public $incrementing = false;

    protected $fillable = [
        'coupon',
        'minimum_order_value',
        'max_order_value',
        'discount',
        'usage_limit',
        'for_payment_method',
        'for_first_order',
        'starts_at',
        'expires_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'starts_at',
        'expires_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $visible = [
        'coupon',
        'minimum_order_value',
        'max_order_value',
        'discount',
        'usage_limit',
        'for_payment_method',
        'for_first_order',
        'starts_at',
        'expires_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'for_first_order' => 'boolean',
    ];

    public function valid_for_user($customer)
    {

        $coupon_users = DB::table('coupon_users')
            ->where('coupon', $this->coupon)
            ->get();
        
        // Valid for all users
        if ($coupon_users->firstWhere('user_identifier', '*')) {
            return true;
        }

        // If there is no user information
        if (is_null($customer) || !($customer instanceof Customer)) {
            return false;
        }

        // if user identified by email
        // if user identified by phone number
        // if user identified by use id

        foreach ($coupon_users as $coupon_user) {
            
            // Check email
            if (!is_null($customer->email) && (
                    ($customer->email == $coupon_user->user_identifier) ||
                    \Str::endsWith($customer->email, $coupon_user->user_identifier)
                )) {
                return true;
            }

            // check phone number
            if (!is_null($customer->phone_number) && ($customer->phone_number == $coupon_user->user_identifier)) {
                return true;
            }

            // check user id
            if ($customer->id == $coupon_user->user_identifier) {
                return true;
            }

        }

        return false;

    }

    public function limit_usage($customer)
    {

        if (is_null($customer)) {
            return false;
        }

        $usage = DB::table('coupon_usage')
            ->where('coupon', $this->coupon)
            ->where('customer_id', $customer->id)
            ->count();
        
        if ($usage >= $this->usage_limit) {
            return true;
        }

        return false;

    }

    public function valid_for_cart(Cart $cart)
    {

        $coupon_items = DB::table('coupon_items')->where('coupon', $this->coupon)->get();

        if ($coupon_items->count() == 0) {
            return true;
        }

        $cart_items = $cart->items;

        $products = $cart_items->get('products', collect([]));
         $bundles = $cart_items->get('bundles', collect([]));

        foreach ($coupon_items as $coupon_item) {
            
            if (($coupon_item->item_type == self::PRODUCT) && !is_null($products->firstWhere('item.id', $coupon_item->item_id))) {
                return true;
            }
            
            if (($coupon_item->item_type == self::BUNDLE) && $bundles->firstWhere('item.id', $coupon_item->item_id)) {
                return true;
            }

            if (($coupon_item->item_type == self::PRODUCT_BRAND)) {
                
                foreach($products as $product) {
                    if ($product['item']->brand_id == $coupon_item->item_id){
                        return true;
                    }
                }

            }
            
            if (($coupon_item->item_type == self::PRODUCT_DEPARTMENT)) {
                
                foreach($products as $product) {
                    if ($product['item']->department_id == $coupon_item->item_id){
                        return true;
                    }
                }

            }

            if (($coupon_item->item_type == self::PRODUCT_CATEGORY)) {
                
                foreach($products as $product) {
                    if ($product['item']->category_id == $coupon_item->item_id){
                        return true;
                    }
                }

            }

            if (($coupon_item->item_type == self::PRODUCT_SUB_CATEGORY)) {
                
                foreach($products as $product) {
                    if ($product['item']->subcategory_id == $coupon_item->item_id){
                        return true;
                    }
                }

            }

            if (($coupon_item->item_type == self::BUNDLE_CATEGORY)) {
                
                foreach($bundles as $bundle) {
                    if ($bundle['item']->category_id == $coupon_item->item_id){
                        return true;
                    }
                }

            }
            
        }

        return false;

    }

    public function get_discount(Cart $cart)
    {

        $coupon_items = DB::table('coupon_items')->where('coupon', $this->coupon)->get();

        $products = $cart->items->get('products', collect([]))->where('item.is_offer', false);
         $bundles = $cart->items->get('bundles', collect([]));

        $items = collect([]);

        if ($coupon_items->count() == 0) {

            // Check discount on all products
            $items = $products;

        } else {

            foreach ($coupon_items as $coupon_item) {
            
                switch ($coupon_item->item_type) {
                    
                    case 'product':
                        $items = $items->concat($products->where('item.id', $coupon_item->item_id));
                        break;
                    
                    case 'product_brand':
                        $items = $items->concat($products->where('item.brand_id', $coupon_item->item_id));
                        break;
                    
                    case 'product_department':
                        $items = $items->concat($products->where('item.department_id', $coupon_item->item_id));
                        break;
                    
                    case 'product_category':
                        $items = $items->concat($products->where('item.category_id', $coupon_item->item_id));
                        break;
    
                    case 'product_sub_category':
                        $items = $items->concat($products->where('item.subcategory_id', $coupon_item->item_id));
                        break;
    
                    case 'bundle_category':
                        $items = $bundles->where('item.category_id', $coupon_item->item_id);
                        break;
    
                    case 'bundle':
                        $items = $bundles->where('item.id', $coupon_item->item_id);
                        break;
                
                }
    
            }

        }

        $total_discount = 0;

        $discount_string_matches = [];
        preg_match('/(%|AED)$/', $this->discount, $discount_string_matches);

        $discount = (float) $this->discount;

        $discounted_items = [];
        //print_r($discounted_items);die;
        if ($discount_string_matches[1] == '%') {

            foreach ($items as $item) {
            
                if (in_array($item['item']->id, $discounted_items)) {
                    continue;
                }
    
                $valucart_price = $item['item']->valucart_price;
                $total_discount += (($discount / 100) * $valucart_price) * $item['quantity'];
                array_push($discounted_items, $item['item']->id);
                
            }

        } else if($discount_string_matches[1] == 'AED' && $items->isNotEmpty()) {

            $total_discount = $discount;

        }

        return round($total_discount, 2) . ' AED';

    }

    public static function record_usage($coupon, $customer)
    {

        \DB::table('coupon_usage')->insert([
            'coupon' => $coupon,
            'customer_id' => (string) $customer,
        ]);

    }

}
