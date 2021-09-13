<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Orders;
use App\Models\Product;
use App\Models\CustomerBundle;
use App\Models\DeliveryTimeSlot;

class MeatMondayController extends Controller
{
    
    public function showCart(Request $request, $cart_id)
    {

        $bundle = CustomerBundle::find($cart_id);

         if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Cart not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $bundle,
        ], 200);

    }

    public function addRemoveProduct(Request $request)
    {
    
        $rules = [
            'action' => [
                'required',
                Rule::in(['add','subtract', 'remove'])
            ],
            'product_id' => [
                'required',
            ],
        ];

        $messages = [
            'action.required' => 'Action is required.',
            'action.in' => 'Action must be one of add, subract or remove.',
            'product_id.required' => 'Please provider Product Id'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $product_id = $request->input('product_id');

        $unhased_id = Hashids::decode($product_id);

        $product_id = array_shift($unhased_id);

        $product = Product::find($product_id);

        if (is_null($product)) {
            
            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'product_id' => [
                        "The product ID seems to be invalid."
                    ]
                ],
            ], 422);

        }

        if (!$product->is_customer_bundlable) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'product_id' => [
                        "Product is not valid for meat monday."
                    ]
                ],
            ], 422);

        }

        $action = $request->input('action');
        $bundle_id = $request->input('cart_id');

        $user = $request->user();

        $bundle = CustomerBundle::with('products')->find($bundle_id);

        $in_bundle = false;

        if (!$bundle) {
            
            $bundle = CustomerBundle::create([
                'customer_id' => $user ? $user->id : null,
                'name' => 'meat monday'
            ]);

        } else if($bundle && is_null($bundle->customer_id) && $user) {

            $bundle->customer_id = $user->id;
            $bundle->save();

        }

        foreach ($bundle->products as $p ) {
            if ($p['product']->id == $product->id) {
                $in_bundle = $p;
                break;
            }
        }

        if ($in_bundle && $action == "add") {

            $attributes = [
                'quantity' => $in_bundle['quantity'] + 1,
            ];

            $bundle->products()->updateExistingPivot($product->id, $attributes);

        } else if ($in_bundle && $action == "subtract") {

            $quantity = $in_bundle['quantity'] <= 0 ? 0 : $in_bundle['quantity'] - 1 ;

            if ($quantity == 0) {

                $bundle->products()->detach($product->id);

            } else {

                $attributes = [
                    'quantity' => $in_bundle['quantity'] <= 0 ? 0 : $in_bundle['quantity'] - 1,
                ];
    
                $bundle->products()->updateExistingPivot($product->id, $attributes);

            }

        } else if ($in_bundle && $action == "remove") {

            $bundle->products()->detach($product->id);

        } else if(!$in_bundle && $action === "add") {

            $bundle->products()->attach($product->id, [ 'quantity' => 1 ]);

        }

        $bundle = $bundle->refresh();

        return response()->json([
            'status' => 1,
            'data' => $bundle
        ], 200);

    }

    function checkout(Request $request) {

        $user = $request->user();

        $rules = [

            "cart_id" => [
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

        $today = now();

        $delivery_date = $today->dayOfWeek >= 7 ? $today->startOfWeek()->addDays(14) : $today->startOfWeek()->addDays(7);

        $cart_id = $request->input("cart_id");
        $address_id = $request->input("address_id");
        $time_slot_id = $request->input("time_slot_id");
        $note = $request->input("note");

        $bundle = CustomerBundle::with('products')->find($cart_id);

        if (is_null($bundle)) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "cart_id" => [
                        "Invalid cart ID"
                    ]
                ]
            ], 422);

        }

        if (!$bundle->can_checkout) {

            $minimum_order = env("MEAT_MONDAY_MINIMUM_ORDER_VALUE");

            return response()->json([
                "status" => 0,
                "message" => "Order should be at least AED" . $minimum_order,
                "errors" => [
                    "minimum_order" => [
                        "Order should be at least AED" . $minimum_order,
                    ]
                ]
            ], 422);

        }

        $delivery_time = DeliveryTimeSlot::find($time_slot_id);

        if (is_null($delivery_time)) {
            
            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "time_slot_id" => ["Invalid delivery time slot"]
                ]
            ], 422);

        }
        
        if (!$delivery_time->is_vailable_for($delivery_date)) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "time_slot_id" => ["The selected delivery time slot is not available."]
                ]
            ], 422);

        }

        $delivery_address = $user->addresses()->where("id", $address_id)->first();

        if (is_null($delivery_address)) {
            
            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "address_id" => ["Invalid delivery addresss"]
                ]
            ], 422);

        }

        $order = Orders::where("cart_id", $cart_id)->where("status", 1)->first();

        if(!$order) {

            $invoice_number = $today->year + 
                    $today->month + 
                    $today->day + 
                    $today->hour + 
                    $today->minute + 
                    $today->second + 
                    mt_rand(100, 1100) + 
                    $user->id;

            $order_reference = "VCMM". $invoice_number;
            
            $discounted_price = $bundle->discount;
            $delivery_charge = $this->delivery_charge($bundle->valucart_price);
            
            $order_snapshoot = [
                "meat_monday" => true,
                "created_at" => now()->format("l jS F Y H:d"),
                "reference" => $order_reference,
                "invoice_no" => "",
                "sub_total" => $bundle->maximum_selling_price,
                "delivery_charge" => round($delivery_charge, 2),
                "discount" => $bundle->discount,
                "vat" => env("VAT"),
                "total" => round(($bundle->valucart_price + $delivery_charge), 2),
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
                "bundles" => [],
                "customer_bundles" => []
            ];
            
            $order = Orders::create([
                "customer_id" => $user->id,
                "order_reference" => $order_reference,
                "delivery_date" => $delivery_date->format("Y-m-d"),
                "address_id" => $address_id,
                "cart_id" => $bundle->id,
                "time_slot_id" => $delivery_time->id,
                "sub_total_price" => $bundle->maximum_selling_price,
                "first_order_discount" => 0,
                "discount" => $bundle->discount,
                "price" => round(($bundle->valucart_price + $delivery_charge), 2),
                "snapshots" => serialize($order_snapshoot),
                "status" => Orders::CREATED,
                "note" => $note,
                "is_meat_monday" => 1
            ]);
            
            return response()->json([
                "status" => 1,
                "data" => [
                    "id" => $order->id, 
                    "customer_id" => $order->customer_id,
                    "payment_type" => $order->payment_type,
                    "sub_total_price"=> $order->sub_total_price,
                    "first_order_discount" =>$order->first_order_discount,
                    "discount" => $bundle->discount,
                    "price" =>$order->price,
                    "order_reference" =>$order->order_reference,
                    "time_slot_id" =>$delivery_time->id,
                    "delivery_date" =>$order->delivery_date,
                    "created_at" =>$order->created_at,
                    "updated_at" =>$order->updated_at,
                    "cart_id" => $order->cart_id,
                    "address_id" =>$order->address_id
                ],
            ], 201);

        }

        if ($order) {
            
            $discounted_price = $bundle->discount;
            $delivery_charge = $this->delivery_charge($bundle->valucart_price);

            $order->sub_total_price = $bundle->maximum_selling_price;
            $order->first_order_discount = 0;
            $order->price = round(($bundle->valucart_price + $delivery_charge), 2);

            $order->delivery_date = $delivery_date->format("Y-m-d");
            $order->address_id = $address_id;
            $order->time_slot_id = $delivery_time->id;
            $order->note = $note;

            $delivery_address = $user->addresses()->where("id", $address_id)->first();

            $order_snapshoot = [
                "meat_monday" => true,
                "sub_total" => $bundle->maximum_selling_price,
                "delivery_charge" => $delivery_charge,
                "discount" => $bundle->discount,
                "discounted_price" => $bundle->valucart_price,
                "total" => round(($bundle->valucart_price + $delivery_charge), 2),
                "delivery_information" => [
                    "address" => (string) $delivery_address,
                    "date" => $delivery_date->format("l jS F Y"),
                    "time" => (string) $delivery_time,
                    "address_data" => (array) $delivery_address,
                ],
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
                "bundles" => [],
                "customer_bundles" => []
            ];

            $order_snapshoot = array_merge(unserialize($order->snapshots), $order_snapshoot);

            $order->snapshots = serialize($order_snapshoot);

            $order->save();

            return response()->json([
                "status" => 1,
                "data" => [
                    "id" => $order->id, 
                    "customer_id" => $order->customer_id,
                    "payment_type" => $order->payment_type,
                    "sub_total_price"=> $order->sub_total_price,
                    "first_order_discount" =>$order->first_order_discount,
                    "discount" => $bundle->discount,
                    "price" =>$order->price,
                    "order_reference" =>$order->order_reference,
                    "time_slot_id" =>$delivery_time->id,
                    "delivery_date" =>$order->delivery_date,
                    "created_at" =>$order->created_at,
                    "updated_at" =>$order->updated_at,
                    "cart_id" => $order->cart_id,
                    "address_id" =>$order->address_id
                ],
            ], 200);

        }

    }

    protected function delivery_charge($price)
    {

        $free_delivery_minimum_order = env("MEAT_MONDAY_FREE_DELIVERY_MINIMUM_ORDER");
        $delivery_charge = env("DELIVERY_CHARGE");
        $vat = env("VAT");

        $delivery_charge = ($price < $free_delivery_minimum_order) ? $delivery_charge : 0;

        if ($delivery_charge > 0) {
            $vat_amount = ($vat / 100) * $delivery_charge;
            $delivery_charge = $delivery_charge + $vat_amount;
        }

        return round(($delivery_charge * 1.0), 2);

    }

}
