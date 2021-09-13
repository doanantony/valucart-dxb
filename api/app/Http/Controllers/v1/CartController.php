<?php

namespace App\Http\Controllers\v1;


use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Hashids;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Bundles;
use App\Rules\Hashexists;
use App\Models\CustomerBundle;
use App\Exceptions\CartException;
use App\Http\Resources\CartCollection;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class CartController extends Controller
{

    use ControllerTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [

            'cart_id' => [
                'nullable'
            ],

            'action' => [
                'required',
                Rule::in(['add','subtract', 'remove', 'update', "allow_alternatives"])
            ],

            'item_type' => [
                'required',
                Rule::in(['product','bundle','customer_bundle'])
            ],

            'item_id' => [
                'required',
            ],

            'quantity' => [
                'nullable',
                'integer'
            ],

            'product_list' => [
                'nullable',
                'array',
            ],

            'allow_alternatives' => [
                'nullable',
                "required_if:action,allow_alternatives",
                'boolean'
            ]

        ];

        $messages = [
            'action.required' => 'Please provide the action (add or subtract, or remove).',
            'action.in' => 'Action must one of add, subtract or remove.',
            'item_type.required' => 'Please provide the Item type(bundle, product or customer bundle).',
            'item_type.in' => 'Item type must one of bundle, product or customer bundle.',
            'item_id.required' => 'Please provide the item Id',
            'quantity.integer' => 'Quantity nust be an integer.',
            // 'product_list.required_if' => 'Please provide the list of porducts in the bundle.'
        ];

        $action = $request->input('action');
        $item_id = $request->input('item_id');
        $item_type = $request->input('item_type');
        $product_list = $request->input('product_list', null);
        $request_quantity = $request->input('quantity', 1);
        $allow_alternatives = $request->input('allow_alternatives', null);

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function($validator) use($request) {

            $action = $request->input('action');
            $cart_id = $request->input('cart_id');
            $item_type = $request->input('item_type');

            if (($action == 'subtract' || $action == 'remove' || $action == 'update') && is_null($cart_id)) {
                $validator->errors()->add('cart_id', 'Please provide a cart id.');
            }

            if ($action == 'update' && $item_type != 'bundle') {
                $validator->errors()->add('action', 'Action update is not applicable to this item type.');
            }

        });

        // Validate
        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);

        }
        
        if (($item_type == 'product') || ($item_type == 'bundle')) {
            $item_id = $this->unhash_id($item_id);
        }
        
        if ($item_type == 'product') {

            $item = Product::find($item_id);

        } else if($item_type == 'bundle') {

            $item = Bundles::find($item_id);

        } else if($item_type == 'customer_bundle') {

            $item = CustomerBundle::find($item_id);

        }

        // Additional validation
        if (is_null($item)) {
            
            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'item_id' => 'The given item_id seem to be invalid.',
                ]
            ], 422);

        }
        
        if ($item_type == 'bundle' && (is_null($product_list) || empty($product_list))) {

            // Get defaual list
            $product_list = $item->products->map(function($product) {
                return $product->id;
            })
            ->toArray();
            
        } else if($item_type == 'bundle' && (!is_null($product_list) && !empty($product_list))) {

            $product_list = array_map(function($product_id) {
                return $this->unhash_id($product_id);
            }, $product_list);

            $valid_id_count = Product::whereIn('id', $product_list)->count();

            if (count($product_list) != $valid_id_count) {

                return response()->json([
                    'status' => 0,
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'product_list' => 'Some product ids in product list might be invalid.',
                    ]
                ], 422);

            }

        }

        $request_cart_id = $request->input('cart_id');

        $customer = $request->user();

        $cart = null;

        // when logged in
        if ($customer) {

            // get cart by customer id
            $cart = Cart::find($customer->id);
            
            // customer does not have a cart
            // and there is no cart id in the request
            // create a new cart
            if (!$cart && !$request_cart_id) {

                $cart = Cart::create($customer->id);
                
            }

            // if we have a valid cart id in the request
            // but the customer does not have a cart
            // assign the request cart id to the customer
            else if ($request_cart_id && !$cart) {
                
                $cart = Cart::find($request_cart_id);
                
                if (is_null($cart)) {
                    
                    $cart = Cart::create($customer->id);
                    
                } else if (!$cart->customer) {
                    
                    $cart->customer = $customer->id;
                    $cart->save();

                } else if ($cart->customer && ($cart->customer != $customer->id)) {

                    return response()->json([
                        'status' => 0,
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'cart_id' => 'ERROR: Customer, cart mismatch.',
                        ]
                    ], 422);

                }
                
            }

            // if customer has a cart
            // and we have a cart id in the request
            // move request cart id items to customer cart
            else if ($cart && $request_cart_id && ($cart->id != $request_cart_id)) {

                $cart = $cart->merge($request_cart_id)->reload();
            
            }
            
        } else {

            if ($request_cart_id) {

                $cart = Cart::find($request_cart_id);
            
                if (is_null($cart)) {
                    $cart = Cart::create();
                }

            } else {

                $cart = Cart::create();

            }
            

        }

        try {

            if (is_null($cart)) {
                throw new CartException('Snap! Something went terribly wrong.');
            }

            if ($action == 'add') {

                $this->check_item_inventory($item);

                if ($cart->can_add($item)) {
                    $cart->add($item, $request_quantity, $allow_alternatives, $product_list);
                }
                
            } elseif ($action == 'update' && $item_type == 'bundle') {

                if (!$cart->has($item)) {

                    return response()->json([
                        'status' => 0,
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'item_id' => 'Item not found in cart.'
                        ]
                    ], 422);

                }
                
                $cart->update($item, $product_list);

            } elseif ($action == 'subtract') {

                if (!$cart->has($item)) {

                    return response()->json([
                        'status' => 0,
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'item_id' => 'Item not found in cart.'
                        ]
                    ], 422);

                }

                $cart->reduce($item, $request_quantity, $cart->item($item)['item']->quantity);

            } else if ($action == 'remove') {

                if (!$cart->has($item)) {

                    return response()->json([
                        'status' => 0,
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'item_id' => 'Item not found in cart.'
                        ]
                    ], 422);

                }

                $cart->remove($item);

            } else if ($action == 'allow_alternatives') {

                if (!$cart->has($item)) {

                    return response()->json([
                        'status' => 0,
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'item_id' => 'Item not found in cart.'
                        ]
                    ], 422);

                }

                $cart->update_allow_alternatives($item, $allow_alternatives);

            } 

            $cart = $cart->reload();

            // revalidate coupon if vailable
            if ($cart->coupon) {

                $coupon = Coupon::find($cart->coupon);

                if ($coupon) {

                    // minimum order value met
                    if (($cart->price < $coupon->minimum_order_value) || !$coupon->valid_for_cart($cart)) {
                        
                        $cart = $cart->remove_coupon();

                    } else {

                        $discount = $coupon->get_discount($cart);

                        $cart->coupon = $coupon->coupon;
                        $cart->discount = $discount;
                        $cart->save();

                        $cart = $cart->reload();

                    }


                } else {

                    $cart = $cart->remove_coupon();

                }

            }

            return response()->json([
                'status' => 1,
                'data' => $cart->http_response,
            ], 200);

        } catch (CartException $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ], 400);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $cart_id = null)
    {

        $customer = $request->user();
        
        $cart = null;

        if ($customer) {
            $cart = Cart::find($customer->id);
        }

        if (is_null($cart) && $cart_id) {
            $cart = Cart::find($cart_id);
        }

        if (is_null($cart)) {
            $cart = $customer ? Cart::create($customer->id) : Cart::create() ;
        }

        // revalidate coupon if vailable
        if ($cart->coupon) {

            $coupon = Coupon::find($cart->coupon);

            if ($coupon) {

                $timezone = CarbonTimeZone::create('Asia/Dubai');

                // minimum order value met
                if (($cart->price < $coupon->minimum_order_value)
                    || $coupon->expires_at->setTimezone($timezone)->isPast()
                    || !$coupon->valid_for_cart($cart)) {
                    
                    $cart = $cart->remove_coupon();

                } else {

                    $discount = $coupon->get_discount($cart);

                    if (($cart->coupon != $coupon->coupon) || ($cart->discount != $discount)) {

                        $cart->coupon = $coupon->coupon;
                        $cart->discount = $discount;
                        $cart->save();

                    }

                }


            } else {

                $cart = $cart->remove_coupon();

            }

        }

        return response()->json([
            'status' => 1,
            'data' => $cart->http_response
        ], 200);
    
    }

    protected function check_item_inventory($item)
    {
//////////////// we have to check this code////////////////////
       // if ($item->inventory <= 0) {
         //   throw new CartException('This item is out of stock.');
       // }

    }

}
