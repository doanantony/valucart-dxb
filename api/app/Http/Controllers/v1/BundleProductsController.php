<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;

use Hashids;
use Validator;
use Carbon\Carbon;
use App\Models\Bundles;
use App\Models\Product;
use App\Rules\Hashexists;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class BundleProductsController extends Controller
{

    use ControllerTrait;
    
    public function add_products(Request $request, $bundle_id) {

        $bundle_id = $this->unhash_id($bundle_id);

        $bundle = Bundles::find($bundle_id);

        if (is_null($bundle)) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'error' => [
                    'bundle_id' => 'The bundle seems to be invalid.'
                ]
            ], 404);

        }

        $rules = [

            'product_id' => [
                'required',
                new Hashexists('products', 'id')
            ],

            'quantity' => [
                'nullable',
                'integer'
            ],

        ];

        $messages = [
            'product_id.required' => 'Please provider a product id',
            'quantity.integer' => 'Quantity must be a number.',
        ];

        $quantity = $request->input('quantity', 1);
        $product_id = $this->unhash_id($request->input('product_id'));

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();
        
        // If product is already in the bundle, romove
        $existing_product = $bundle->products()->where('product_id', $product_id)->first();
        if ($existing_product) {

            $existing_product->pivot->quantity = $quantity;
            $existing_product->pivot->save();

        } else {

            $bundle->products()->attach($product_id, [
                'quantity' => $quantity,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

        }

        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => Hashids::encode($bundle->id),
                'category' => Hashids::encode($bundle->category_id),
                'name' => $bundle->name,
                'description' => $bundle->description,
                'item_count' => $bundle->item_count,
                'price' => $bundle->price,
                'valucart_price' => $bundle->valucart_price,
                'percentage_discount' => $bundle->percentage_discount,
                'is_popular' => (boolean) $bundle->is_popular,
                'images' => $bundle->get_image_urls(),
                'thumbnail' => $bundle->thumbnail,
                'products' => $bundle->get_products_with_quantity(),
            ]
        ];

        return response()->json($prepared_response, 200);

    }

    public function remove_product(Request $request, $bundle_id, $product_id)
    {

        $bundle = Bundles::find($this->unhash_id($bundle_id));

        if (is_null($bundle)) {

            return response()->json([
                'message' => 'Bundle not found.',
            ], 404);

        }

        $bundle->products()->detach($this->unhash_id($product_id));

        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => Hashids::encode($bundle->id),
                'category' => Hashids::encode($bundle->category_id),
                'name' => $bundle->name,
                'description' => $bundle->description,
                'item_count' => $bundle->item_count,
                'price' => $bundle->price,
                'valucart_price' => $bundle->valucart_price,
                'percentage_discount' => $bundle->percentage_discount,
                'is_popular' => (boolean) $bundle->is_popular,
                'images' => $bundle->get_image_urls(),
                'thumbnail' => $bundle->thumbnail,
                'products' => $bundle->get_products_with_quantity(),
            ]
        ];

        return response()->json($prepared_response, 200);

    }

    public function add_alt_products(Request $request, $bundle_id, $product_id) {

        $rules = [

            'product' => [
                'required',
                new Hashexists('products', 'id')
            ],
            'quantity' => [
                'nullable',
                'integer'
            ],
        ];
        
        $messages = [
            'alt_product.required' => 'Please provider the alternative product.',
            'quantity.integer' => 'Quantity must be an integer',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $bundle_id = $this->unhash_id($bundle_id);
        $product_id = $this->unhash_id($product_id);
        $alt_product_id = $this->unhash_id($request->input('product'));

        $validator->after(function($validator) use($product_id, $alt_product_id) {

            if ($alt_product_id == $product_id) {
                $validator->errors()->add('product', 'Product and alternative product can not be same.');
            }

        });

        $validator->validate();

        $quantity = $request->input('quantity', 1);

        // Check that bundle - product combination exists
        $bundle_product = \DB::table('bundles_products')
                ->where('bundle_id', $bundle_id)
                ->where('product_id',$product_id)
                ->first();

        if(is_null($bundle_product)){

            return response()->json([
                'status' => 0,
                'message' => 'Invalid bundle - product combination.'
            ], 200);

        }

        $alt_product_query = \DB::table('bundles_products_alternatives')
            ->where('bundles_products_id', $bundle_product->id)
            ->where('product_id', $alt_product_id);

        if($alt_product_query->exists()) {

            $alt_product_query->update(['quantity' => $quantity]);

        } else {

            \DB::table('bundles_products_alternatives')->insert([
                'bundles_products_id' => $bundle_product->id,
                'product_id' => $alt_product_id,
                'quantity' => $quantity, 
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

        }

        $bundle = Bundles::find($bundle_id);
       
        return response($bundle, 200);

    }

    public function remove_alt_products(Request $request, $bundle_id, $product_id, $alt_product_id)
    {

        $bundle_id = $this->unhash_id($bundle_id);
        $product_id = $this->unhash_id($product_id);
        $alt_product_id = $this->unhash_id($alt_product_id);

        // Check that bundle - product combination exists
        $bundle_product = \DB::table('bundles_products')
                ->where('bundle_id', $bundle_id)
                ->where('product_id',$product_id)
                ->first();

        if(is_null($bundle_product)){

            return response()->json([
                'status' => 0,
                'message' => 'Invalid bundle - product combination.'
            ], 422);

        }

        \DB::table('bundles_products_alternatives')
            ->where('bundles_products_id', $bundle_product->id)
            ->where('product_id', $alt_product_id)
            ->delete();

        return response('', 200);

    }

}
