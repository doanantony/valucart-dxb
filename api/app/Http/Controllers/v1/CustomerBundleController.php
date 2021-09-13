<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use Throwable;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\CustomerBundle;
use Illuminate\Validation\Rule;

use App\Http\Resources\CustomerBundle as CustomerBundleResource;
use App\Http\Resources\CustomerBundleCollection;
use Hashids;
use App\Rules\Hashexists;
use App\Models\Cart;

use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class CustomerBundleController extends Controller
{

    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $user = $request->user();

        $bundles = CustomerBundle::whereNull('deleted_at')
                                    ->where('customer_id', $user->id)
                                    ->get();


        if ($bundles->count() < 1) {

            return response()->json([
                'status' => 0,
                'message' => 'Customer Bundles not found'
            ], 404);

        }

        $per_page = (int) $request->query('per_page', 15);

        $query = CustomerBundle::whereNull('deleted_at')->where('customer_id', $user->id);

        $collection = new CustomerBundleCollection($query->paginate($per_page));

        return $collection->additional([
            'status' => 1
        ]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_bck(Request $request)
    {
       // $user = $request->user();

        $products = $request->input('products');

        $rules = [
            'products.*.quantity' => 'required|integer'
        ];

        $messages = [
            'products.*.quantity.required' => 'Please provide the product\'s quantity',
            'products.*.quantity.integer' => 'Product quantity must be an integer.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function($validator) use ($products) {

            $products_ids = array_map(function($product) {
                return $this->unhash_id($product['product_id']);
            }, $products);

            $valid_ids = Product::query()->whereIn('id', $products_ids)->count();

            if (count($products_ids) != $valid_ids) {
                $validator->errors()->add('products', 'Some of the product id were invalid!');
            }

        });

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ]);

        }


        DB::beginTransaction();

        try {

            // Create the bundle

            $bundle = CustomerBundle::create([
                'name' => 'Bundle',
               // 'customer_id' => $user->id,
             //   'description' => $request->input('description'),
            ]);

            // insert products

            $products = array_map(function($product) use ($bundle) {

                return [
                    'bundle_id' => $bundle->id,
                    'product_id' => $this->unhash_id($product['product_id']),
                    'quantity' => $product['quantity'],
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];

            }, $products);

            DB::table('customer_bundles_products')->insert($products);
            // $bundle->products()->createMany();

        } catch (Throwable $e) {

            DB::rollBack();

            throw $e;

        }

        DB::commit();

       // return $bundle;

        return response()->json([
                        'status' => 1,
                        'data' => $bundle
                    ], 200);



    }


    public function store(Request $request)
    {   
        $rules = [
            'action' => [
                'required',
                'string',
                'min:3',
                'max:10',
                Rule::in(['add','subtract'])
            ],
            'product_id' => [
                'required',
            ],
        ];

        $messages = [
            'action.required' => 'Please provide the action(add).',
            'action.string' => 'The action should be a string.',
            'action.min' => 'The action should be at least 3 characters long.',
            'action.max' => 'The action should not be longer than 10 characters.',
            'product_id.required' => 'Please provider Product Id'
        ];

        // $validator = Validator::make($request->all(), $rules, $messages);

        // $validator->validate();

        // if ($validator->fails()) {

        //     return response()->json([
        //         'status' => 0,
        //         'message' => 'The given data was invalid.',
        //         'errors' => $validator->errors(),
        //     ]);

        // }


        // $item_id = $request->input('product_id');

        // $unhased = Hashids::decode($item_id);

        // $decoded_item_id = array_shift($unhased);
        
        $bundle = CustomerBundle::create([
                'name' => 'Bundle',
               // 'customer_id' => $user->id,
             //   'description' => $request->input('description'),
            ]);


        DB::table('customer_bundles_products')->insert(
                [
                    'bundle_id' => $bundle->id, 
                    'product_id' => $decoded_item_id,
                    'quantity' => 1,
                ]
            );


        return response()->json([
                        'status' => 1,
                        'data' => $bundle
                    ], 200);


    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    


    public function show(Request $request, $customerbundle_id)
    {

        $bundle = CustomerBundle::find($customerbundle_id);

         if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Customer Bundle not found'
            ], 404);

        }


        // $discount = ($bundle->get_price() - $bundle->get_discount_price());

        return response()->json([
            'status' => 1,
            'data' => $bundle,
        ], 200);

    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $products = $request->input('products');
        
        $rules = [
            'product_id' => 'required',
            'action' =>'required',
            'bundle_id' =>'nullable'
        ];

        $messages = [
            'action.required' => 'Please provide an action (add or subtract)',
            'bundle_id.required' => 'Please provide  bundle id',
            'product_id.required' => 'Please provide  product id',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

    

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ]);

        }

        $actions = $request->input('action');
        $bundle_id = $request->input('bundle_id');
        $product_id = $request->input('product_id');
        $unhased = Hashids::decode($product_id);
        $decoded_product_id = array_shift($unhased);


        if (is_null($bundle_id)) {

            $bundle = CustomerBundle::create([
                'name' => 'Bundle',
               // 'customer_id' => $user->id,
             //   'description' => $request->input('description'),
            ]);
            
            DB::table('customer_bundles_products')->insert(
                [
                    'bundle_id' => $bundle->id, 
                    'product_id' => $decoded_product_id,
                    'quantity' => 1,
                ]
            );

            return response()->json([
                'status' => 1,
                'data' => $bundle
            ], 200);

        }

        if($actions == "add"){
                

                $item_exists = DB::table('customer_bundles_products')
                    ->where('bundle_id', $bundle_id)
                    ->where('product_id', $decoded_product_id)
                    ->exists();
                  
               

                if($item_exists){

                        $product = DB::table('customer_bundles_products')
                                    ->where('bundle_id', $bundle_id)
                                    ->where('product_id', $decoded_product_id)
                                    ->first();

                        $offer_exists = DB::table('products')
                                        ->where('id', $decoded_product_id)
                                        ->where('is_offer', '1')
                                        ->exists();



                        if($offer_exists && $product->quantity == 1){

                                return response()->json([
                                        'status' => 0,
                                        'message' => 'Product on offer can only be added once to the bundle.'
                                    ], 200);

                        }

                        if($product->quantity >= 5){

                                return response()->json([
                                        'status' => 0,
                                        'message' => 'You can only add 5 of a single item to the bundle.'
                                    ], 200);

                        }



                    DB::table('customer_bundles_products')
                    ->where('bundle_id',$bundle_id)
                    ->where('product_id',$decoded_product_id)
                    ->increment('quantity');

                }else{

                    DB::table('customer_bundles_products')->insert(
                                [
                                    'bundle_id' => $bundle_id, 
                                    'product_id' => $decoded_product_id,
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                ]
                     );

                }

                

        }elseif($actions == "subtract"){
                
                 $quantity = DB::table('customer_bundles_products')->where('bundle_id',$bundle_id)->where('product_id',$decoded_product_id)->first();

                 if($quantity->quantity == 1){

                        DB::table('customer_bundles_products')
                            ->where('bundle_id',$bundle_id)
                            ->where('product_id',$decoded_product_id)
                            ->delete();
                            
                 }else{
                            DB::table('customer_bundles_products')
                            ->where('bundle_id',$bundle_id)
                            ->where('product_id',$decoded_product_id)
                            ->decrement('quantity');
                 }
                

        }else{

                 DB::table('customer_bundles_products')
                    ->where('bundle_id',$bundle_id)
                    ->where('product_id',$decoded_product_id)
                    ->delete();
        }
        

        $bundle = CustomerBundle::find($bundle_id);

         if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Customer Bundle not found'
            ], 404);

        }


        // $discount = ($bundle->get_price() - $bundle->get_discount_price());

        return response()->json([
            'status' => 1,
            'data' => $bundle,
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bundle_id)
    {
        $bundle = CustomerBundle::find($bundle_id);

         if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Bundle not found'
            ], 404);

        }


        $validation_rules = [

            'name' => [
                'required',
                'string',
                'min:3',
                'max:64',
            ],

        ];

        $validation_messages = [
            'name.required' => 'Please provide a name for the Bundle.',
            'name.string' => 'The name of a bundle should be a string.',
            'name.min' => 'The bundle name should be at least 3 characters long.',
            'name.max' => 'The bundle name should not be longer te 64 characters'
        ];

        $validator = Validator::make($request->all(), $validation_rules, $validation_messages);

        if ($validator->fails()) {

            return response()->json([
                'errors' => $validator->errors()
            ], 422);

        }

        DB::table('customer_bundles')
            ->where('id', $bundle_id)
            ->update(['name' => $request->input('name')]);

        $customer_bundle = CustomerBundle::find($bundle_id);

         return response()->json([
            'status' => 1,
            'data' => $customer_bundle->toArray()
        ], 200);
    }


    public function update_customer_id(Request $request, $bundle_id)
    {
        $bundle = CustomerBundle::find($bundle_id);

         if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Bundle not found'
            ], 404);

        }

        $user = $request->user();

        DB::table('customer_bundles')
            ->where('id', $bundle_id)
            ->update(['customer_id' => $user->id ]);

        $customer_bundle = CustomerBundle::find($bundle_id);

         return response()->json([
            'status' => 1,
            'data' => $customer_bundle->toArray()
        ], 200);
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


    public function delete(Request $request)
    {

        $rules = [

            'bundle_id' => [
                'required',
            ],

        ];

        $messages = [
            'bundle_id.required' => 'Please provide a customer bundle id.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);

        }

        $bundle_id = $request->input('bundle_id');

        $bundle = CustomerBundle::find($bundle_id);

         if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Customer Bundle not found'
            ], 404);

        }

       $customer = $request->user();

       $cart = Cart::find($customer->id);
      

        if ($cart->has($bundle)) {

            $cart->remove($bundle);

       }

        


       $delete_time = Carbon::now()->format('Y-m-d H:i:s');

       DB::table('customer_bundles')
            ->where('id', $bundle_id)
            ->update(['deleted_at' => $delete_time]);



       $user = $request->user();

       $customers = DB::table('customer_bundles')->whereNull('deleted_at')->where('customer_id', $user->id)->get();
       $count = $customers->count();

        //  if ($count == 0) {

        //     return response()->json([
        //         'status' => 0,
        //         'message' => 'Customer Bundles not found'
        //     ], 404);

        // }

        $per_page = (int) $request->query('per_page', 15);

        $query = CustomerBundle::query()->whereNull('deleted_at')->where('customer_id', $user->id);
        
        $collection = new CustomerBundleCollection($query->paginate($per_page));

        return $collection->additional([
            'status' => 1,
            'item_count' => $cart->item_count
        ]);
        


    }




}
