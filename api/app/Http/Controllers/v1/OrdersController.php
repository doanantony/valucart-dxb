<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

use Hashids;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Coupon;
use App\Rules\Hashexists;
use App\Models\CustomerAddress;
use App\Models\DeliveryTimeSlot;
use App\Http\Resources\OrdersCollection;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class OrdersController extends Controller
{   
    use ControllerTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $time_slots = DB::table("delivery_time_slots")->select("id","time_slots")->get();

         return response()->json([
                "status" => 1,
                "data" => $time_slots,
            ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = $request->user();

        $rules = [

            "cart_id" => [
                "required",
            ],

            "delivery_date" => [
                "required",
            ],

            "address_id" => [
                "required",
            ],

            "time_slot_id" => [
                "required",
            ],

            'note' => [
                'nullable',
                'max:1024'
            ]
        
        ];

        $messages = [
              "cart_id.required" => "Please provide a cart id",
              "delivery_date.required" => "Please provide the delivery date",
              "address_id.required" => "Please provide address id",
              "time_slot_id.required" => "Please provide time slot id",
              "note.max" => "Note is too long"
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => $validator->errors(),
            ], 404);

        }

        $cart_id = $request->input("cart_id");
        $delivery_date = $request->input("delivery_date");
        $address_id = $request->input("address_id");
        $time_slot_id = $request->input("time_slot_id");
        $note = $request->input("note");

        $customer_id = $user->id;
        
        $cart = Cart::find($cart_id);
        
        if (!$cart) {

            return response()->json([
                "status" => 0,
                "message" => "Cart not found"
            ], 404);

        }
        
        $delivery_time = DeliveryTimeSlot::find($time_slot_id);
        
        // if (is_null($delivery_time)) {
            
        //     return response()->json([
        //         "status" => 0,
        //         "message" => "The given data was invalid.",
        //         "errors" => [
        //             "time_slot_id" => ["Invalid delivery time slot"]
        //         ]
        //     ], 422);

        // }
        
        // if (!$delivery_time->is_vailable_for($delivery_date)) {

        //     return response()->json([
        //         "status" => 0,
        //         "message" => "The given data was invalid.",
        //         "errors" => [
        //             "time_slot_id" => ["The selected delivery time slot is not available."]
        //         ]
        //     ], 422);

        // }

        // Check minimum order
        // if(!$cart->can_checkout) {

        //     $minimum_order = env("MINIMUM_ORDER_VALUE");

        //     return response()->json([
        //         "status" => 0,
        //         "message" => "Order should be at least AED" . $minimum_order,
        //         "errors" => [
        //             "minimum_order" => [
        //                 "Order should be at least AED" . $minimum_order,
        //             ]
        //         ]
        //     ], 422);
        // }

        // if (is_null($user->email) && is_null($user->contact_email)) {

        //     return response()->json([
        //         "status" => 0,
        //         "message" => "The given data was invalid.",
        //         "errors" => [
        //             "address_id" => [
        //                 "Please update you contact information, provide email.",
        //             ]
        //         ]
        //     ], 422);

        // }

        // if (is_null($user->phone_number)) {

        //     return response()->json([
        //         "status" => 0,
        //         "message" => "The given data was invalid.",
        //         "errors" => [
        //             "address_id" => [
        //                 "Please update you contact information, provide phone number.",
        //             ]
        //         ]
        //     ], 422);

        // }

        // if (is_null($user->contact_email)) {

        //     return response()->json([
        //         "status" => 0,
        //         "message" => "The given data was invalid.",
        //         "errors" => [
        //             "address_id" => [
        //                 "Your contact information might not be upto date.",
        //             ]
        //         ]
        //     ], 422);

        // }

        // if (is_null($user->email) && !$user->email_verified && is_null($user->contact_email)) {

        //     return response()->json([
        //         "status" => 0,
        //         "message" => "The given data was invalid.",
        //         "errors" => [
        //             "address_id" => [
        //                 "Please verify you email address to continue.",
        //             ]
        //         ]
        //     ], 422);

        // }

        $price = $cart->price;

        $is_order_exists = Orders::where("cart_id", $cart_id)->where("status", 1)->first();

        $delivery_date = Carbon::parse($delivery_date);

        if(is_null($is_order_exists)){
               
            $now = now();

            $invoice_number = $now->year + 
                      $now->month + 
                      $now->day + 
                      $now->hour + 
                      $now->minute + 
                      $now->second + 
                      mt_rand(100, 1100) + 
                      $user->id;

            $order_reference = "VC". $invoice_number;

            // $snapshoot_delivery_time_slot = \DB::table("delivery_time_slots")
            //     ->where("id", $time_slot_id)
            //     ->first("time_slots");
                
            $delivery_address = $user->addresses()->where("id", $address_id)->first();

            $delivery_charge = $cart->delivery_charge;
            $discounted_price = $cart->discounted_price;
            
            $order_snapshoot = [
                "created_at" => now()->format("l jS F Y H:d"),
                "reference" => $order_reference,
                "invoice_no" => "",
                "sub_total" => round($cart->price, 2),
                "delivery_charge" => round($delivery_charge, 2),
                "discount" => $cart->discount,
                "vat" => "5%",
                "total" => round(($discounted_price + $delivery_charge), 2),
                "customer" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "telephone" => $user->phone_number ? $user->phone_number : "",
                    "email" => $user->contact_email ? $user->contact_email : "",
                ],
                "delivery_information" => [
                    "address" => (string) $delivery_address,
                    "date" => $delivery_date->format("l jS F Y"),
                    "time" => (string) $delivery_time,
                    "address_data" => (array) $delivery_address,
                ],
                "products" => $cart->items->get("products", collect([]))->map(function($product) {
                    $quantity = $product["quantity"];
                    $allow_alternatives = $product["allow_alternatives"];
                    $product = $product["item"];

                    $vendor = $product->vendors()->first();
                    
                    $product_array = $product->toArray();

                    return [
                        "id" => $product->id,
                        "department" => !is_null($product->department) ? $product->department->name : "",
                        "category" => !is_null($product->category) ? $product->category->name : "",
                        "subcategory" => !is_null($product->subcategory) ? $product->subcategory->name : "",
                        "name" => $product->name,
                        "sku" => $product->sku,
                        "vendor" => !is_null($vendor) ? $vendor->name : "",
                        "brand" => $product->brand->name,
                        "packaging" => !is_null($product->packaging_quantity_unit) ? $product->packaging_quantity . $product->packaging_quantity_unit->symbol : "",
                        "price" => $product->valucart_price,
                        "quantity" => $quantity,
                        "description" => $product->description,
                        "packaging_quantity" => $product->packaging_quantity,
                        "maximum_selling_price" => $product->maximum_selling_price,
                        "percentage_discount" => "0",
                        "valucart_price" => $product->valucart_price,
                        "packaging_quantity_unit" => $product->packaging_quantity_unit,
                        "images" => $product_array["images"],
                        "thumbnail" => $product_array["thumbnail"],
                        "is_bulk" => $product->is_bulk,
                        "is_offer" => $product->is_offer,
                        "bulk_quantity" => $product->bulk_quantity,
                        "allow_alternatives" => $allow_alternatives ? "Yes" : "No"
                    ];

                })->toArray(),
                "bundles" => $cart->items->get("bundles", collect([]))->map(function($bundle) {

                    $quantity = $bundle["quantity"];
                    $bundle = $bundle["item"];
                    
                    $products = $bundle->selected_products_list;

                    return [
                        "id" => Hashids::encode($bundle->id),
                        "name" => $bundle->name,
                        "price" => $bundle->valucart_price,
                        "quantity" => $quantity,
                        "category" => Hashids::encode($bundle->category_id),
                        "description" => $bundle->description,
                        "item_count" => $bundle->item_count,
                        "maximum_selling_price" => $bundle->price,
                        "valucart_price" => $bundle->valucart_price,
                        "percentage_discount" => $bundle->percentage_discount,
                        "is_popular" => $bundle->is_popular,
                        "images" => $bundle->get_image_urls(),
                        "thumbnail" => $bundle->thumbnail,
                        "products" => $products->map(function($product) {

                            $vendor = $product->vendors()->first();

                            $product_array = $product->toArray();
                            // return [
                            //     "name" => $product->name,
                            //     "sku" => $product->sku,
                            //     "vendor" => !is_null($vendor) ? $vendor->name : "",
                            //     "brand" => $product->brand->name,
                            //     "packaging" => $product->packaging_quantity . $product->packaging_quantity_unit->symbol,
                            //     "price" => $product->valucart_price,
                            //     "quantity" => $product->bundled_quantity,
                            // ];
                            return [
                                "id" => $product->id,
                                "department" => !is_null($product->department) ? $product->department->name : "",
                                "category" => !is_null($product->category) ? $product->category->name : "",
                                "subcategory" => !is_null($product->subcategory) ? $product->subcategory->name : "",
                                "name" => $product->name,
                                "sku" => $product->sku,
                                "vendor" => !is_null($vendor) ? $vendor->name : "",
                                "brand" => $product->brand->name,
                               "packaging" => !is_null($product->packaging_quantity_unit) ? $product->packaging_quantity . $product->packaging_quantity_unit->symbol : "",
                                "price" => $product->valucart_price,
                                "quantity" => $product->bundled_quantity,
                                "description" => $product->description,
                                "packaging_quantity" => $product->packaging_quantity,
                                "maximum_selling_price" => $product->maximum_selling_price,
                                "percentage_discount" => "0",
                                "valucart_price" => $product->valucart_price,
                                "packaging_quantity_unit" => $product->packaging_quantity_unit,
                                "images" => $product_array["images"],
                                "thumbnail" => $product_array["thumbnail"],
                                "is_bulk" => $product->is_bulk,
                                "is_offer" => $product->is_offer,
                                "bulk_quantity" => $product->bulk_quantity,
                            ];
                        })
                    ];

                })->toArray(),
                "customer_bundles" => $cart->items->get("customer_bundles", collect([]))->map(function($bundle) {
                    
                    $quantity = $bundle["quantity"];
                    $bundle = $bundle["item"];
                    
                    return [
                        "id" => $bundle->id,
                        "name" => $bundle->name,
                        "price" => $bundle->valucart_price,
                        "quantity" => $quantity,
                        "description" => $bundle->description,
                        "customer_id" => $bundle->customer_id,
                        "created_at" => $bundle->created_at,
                        "maximum_selling_price" => $bundle->maximum_selling_price,
                        "discount" => $bundle->discount,
                        "valucart_price" => $bundle->valucart_price,
                        "inventory" => $bundle->inventory,
                        "thumbnai" => $bundle->thumbnail,
                        "products" => $bundle->products->map(function($product) {
                            
                            $quantity = $product["quantity"];
                            $product = $product["product"];
                            
                            $vendor = $product->vendors()->first();

                            $product_array = $product->toArray();

                            // return [
                            //     "name" => $product->name,
                            //     "sku" => $product->sku,
                            //     "vendor" => !is_null($vendor) ? $vendor->name : "",
                            //     "brand" => $product->brand->name,
                            //     "packaging" => $product->packaging_quantity . $product->packaging_quantity_unit->symbol,
                            //     "price" => $product->valucart_price,
                            //     "quantity" => $quantity,
                            // ];

                            return [
                                "id" => $product->id,
                                "department" => !is_null($product->department) ? $product->department->name : "",
                                "category" => !is_null($product->category) ? $product->category->name : "",
                                "subcategory" => !is_null($product->subcategory) ? $product->subcategory->name : "",
                                "name" => $product->name,
                                "sku" => $product->sku,
                                "vendor" => !is_null($vendor) ? $vendor->name : "",
                                "brand" => $product->brand->name,
                                "packaging" => !is_null($product->packaging_quantity_unit) ? $product->packaging_quantity . $product->packaging_quantity_unit->symbol : "",
                                "price" => $product->valucart_price,
                                "quantity" => $quantity,
                                "description" => $product->description,
                                "packaging_quantity" => $product->packaging_quantity,
                                "maximum_selling_price" => $product->maximum_selling_price,
                                "percentage_discount" => "0",
                                "valucart_price" => $product->valucart_price,
                                "packaging_quantity_unit" => $product->packaging_quantity_unit,
                                "images" => $product_array["images"],
                                "thumbnail" => $product_array["thumbnail"],
                                "is_bulk" => $product->is_bulk,
                                "is_offer" => $product->is_offer,
                                "bulk_quantity" => $product->bulk_quantity,
                            ];

                        })
                    ];

                })->toArray(),
            ];

            $order = Orders::create([
                "customer_id" => $customer_id,
                "order_reference" => $order_reference,
                "delivery_date" => $delivery_date->format("Y-m-d"),
                "address_id" => $address_id,
                "cart_id" => $cart_id,
                "time_slot_id" => $delivery_time->id,
                "sub_total_price" => round($cart->price, 2),
                "first_order_discount" => 0, //round($first_order_discount, 2),
                "discount" => $cart->discount,
                "price" => round($cart->discounted_price, 2),
                "snapshots" => serialize($order_snapshoot),
                "status" => Orders::CREATED,
                "note" => $note,
            ]);
            
            return response()->json(["status" => 1, "data" =>  $order], 201);

        } else {
            
            $order = $is_order_exists;

            $order->sub_total_price = $cart->price;//round($sub_total_price, 2);
            $order->first_order_discount = 0;//round($first_order_discount, 2);
            $order->price = $cart->discounted_price;//round($total_price, 2);

            $order->delivery_date = $delivery_date->format("Y-m-d");
            $order->address_id = $address_id;
            $order->time_slot_id = $delivery_time->id;
            $order->note = $note;

            // $cart = Cart::find($cart_id);
            $delivery_address = $user->addresses()->where("id", $address_id)->first();

            $delivery_charge = $cart->delivery_charge;
            $discounted_price = $cart->discounted_price;
            
            $order_snapshoot = [
                "sub_total" => round($cart->price, 2),
                "delivery_charge" => round($delivery_charge, 2),
                "discount" => $cart->discount,
                "discounted_price" => $discounted_price,
                "total" => round(($cart->discounted_price + $delivery_charge), 2),
                "delivery_information" => [
                    "address" => (string) $delivery_address,
                    "date" => $delivery_date->format("l jS F Y"),
                    "time" => (string) $delivery_time,
                    "address_data" => (array) $delivery_address,
                ],
                "products" => $cart->items->get("products", collect([]))->map(function($product) {

                    $quantity = $product["quantity"];
                    $product = $product["item"];

                    $vendor = $product->vendors()->first();

                    $product_array = $product->toArray();
//print_r($product->packaging_quantity_unit['symbol']);
 //exit();
                    $packaging = $product->packaging_quantity . " " . $product->packaging_quantity_unit["symbol"];

                    return [
                        "id" => $product->id,
                        "department" => !is_null($product->department) ? $product->department->name : "",
                        "category" => !is_null($product->category) ? $product->category->name : "",
                        "subcategory" => !is_null($product->subcategory) ? $product->subcategory->name : "",
                        "name" => $product->name,
                        "sku" => $product->sku,
                        "vendor" => !is_null($vendor) ? $vendor->name : "",
                        "brand" => $product->brand->name,
                        "packaging" => $packaging,
                        "price" => $product->valucart_price,
                        "quantity" => $quantity,
                        "description" => $product->description,
                        "packaging_quantity" => $product->packaging_quantity,
                        "maximum_selling_price" => $product->maximum_selling_price,
                        "percentage_discount" => "0",
                        "valucart_price" => $product->valucart_price,
                        "packaging_quantity_unit" => $product->packaging_quantity_unit,
                        "images" => $product_array["images"],
                        "thumbnail" => $product_array["thumbnail"],
                        "is_bulk" => $product->is_bulk,
                        "is_offer" => $product->is_offer,
                        "bulk_quantity" => $product->bulk_quantity,
                    ];

                })->toArray(),
                "bundles" => $cart->items->get("bundles", collect([]))->map(function($bundle) {

                    $quantity = $bundle["quantity"];
                    $bundle = $bundle["item"];
                    
                    $products = $bundle->selected_products_list;

                    return [
                        "id" => Hashids::encode($bundle->id),
                        "name" => $bundle->name,
                        "price" => $bundle->valucart_price,
                        "quantity" => $quantity,
                        "category" => Hashids::encode($bundle->category_id),
                        "description" => $bundle->description,
                        "item_count" => $bundle->item_count,
                        "maximum_selling_price" => $bundle->price,
                        "valucart_price" => $bundle->valucart_price,
                        "percentage_discount" => $bundle->percentage_discount,
                        "is_popular" => $bundle->is_popular,
                        "images" => $bundle->get_image_urls(),
                        "thumbnail" => $bundle->thumbnail,
                        "products" => $products->map(function($product) {

                            $vendor = $product->vendors()->first();

                            $product_array = $product->toArray();

                            return [
                                "id" => $product->id,
                                "department" => !is_null($product->department) ? $product->department->name : "",
                                "category" => !is_null($product->category) ? $product->category->name : "",
                                "subcategory" => !is_null($product->subcategory) ? $product->subcategory->name : "",
                                "name" => $product->name,
                                "sku" => $product->sku,
                                "vendor" => !is_null($vendor) ? $vendor->name : "",
                                "brand" => $product->brand->name,
                                "packaging" => !is_null($product->packaging_quantity_unit) ? $product->packaging_quantity . $product->packaging_quantity_unit->symbol : "",
                                "price" => $product->valucart_price,
                                "quantity" => $product->bundled_quantity,
                                "description" => $product->description,
                                "packaging_quantity" => $product->packaging_quantity,
                                "maximum_selling_price" => $product->maximum_selling_price,
                                "percentage_discount" => "0",
                                "valucart_price" => $product->valucart_price,
                                "packaging_quantity_unit" => $product->packaging_quantity_unit,
                                "images" => $product_array["images"],
                                "thumbnail" => $product_array["thumbnail"],
                                "is_bulk" => $product->is_bulk,
                                "is_offer" => $product->is_offer,
                                "bulk_quantity" => $product->bulk_quantity,
                            ];
                        })
                    ];

                })->toArray(),
                "customer_bundles" => $cart->items->get("customer_bundles", collect([]))->map(function($bundle) {
                    
                    $quantity = $bundle["quantity"];
                    $bundle = $bundle["item"];
                    
                    return [
                        "id" => $bundle->id,
                        "name" => $bundle->name,
                        "price" => $bundle->valucart_price,
                        "quantity" => $quantity,
                        "description" => $bundle->description,
                        "customer_id" => $bundle->customer_id,
                        "created_at" => $bundle->created_at,
                        "maximum_selling_price" => $bundle->maximum_selling_price,
                        "discount" => $bundle->discount,
                        "valucart_price" => $bundle->valucart_price,
                        "inventory" => $bundle->inventory,
                        "thumbnai" => $bundle->thumbnail,
                        "products" => $bundle->products->map(function($product) {
                            
                            $quantity = $product["quantity"];
                            $product = $product["product"];
                            
                            $vendor = $product->vendors()->first();

                            $product_array = $product->toArray();

                            return [
                                "id" => $product->id,
                                "department" => !is_null($product->department) ? $product->department->name : "",
                                "category" => !is_null($product->category) ? $product->category->name : "",
                                "subcategory" => !is_null($product->subcategory) ? $product->subcategory->name : "",
                                "name" => $product->name,
                                "sku" => $product->sku,
                                "vendor" => !is_null($vendor) ? $vendor->name : "",
                                "brand" => $product->brand->name,
                                "packaging" => !is_null($product->packaging_quantity_unit) ? $product->packaging_quantity . $product->packaging_quantity_unit->symbol : "",
                                "price" => $product->valucart_price,
                                "quantity" => $product->bundled_quantity,
                                "description" => $product->description,
                                "packaging_quantity" => $product->packaging_quantity,
                                "maximum_selling_price" => $product->maximum_selling_price,
                                "percentage_discount" => "0",
                                "valucart_price" => $product->valucart_price,
                                "packaging_quantity_unit" => $product->packaging_quantity_unit,
                                "images" => $product_array["images"],
                                "thumbnail" => $product_array["thumbnail"],
                                "is_bulk" => $product->is_bulk,
                                "is_offer" => $product->is_offer,
                                "bulk_quantity" => $product->bulk_quantity,
                            ];

                        })
                    ];

                })->toArray(),
            ];

            $order_snapshoot = array_merge(unserialize($order->snapshots), $order_snapshoot);

            $order->snapshots = serialize($order_snapshoot);

            $order->save();

            // DB::table("orders")
            //     ->where("cart_id", $cart_id)
            //     ->update(["sub_total_price" => round($sub_total_price,2),
            //         "first_order_discount" => round($first_order_discount, 2),
            //         "price" => round($total_price,2)
            //     ]);

            // $order = DB::table("orders")->where("cart_id",$cart_id)->first();

            $response_data = [
                "id" => $order->id, 
                "customer_id" => $order->customer_id,
                "payment_type" => $order->payment_type,
                "sub_total_price"=> $order->sub_total_price,
                "first_order_discount" =>$order->first_order_discount,
                "discount" => $cart->discount,
                "price" =>$order->price,
                "order_reference" =>$order->order_reference,
                "time_slot_id" =>$delivery_time->id,
                "delivery_date" =>$order->delivery_date,
                "created_at" =>$order->created_at,
                "updated_at" =>$order->updated_at,
                "cart_id" =>$order->cart_id,
                "address_id" =>$order->address_id,
            ];

            if ($order) {

                return response()->json([
                    "status" => 1,
                    "data" => $response_data,
                ], 200);

            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $order_reference)
    {
        
        $order = Orders::where("order_reference", $order_reference)->first();
        
        if (is_null($order)) {

            return response()->json([
                "status" => 0,
                "message" => "Order Reference not found"
            ], 404);

        }
        
        $cart = Cart::find($order->cart_id);
        $snapshot = unserialize($order->snapshots);
        
        // $address = CustomerAddress::find($order->address_id);

        // $delivery_time = DeliveryTimeSlot::find($order->time_slot_id);

        $products = array_map(function($product) {
            
            return [
                "quantity" => $product["quantity"],
                "item" => $product,
            ];

        }, $snapshot["products"]);

        $bundles = array_map(function($bundle) {
            
            return [
                "quantity" => $bundle["quantity"],
                "item" => $bundle,
            ];

        }, $snapshot["bundles"]);

        $customer_bundles = array_map(function($bundle) {
            
            return [
                "quantity" => $bundle["quantity"],
                "item" => $bundle,
            ];

        }, $snapshot["customer_bundles"]);
        
        return response()->json([
            "status" => 1,
            "data" => [
                "id" => $order->id,
                "status" => 0,
                "status_string" => $order->status,
                "is_scheduled" => !is_null($order->schedule_interval_id),
                "interval" => $order->interval,
                "created_at" => $snapshot["created_at"],
                "reference" => $snapshot["reference"],
                "first_order_discount" => 0,
                "sub_total_price" => (float) $snapshot["sub_total"],
                "delivery_charge" => (float) $snapshot["delivery_charge"],
                "discount" => (string) $snapshot["discount"],
                "total_price" => (float) $snapshot["total"],
                "delivery_date" => $snapshot["delivery_information"]["date"],
                "delivery_time" => $snapshot["delivery_information"]["time"],
                "delivery_address" => $snapshot["delivery_information"]["address"],
                "products" => $products,
                "bundles" => $bundles,
                "customer_bundles" => $customer_bundles,
            ]
        ], 200);

    }

    public function allorders(Request $request)
    {
        $user = $request->user();

        $per_page = (int) $request->query("per_page", 15);

        $orders = Orders::where("customer_id", $user->id);
        
        // filter scheduled orders
        $orders = $orders->when($request->has("scheduled"), function($orders) {

            return $orders->where(function($orders) {
                $orders->whereNotNull("schedule_start_date")
                        ->whereNotNull("schedule_interval_id")
                        ->whereNotNull("schedule_next_date");
            });

        });

        $collection = new OrdersCollection($orders->paginate($per_page));

        return $collection->additional([
            "status" => 1
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function make_order(Request $request, $order_reference)
    {
        $user = $request->user();

        $order = DB::table("orders")->where("order_reference", $order_reference)->first();

        $customer = DB::table("customers")->where("id", $user->id)->first();

        $verification_code = $this->generate_string();
       
        // Send cunstomer email
        if ($customer->email) {

            $email = new \App\Mail\Order($customer, $verification_code);
            Mail::to($customer->contact_email)->send($email);

        }

    }


       public function show_single_order(Request $request, $order_id)
    {
        
        $order = DB::table("orders")->where("id",$order_id)->select("snapshots")->get();
        $order =$order[0]->snapshots;
        $order = unserialize($order);
        // print_r($order);die;

                

        if ($order) {

            return response()->json([
                "status" => 1,
                "data" => $order,
            ], 200);

        }
       
    }


    public function show_all_orders_bck(Request $request)
    {
        
       $per_page = (int) $request->query('per_page', 15);

        $collection = new OrdersCollection(Orders::paginate($per_page));

        return $collection->additional([
            'status' => 1
        ]);
       
    }



        public function show_all_orders(Request $request)
    {
        
         $sorting = $request->query('status');

        // Start the query
         $query = Orders::query();
         $query->orderBy('id', 'desc');
            if (!is_null($sorting)) {

            if($sorting == 'new'){

               $query = $query->where('status', 2);

            }else if ($sorting == 'shipped'){

                  $query = $query->where('status', 3);

            }else if ($sorting == 'delivered'){

                  $query = $query->where('status', 4);

            }else if ($sorting == 'cancelled'){
                echo "string";
                  $query = $query->where('status', 19);
            }
            else{

                $query->orderBy('id', 'desc');
            }

        }
        
       

        $per_page = (int) $request->query('per_page', 14);

        $collection = new OrdersCollection($query->paginate($per_page));

        return $collection->additional([
            'status' => 1
        ]);

}







    public function statuschange(Request $request)
    {
        
        $rules = [
            'status' => [
                'required',
                'integer',
                'min:1',
                'max:64',
            ],
            'order_id' => [
                'required',
            ],
        ];

        $messages = [
            'status.required' => 'Please enter the status.',
            'status.integer' => 'The status should be a integer.',
            'status.min' => 'The status should be at least 1integer long.',
            'status.max' => 'The status should not be longer than 64 characters.',
            'order_id.required' => 'Please provider a order_id',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $order_id = $request->input('order_id');
        
        $status = $request->input('status');

        $order = Orders::find($order_id);

        $order->status = $status;

        $order->save();

        $this->trigger_push($order);

        return response()->json($order, 201);

    }



    //push notification call

    public function trigger_push($order){

            $order_id = $order->id;

            $order_details = DB::table('orders')->where('id', $order_id)->first();

            $order_status =  $order_details->status;
           
            $OrderId =  $order_details->order_reference;

            $customer_id =  $order_details->customer_id;

            $customer_details = DB::table('customers')->where('id', $customer_id)->first();
            
            $name =  $customer_details->name;

            if($order_status == '2'){
                
                $title = 'New Order Placed';

                $message = 'Hello Admin! Order ' .$OrderId.' has been placed';

                $user_message = 'Hello ' .$name. '! Your Order ' .$OrderId.' was successfuly placed';

            }else if($order_status == '3'){

                $title = 'Order Shipped';

                $message = 'Hello Valucart Admin! Order ' .$OrderId.' was successfuly shipped';

                $user_message = 'Hello ' .$name. '! Your Order ' .$OrderId.' was successfuly shipped';
            
            }else if($order_status == '4'){

                $title = 'Order Delivered';

                $message = 'Hello Valucart Admin! Order ' .$OrderId.' was successfuly delivered';

                $user_message = 'Hello ' .$name. '! Your Order ' .$OrderId.' was successfuly delivered';
            }
            else if($order_status == '19'){

                $title = 'Order Cancelled';

                $message = 'Hello Valucart Admin! Order ' .$OrderId.' has been cancelled';

                $user_message = 'Hello ' .$name. '! Sorry your Order ' .$OrderId.' was cancelled for some reason';
            }
            
           
            $settings_data = DB::table('system_settings')->where('id', 1)->first();
            
            $admin_data = DB::table('users')->where('id', 1)->first();
            
            $key = 'AAAAzB0NRgw:APA91bE8BFXH7biQ9KBfEZkW1qLMM4liVPPkDwVt9pM8Zva4HG5IVLqi6yC6Wx80ZBZnVN12vH-Un8xHRU0rSjY95uk4hFI58MwgkEoJlO3Fo_d7h_rQcqfOO5Althay_RleII_iuF_o_dup';


            $fcm_token = 'dayEsxYubdo:APA91bEQn9o9l5cemBK7FA5f4anRTOEkbIjGzMNH4_JvurjRQfrGDJrm0UqHsnLMJE_oT-dX5cp4sYaNHH6fU6rJSlJqcmEOS1A58lQY0Uv5UNR2qfKsXCn0K3h-lZURLAv2LhROmtkB';

            $fcm_data = array('id' => 1, 'title' => $title, 'message' => $user_message);

            $data = "{ \"notification\": { \"title\": \"".$fcm_data['title']."\", \"text\": \"".$fcm_data['message']."\", \"sound\": \"default\" }, \"time_to_live\": 60, \"data\" : {\"response\" : {\"status\" : \"success\", \"data\" : {\"order_id\" : \"".$fcm_data['id']."\", \"order_status\" : 0}}}, \"collapse_key\" : \"order\", \"priority\":\"high\", \"to\" : \"".$fcm_token."\"}";


            $this->send_notification($data,$key);


            $customer_fcm_token = 'd05CjT9rLXQ:APA91bH8jA9CqVXpuo0rmRpIVvf0pDzA_vVcH-jqP0dw22cXRzR4Bh5-Qwc5LpVSVm0g_MT787ueDa8QqIEZajxrvdmhVisZJebwfywuNo9FOb7YuoO75iYf1SEpHDdPMMWIYLZ0tpEB';

            $fcm_data = array('id' => 1, 'title' => $title, 'message' => $message);

            $cust_data = "{ \"notification\": { \"title\": \"".$fcm_data['title']."\", \"text\": \"".$fcm_data['message']."\", \"sound\": \"default\" }, \"time_to_live\": 60, \"data\" : {\"response\" : {\"status\" : \"success\", \"data\" : {\"order_id\" : \"".$fcm_data['id']."\", \"order_status\" : 0}}}, \"collapse_key\" : \"order\", \"priority\":\"high\", \"to\" : \"".$customer_fcm_token."\"}";



         $this->send_customernotification($cust_data,$key);
             
    }




        public function send_notification($data,$key){

            $ch = curl_init("https://fcm.googleapis.com/fcm/send");

            $header = array('Content-Type: application/json', 'Authorization: key='.$key);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $out = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       
            $result = curl_exec($ch);
           


       }


        public function send_customernotification($cust_data,$key){

            $ch = curl_init("https://fcm.googleapis.com/fcm/send");

            $header = array('Content-Type: application/json', 'Authorization: key='.$key);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $cust_data);

            $out = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       
            $result = curl_exec($ch);
           


       }


    public function item_availability(Request $request)
    {
        
        $rules = [
            
            'order_id' => [
                'required',
                'exists:orders,order_reference',
            ],

            'item' => [
                'required',
                Rule::in(['product', 'bundle']),
            ],

            'item_id' => [
                'required',
            ],


            'item_availability' => [
                'required',
                Rule::in(['1', '0']),
            ],

        ];

        $messages = [
            'order_id.required' => 'Pleaser provide the order id.',
            'order_id.exists' => 'The order id seems to be invalid.',
            'item_id.required' => 'Pleaser provide the item id.',
            'item.required' => 'Please provide item.',
            'item.in' => 'item must product or bundle ',
            'item_availability.required' => 'Please provide availabilty status.',
            'item_availability.in' => 'item_availability must 1 or 0 '
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $order_id = $request->input('order_id');
        
        $item_availability = $request->input('item_availability');

        $item = $request->input('item');

        $item_id = $request->input('item_id');

        if($item_availability == 1){

            $product_avail = 'Available';


        }else{

            $product_avail = 'Not Available';

        }

        $order_exists = DB::table('order_fullfillment')->where('order_reference', $order_id)->count();

        if($order_exists == 0){

                $order = DB::table("orders")->where("order_reference",$order_id)->select("snapshots")->get();



        }else{

                $order = DB::table("order_fullfillment")->where("order_reference",$order_id)->select("snapshots")->get();


        }

                $order =$order[0]->snapshots;

                $unserialize_data = unserialize($order);



            if($item == 'product'){
                foreach ( $unserialize_data['products'] as $key => $data) {
                       
                            if ($data['id'] == $item_id) {

                                  $index = $key;
  
                                  break; 
                                }
                }

             $unserialize_data['products'][$index]['availabilty'] = $product_avail;

            }

            if($item == 'bundle'){
                foreach ( $unserialize_data['bundles'] as $key => $data) {
                       
                            if ($data['id'] == $item_id) {

                                  $index = $key;
  
                                  break; 
                                }
                }

             $unserialize_data['bundles'][$index]['availabilty'] = $product_avail;

            }

                //print_r($unserialize_data);die;
            $snapshots = serialize($unserialize_data);

            $order_exists = DB::table('order_fullfillment')->where('order_reference', $order_id)->count();
            
            if($order_exists == 0){


                             DB::table('order_fullfillment')->insert(
                                  ['order_reference' => $order_id,
                                   'status' => 0,
                                   'snapshots' => $snapshots,
                                   'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                   'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                  ]
                             );

            }else{


                            DB::table('order_fullfillment')
                            ->where('order_reference', $order_id)
                            ->update(
                                [
                                    'snapshots' => $snapshots
                         ]);
            }

            return response()->json($unserialize_data, 201);

    }






}
