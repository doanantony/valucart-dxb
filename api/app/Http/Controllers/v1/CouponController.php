<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Orders;
use App\Models\Customer;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\GenericCollection;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class CouponController extends Controller
{

    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        
        $query = Coupon::query();

        // filters
        $query = $query->when($request->has('active'), function($query) {
            return $query->where('starts_at', '>=', now()->toW3cString())
                ->where('expires_at', '<', now()->toW3cString())
                ->whereNull('deleted_at');
        });

        $query = $query->when($request->has('expired'), function($query) {
            return $query->where('expires_at', '>=', now()->toW3cString())->whereNull('deleted_at');
        });


        if ($request->query('page')) {

            $per_page = (int) $request->query('per_page', 15);
            $collection = new CouponCollection($query->paginate($per_page));

        } else {

            $collection = new CouponCollection($query->get());

        }
        
        return $collection->additional([
            'status' => 1
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $rules = [
            'coupon' => [
                'required',
                'min:3',
                'max:128',
                'unique:coupons'
            ],
            'starts_at' => [
                'nullable',
            ],
            'expires_at' => [
                'nullable',
            ],
            'minimum_order_value' => [
                'nullable',
                'numeric',
            ],
            'max_order_value' => [
                'nullable',
                'numeric',
            ],
            'discount' => [
                'required',
                'max:128',
            ],
            'usage_limit' => [
                'nullable',
                'integer',
            ],
            'for_payment_method' => [
                'nullable',
                Rule::in([
                    Coupon::CASH_PAYMENT,
                    Coupon::CARD_PAYMENT,
                ]),
            ],
            'for_first_order' => [
                'nullable',
                'boolean',
            ],
        ];

        $messages = [
            'coupon.required' => 'Please provide the coupon.',
            'coupon.min:3' => 'The coupon should be at least 3 characters long.',
            'coupon.max:128' => 'The coupon should not be longer than 128 characters.',
            'coupon.unique' => 'The coupon :input to already exists.',
            'minimum_order_value.numeric' => 'Minimum order value must be a number.',
            'max_order_value.numeric' => 'Maximum order value must be a number.',
            'discount.required' => 'Please provide the discount for this coupon.',
            'discount.max' => 'Discount value should not be longer than 128 characters long.',
            'usage_limit.integer' => 'Usage limit must be an integer.',
            'for_payment_method.in' => 'Payment method should be one of card or cash',
            'for_first_order.boolean' => 'First order should be a boolean type.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function($validator) use($request) {

            $starts_at = $request->input('starts_at');
            $expires_at = $request->input('expires_at');
            $discount = $request->input('discount');
            
            if (!preg_match('/[%|AED]$/i', $discount)) {
                $validator->errors()->add('discount', 'Discount must be percentage or AED.');
            }

            if (!is_null($starts_at) && strtotime($starts_at) === false) {
                $validator->errors()->add('starts_at', 'Coupon start datetime seems to be invalid.');
            }

            if (!is_null($expires_at) && strtotime($expires_at) === false) {
                $validator->errors()->add('expires_at', 'Coupon expiry datetime seems to be invalid.');
            }

            if ((!is_null($starts_at) && strtotime($starts_at))
                && (!is_null($expires_at) && strtotime($expires_at))
                && Carbon::parse($expires_at)->lessThanOrEqualTo(Carbon::parse($starts_at))) {
                $validator->errors()->add('expires_at', 'Coupon expiry datetime must be ahead of the start datetime.');
            }

        });

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()->toArray(),
            ], 422);

        }

        $coupon = $request->input('coupon');

        $time_zone = CarbonTimeZone::create('Asia/Dubai');

        $starts_at = $request->input('starts_at');
        if (!is_null($starts_at)) {

            $starts_at = Carbon::parse($starts_at, $time_zone);
            $starts_at->setTimezone('UTC');
            $starts_at = $starts_at->format('Y-m-d H:i:s');

        } else {

            $starts_at = Carbon::now();
            $starts_at->setTimezone('UTC');
            $starts_at = $starts_at->format('Y-m-d H:i:s');

        }
        
        $expires_at = $request->input('expires_at');
        if (!is_null($expires_at)) {

            $expires_at = Carbon::parse($expires_at, $time_zone);
            $expires_at->setTimezone('UTC');
            $expires_at = $expires_at->format('Y-m-d H:i:s');
        
        } else {

            $expires_at = null;

        }
        

        $minimum_order_value = $request->input('minimum_order_value');
        $max_order_value = $request->input('max_order_value');

        $discount = $request->input('discount');

        $usage_limit = $request->input('usage_limit', null);
        
        $for_payment_method = $request->input('for_payment_method', null);
        
        $for_first_order = $request->input('for_first_order', null);
        
        $coupon = Coupon::create([
            'coupon' => $coupon,
            'minimum_order_value' => (float) $minimum_order_value,
            'max_order_value' => $max_order_value <= 0 ? null : (float) $max_order_value,
            'discount' => $discount,
            'usage_limit' => $usage_limit,
            'for_payment_method' => $for_payment_method,
            'for_first_order' => (int) $for_first_order,
            'starts_at' => $starts_at,
            'expires_at' => $expires_at
        ]);

        return response()->json($coupon, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $coupon)
    {
        
        $coupon = Coupon::where('coupon', $coupon)->first();

        if (is_null($coupon)) {
            
            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $coupon
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $coupon)
    {
        
        $coupon = Coupon::find($coupon);

        if (is_null($coupon)) {
            
            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        $rules = [
            'starts_at' => [
                'present',
                'nullable',
            ],
            'expires_at' => [
                'present',
                'nullable',
            ],
            'minimum_order_value' => [
                'present',
                'nullable',
                'numeric',
            ],
            'max_order_value' => [
                'present',
                'nullable',
                'numeric',
            ],
            'discount' => [
                'present',
                'nullable',
                'max:128',
            ],
            'usage_limit' => [
                'present',
                'nullable',
                'integer',
            ],
            'for_payment_method' => [
                'present',
                'nullable',
                Rule::in([
                    Coupon::CASH_PAYMENT,
                    Coupon::CARD_PAYMENT,
                ]),
            ],
            'for_first_order' => [
                'present',
                'nullable',
                'boolean',
            ],
        ];

        $messages = [
            'minimum_order_value.numeric' => 'Minimum order value must be a number.',
            'max_order_value.numeric' => 'Maximum order value must be a number.',
            'discount.max' => 'Discount value should not be longer than 128 characters long.',
            'usage_limit.integer' => 'Usage limit must be an integer.',
            'for_payment_method.in' => 'Payment method should be one of card or cash',
            'for_first_order.boolean' => 'First order should be a boolean type.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function($validator) use($request) {

            $starts_at = $request->input('starts_at');
            $expires_at = $request->input('expires_at');
            $discount = $request->input('discount');
            
            if (!is_null($discount) && !preg_match('/[%|AED]$/i', $discount)) {
                $validator->errors()->add('discount', 'Discount must be percentage of AED.');
            }

            if (!is_null($starts_at) && strtotime($starts_at) === false) {
                $validator->errors()->add('starts_at', 'Coupon start datetime seems to be invalid.');
            }

            if (!is_null($expires_at) && strtotime($expires_at) === false) {
                $validator->errors()->add('expires_at', 'Coupon expiry datetime seems to be invalid.');
            }

            if ((!is_null($starts_at) && strtotime($starts_at) !== false)
                && (!is_null($expires_at) && strtotime($expires_at) !== false)
                && Carbon::parse($expires_at)->lessThanOrEqualTo(Carbon::parse($starts_at))) {
                $validator->errors()->add('expires_at', 'Coupon expiry datetime must be ahead of the start datetime.');
            }

        });

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()->toArray(),
            ], 422);

        }

        $time_zone = CarbonTimeZone::create('Asia/Dubai');

        $starts_at = $request->input('starts_at');
            
        if (!is_null($starts_at)) {

            $starts_at = Carbon::parse($starts_at, $time_zone);
            $starts_at->setTimezone('UTC');
            $starts_at = $starts_at->format('Y-m-d H:i:s');

        } else {

            $starts_at = Carbon::now();
            $starts_at->setTimezone('UTC');
            $starts_at = $starts_at->format('Y-m-d H:i:s');

        }

        $coupon->starts_at = $starts_at;

        $expires_at = $request->input('expires_at');
            
        if (!is_null($expires_at)) {

            $expires_at = Carbon::parse($expires_at, $time_zone);
            $expires_at->setTimezone('UTC');
            $expires_at = $expires_at->format('Y-m-d H:i:s');
        
        } else {

            $expires_at = null;

        }

        $coupon->expires_at = $expires_at;


        $coupon->minimum_order_value = (float) $request->input('minimum_order_value');

        $max_order_value = $request->input('max_order_value');
        $coupon->minimum_order_value = $max_order_value <= 0 ? null : (float) $max_order_value;

        $coupon->discount = $request->input('discount');

        $coupon->usage_limit = $request->input('usage_limit');
        
        $coupon->for_payment_method = $request->input('for_payment_method');
        
        $coupon->for_first_order = (int) $request->input('for_first_order');

        $coupon->save();

        return response()->json($coupon, 200);

    }

    public function add_user(Request $request, $coupon)
    {

        $rules = [
            'customer_identifier' => [
                'required',
            ],
        ];

        $messages = [
            'customer_identifier.required' => 'Please provide the customer identifier.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 404);

        }
        
        $customer_identifier = $request->input('customer_identifier');

        $coupon = Coupon::find($coupon);

        if (is_null($coupon)) {

            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        $coupon_user = DB::table('coupon_users')
            ->where('coupon', $coupon->coupon)
            ->where('user_identifier', $customer_identifier)
            ->first();
        
        if (!is_null($coupon_user)) {

            return response()->json([
                'id' => $coupon_user->id,
                'coupon' => $coupon_user->coupon,
                'user_identifier' => $coupon_user->user_identifier,
            ], 200);
            
        }

        $inserted_id = DB::table('coupon_users')->insertGetId([
            'coupon' => $coupon->coupon,
            'user_identifier' => $customer_identifier,
        ]);

        return response()->json([
            'id' => $inserted_id,
            'coupon' => $coupon->coupon,
            'user_identifier' => $customer_identifier,
        ], 201);
    
    }

    public function get_users(Request $request, $coupon)
    {

        $coupon = Coupon::find($coupon);

        if (is_null($coupon)) {

            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        $coupon_users = DB::table('coupon_users')->where('coupon', $coupon->coupon);
        
        if ($request->has('page')) {

            $per_page = $request->input('per_page', 15);
            $coupon_users = $coupon_users->paginate($per_page);

        } else {

            $coupon_users = $coupon_users->get();

        }

        return response()->json($coupon_users, 200);
    
    }

    public function remove_user(Request $request, $coupon)
    {

        $rules = [
            'customer_identifier' => [
                'required',
            ],
        ];

        $messages = [
            'customer_identifier.required' => 'Please provide the customer identifier.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 404);

        }
        
        $customer_identifier = (string) $request->input('customer_identifier');

        $coupon = Coupon::find($coupon);

        if (is_null($coupon)) {

            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        $query = DB::table('coupon_users')
            ->where('coupon', $coupon->coupon)
            ->where('user_identifier', $customer_identifier);
        
        if (!$query->exists()) {

            return response()->json([
                'status' => 0,
                'message' => 'User identifier is not associated with this coupon'
            ], 422);
            
        }

        $query->delete();

        return response('', 204);

    }

    public function add_item(Request $request, $coupon)
    {

        $rules = [
            'item_type' => [
                'required',
                Rule::in([
                    Coupon::PRODUCT,
                    Coupon::PRODUCT_DEPARTMENT,
                    Coupon::PRODUCT_CATEGORY,
                    Coupon::PRODUCT_SUB_CATEGORY,
                    Coupon::PRODUCT_BRAND,
                    Coupon::BUNDLE,
                    Coupon::BUNDLE_CATEGORY,
                ]),
            ],
            'item_identifier' => [
                'required',
            ],
        ];

        $messages = [
            'item_type.required' => 'Please provide the item type.',
            'item_type.in' => 'Item type seems to be invalid.',
            'item_identifier.required' => 'Please provide the item identifier.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }
        
        $item_type = $request->input('item_type');
        
        $item_identifier = $request->input('item_identifier');
      //  $item_identifier = $this->unhash_id($item_identifier);

        if (is_null($item_identifier)) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'item_identifier' => 'Item identifier seems to be invalid.'
                ],
            ], 422);

        }

        $coupon = Coupon::find($coupon);

        if (is_null($coupon)) {

            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        $coupon_item = DB::table('coupon_items')
            ->where('coupon', $coupon->coupon)
            ->where('item_type', $item_type)
            ->where('item_id', $item_identifier)
            ->first();
        
        if (!is_null($coupon_item)) {

            return response()->json([
                'id' => $coupon_item->id,
                'coupon' => $coupon_item->coupon,
                'item_type' => $coupon_item->item_type,
                'item_identifier' => $coupon_item->item_id,
            ], 200);
            
        }

        $inserted_id = DB::table('coupon_items')->insertGetId([
            'coupon' => $coupon->coupon,
            'item_type' => $item_type,
            'item_id' => $item_identifier,
        ]);

        return response()->json([
            'id' => $inserted_id,
            'coupon' => $coupon->coupon,
            'item_type' => $item_type,
            'item_identifier' => $item_identifier,
        ], 201);

    }

    public function get_items(Request $request, $coupon)
    {

        $coupon = Coupon::find($coupon);

        if (is_null($coupon)) {

            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        $coupon_items = DB::table('coupon_items')->where('coupon', $coupon->coupon);
        
        if ($request->has('page')) {

            $per_page = $request->input('per_page', 15);
            $coupon_items = $coupon_items->paginate($per_page);

        } else {

            $coupon_items = $coupon_items->get();

        }

        return response()->json($coupon_items, 200);
    
    }

    public function remove_item(Request $request, $coupon)
    {

        $rules = [
            'item_type' => [
                'required',
                Rule::in([
                    Coupon::PRODUCT,
                    Coupon::PRODUCT_DEPARTMENT,
                    Coupon::PRODUCT_CATEGORY,
                    Coupon::PRODUCT_SUB_CATEGORY,
                    Coupon::PRODUCT_BRAND,
                    Coupon::BUNDLE,
                    Coupon::BUNDLE_CATEGORY,
                ]),
            ],
            'item_identifier' => [
                'required',
            ],
        ];

        $messages = [
            'item_type.required' => 'Please provide the item type.',
            'item_type.in' => 'Item type seems to be invalid.',
            'item_identifier.required' => 'Please provide the item identifier.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 404);

        }
        
        $item_type = $request->input('item_type');
        
        $item_identifier = $request->input('item_identifier');
        $item_identifier = $this->unhash_id($item_identifier);

        if (is_null($item_identifier)) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'item_identifier' => 'Item identifier seems to be invalid.'
                ],
            ], 422);

        }

        $coupon = Coupon::find($coupon);

        if (is_null($coupon)) {

            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        $query = DB::table('coupon_items')
            ->where('coupon', $coupon->coupon)
            ->where('item_type', $item_type)
            ->where('item_id', $item_identifier);
        
        if (!$query->exists()) {

            return response()->json([
                'status' => 0,
                'message' => 'Item seem to not be associated with this coupon.'
            ], 404);

        }
        
        $query->delete();

        return response('', 204);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $coupon)
    {

        $coupon = Coupon::where('coupon', $coupon)->first();

        if (is_null($coupon)) {
            
            return response()->json([
                'status' => 0,
                'message' => 'Coupon not found'
            ], 404);

        }

        $coupon->delete();

        return response('', 204);

    }

    public function apply_coupon(Request $request)
    {

        $rules = [
            'cart_id' => [
                'required',
            ],
            'coupon' => [
                'required'
            ]
        ];

        $messages = [
            'cart_id.required' => 'Please provide the cart id.',
            'coupon.required' => 'Please provide the coupon.' 
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $customer = $request->user();

        $customer_id = !is_null($customer) ? $customer->id : null ;

        $coupon = $request->input('coupon');
        $cart_id = $request->input('cart_id');

        // Check for cart
        $cart = Cart::find($cart_id);
        if (!$cart) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'Cart not found.'
                    ]
                ],
            ], 422);

        }

        // check for coupon
        $coupon = Coupon::where('coupon', $coupon)->whereNull('deleted_at')->first();
        if (is_null($coupon)) {
            
            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'Invalid coupon CODE or CODE does not exist.'
                    ]
                ],
            ], 422);

        }

        // minimum order value met
        if ($cart->price < $coupon->minimum_order_value) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'CODE only applicable to orders dhs' . $coupon->minimum_order_value . '/- and above.'
                    ]
                ],
            ], 422);

        }

        // check first order
        $orders = DB::table('orders')
            ->where('customer_id', $customer_id)
            ->where('status', '>=', Orders::PLACED)
            ->exists();

        if ($orders && $coupon->for_first_order) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'Code ONLY valid on your first checkout/order.'
                    ]
                ],
            ], 422);

        }

        $timezone = CarbonTimeZone::create('Asia/Dubai');
        
        // starts datetime has been reached
        if (!$coupon->starts_at->setTimezone($timezone)->isPast()) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'Coupon might be invalid or inactive.'
                    ]
                ],
            ], 422);

        }

        // allowed for user
        if (!$coupon->valid_for_user($customer)) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'Coupon is not valid for customer.'
                    ]
                ],
            ], 422);

        }

        // max order value met
        if (is_numeric($coupon->max_order_value) && ($cart->price > $coupon->max_order_value)) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'CODE only applicable to orders below dhs' . $coupon->max_order_value . '/-.'
                    ]
                ],
            ], 422);

        }
        
        // not expired
        if ($coupon->expires_at->setTimezone($timezone)->isPast()) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'Coupon might be invalid or expired.'
                    ]
                ],
            ], 422);

        }
        
        // usage limit not exceeded
        if ($coupon->limit_usage($customer)) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'Sorry, you can only use this coupon ' . ( $coupon->usage_limit == 1 ? 'once' : $coupon->usage_limit . ' times' ),
                    ],
                ],
            ], 422);

        }
        
        // check cart items
        if (!$coupon->valid_for_cart($cart)) {

            return response()->json([
                'status' => 0,
                'messages' => 'The given data was invalid.',
                'errors' => [
                    'coupon' => [
                        'Code not valid for items in this cart.'
                    ]
                ],
            ], 422);

        }
        
        // Apply discount to cart
        $discount = $coupon->get_discount($cart);

        $cart->coupon = $coupon->coupon;
        $cart->discount = $discount;
        $cart->save();

        $cart = $cart->reload();
        
        return response()->json([
            'status' => 1,
            'data' => $cart->http_response
        ], 200);

    }

}
