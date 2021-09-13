<?php

namespace App\Http\Controllers\v1;

use Illuminate\Support\Facades\Storage;
use Hashids;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

use App\Models\Product;
use App\Http\Resources\ProductsCollection;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Support\Facades\DB;

use App\Rules\Hashexists;
use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{

    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
      

        $sorting = $request->query('sorting');
        $department = $request->query('department');
        $category = $request->query('category');
        $subcategory = $request->query('subcategory');
        $price = $request->query('price');
        $brand = $request->query('brand');
        $community = $request->query('community');
        $community = $request->query('community');

        $query = Product::with(
            'brand',
            'images',
            'category',
            'subcategory',
            'department',
            'packaging_quantity_unit'
        );

        $query = $query->where("published", "1");
        $query = $query->whereNotNull("packaging_quantity_unit_id");

        $query = $query->when(!is_null($department), function($query) use ($department) {

            $departments = explode(',', $department);

            $departments = array_map(function($department) {
                return $department;
            }, $departments);

            return $query->whereIn('department_id', $departments);

        });

        $query = $query->when(!is_null($category), function($query) use ($category) {
            
            $categories = explode(',', $category);

            $categories = array_map(function($category) {
                return $this->unhash_id($category);
            }, $categories);

            return $query->whereIn('category_id', $categories);

        });

        $query = $query->when(!is_null($subcategory), function($query) use ($subcategory) {
            
            $subcategories = explode(',', $subcategory);

            $subcategories = array_map(function($subcategory) {
                return $this->unhash_id($subcategory);
            }, $subcategories);

            return $query->whereIn('subcategory_id', $subcategories);

        });

        $query = $query->when(!is_null($price), function($query) use ($price) {
            
            $p = explode('-', $price);

            $p = array_map(function($a_price) {
                return (float) $a_price;
            }, $p);
            
            if (isset($p[0]) && !isset($p[1])) {
                return $query->where('valucart_price', $p[0]);
            }

            if (isset($p[0]) && isset($p[1])) {
                return $query->whereBetween('valucart_price', $p);
            }

        });

        $query = $query->when(!is_null($brand), function($query) use ($brand) {
            
            $brands = explode(',', $brand);

            $brands = array_map(function($brand) {
                return $this->unhash_id($brand);
            }, $brands);

            return $query->whereIn('brand_id', $brands);

        });

        $query = $query->when(!is_null($community), function($query) use ($community) {
        
            $communities = explode(',', $community);

            $communities = array_map(function($community) {
                return $this->unhash_id($community);
            }, $communities);
        
            return $query->whereHas('communities', function($q) use ($communities) {
                $q->whereIn('community_id', $communities);
            });
     
        });

        $query = $query->when($request->has('bulk'), function($query) {
            return $query->where('is_bulk', '1');
        });

        $query = $query->when($request->has('featured'), function($query) {
            // return $query = $query->orderByRaw("FIELD(sku , ) DESC");

            $skus = [
                '999955590',
                '999955582',
                '6297000044828',
                '5555600012',
                '5555599991',
                '778899995555565',
                '778899995555566',
                '778899995555564',
                '8906001023043',
                '8681371830100'
            ];

            $sql = 'CASE sku ';

            foreach ($skus as $key => $value) {
                $sql .= "WHEN '$value' THEN " . ($key + 1) . " ";
            }

            $sql .= "ELSE " . (count($skus) + 1) . " END";

            return $query->where('is_featured', '1')->orderByRaw($sql);

        });

        $query = $query->when($request->has('meatmonday'), function($query) {

            $category = [
                '291',
                '292',
                '290'
            ];

            $sql = 'CASE category_id ';

            foreach ($category as $key => $value) {
                $sql .= "WHEN '$value' THEN " . ($key + 1) . " ";
            }

            $sql .= "ELSE " . (count($category) + 1) . " END";

            return $query->where('is_customer_bundlable', '1')->orderByRaw($sql);


          // return $query->where('is_customer_bundlable', '1');
        });

        $query = $query->when($request->has('admin_bundlable'), function($query) {
            return $query->where('is_admin_bundlable', '1');
        });

        $query = $query->when($request->has('offers'), function($query) {
            return $query->where('is_offer', '1')
                        ->whereColumn('maximum_selling_price', '>', 'valucart_price');
        });

        $query = $query->when($request->has('q'), function($query) use ($request) {

            $search_query = $request->query('q');

            return $query->where(function($query) use ($search_query) {
                $query->where('name', 'like', '%' . $search_query . '%')
                    ->orWhere('description', 'like', '%' . $search_query . '%')
                    ->orWhere('meta_page_title', 'like', '%' . $search_query . '%')
                    ->orWhere('meta_description', 'like', '%' . $search_query . '%')
                    ->orWhere('meta_keywords', 'like', '%' . $search_query . '%');
            });

        });

        $query = $query->when(!$request->has('meatmonday'), function($query) {
            return $query->where('is_customer_bundlable', '!=', '1');
        });

        if ($sorting) {

            switch ($sorting) {
                case 'price:low-high':
                    $query->orderBy('valucart_price', 'asc');
                    break;
                
                case 'price:high-low':
                    $query->orderBy('valucart_price', 'desc');
                    break;
                
                default:
                    $query->orderBy('created_at', 'desc');
            }

        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $query = $query->has('images');

        //$query = $query->where('published', '1');

        $per_page = (int) $request->query('per_page', 14);

        $collection = new ProductsCollection($query->paginate($per_page));

        if($request->has('meatmonday')){
            return $collection->additional([
            'status' => 1,
            'popup_message' =>'This section is exclusively made available to “pre-order” your meats and get it delivered on Monday’s. All meats are fresh from the butcher!

Terms and Conditions:

- Cutoff time to receive orders is Sunday (9AM)
- Minimum order of AED 200 for Free Delivery
- No other coupons or promotional codes are applicable
- Meat prices may vary due to Covid-19 availability. All price changes would be notified before proceeding with delivery
- Payment by Credit/Debit Cards only

NOTE: IF YOU HAVE ANY OTHER ITEMS IN THE CART, YOU MUST CHECKOUT SEPERATELY!'
        ]);
        }else{
            return $collection->additional([
            'status' => 1
        ]);
        }
        

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function show(Request $request, $product_id)
    {   
        $unhased = Hashids::decode($product_id);
        $decoded_id = array_shift($unhased);
        
        $product = Product::with('packaging_quantity_unit','brand', 'images', 'category', 'subcategory')->find($decoded_id);

        if (is_null($product) || $product->published != "1" || is_null($product->packaging_quantity_unit)) {

            return response()->json([
                'status' => 0,
                'message' => 'Product not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $product->toArray()
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
       $rules = [

            'product_id' => [
                'required',
                new Hashexists('products', 'id')
            ]

        ];

        $messages = [
            'product_id.required' => 'Please provide the product id',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);

        }

        $name = $request->input('name');
        $description = $request->input('description');
        $packaging_quantity = $request->input('packaging_quantity');
        $valucart_price = $request->input('valucart_price');
        $maximum_selling_price = $request->input('maximum_selling_price');


        $product_id = $request->input('product_id');
        $unhased = Hashids::decode($product_id);
        $decoded_product_id = array_shift($unhased);
//print_r($decoded_product_id);die;

        if (!is_null($name)) {

            $name = $request->input('name');

            DB::table('products')
            ->where('id', $decoded_product_id)
            ->update(['name' => $name]);


        }


        if (!is_null($description)) {

            $description = $request->input('description');

            DB::table('products')
            ->where('id', $decoded_product_id)
            ->update(['description' => $description]);


        }
        if (!is_null($packaging_quantity)) {

            $packaging_quantity = $request->input('packaging_quantity');

            DB::table('products')
            ->where('id', $decoded_product_id)
            ->update(['packaging_quantity' => $packaging_quantity]);


        }
        if (!is_null($valucart_price)) {

            $valucart_price = $request->input('valucart_price');

            DB::table('products')
            ->where('id', $decoded_product_id)
            ->update(['valucart_price' => $valucart_price]);


        }


        if (!is_null($maximum_selling_price)) {

            $maximum_selling_price = $request->input('maximum_selling_price');

            DB::table('products')
            ->where('id', $decoded_product_id)
            ->update(['maximum_selling_price' => $maximum_selling_price ]);


        }


       
        return response()->json([
                        'status' => 1,
                    ], 200);
                        
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

    public function get_by_sku(Request $request)
    {

        $sku = $request->query('sku', null);
        
        $product = Product::where('sku', $sku)
            ->with([
                'brand',
                'department',
                'category',
                'subcategory',
                'packaging_quantity_unit',
                'images',
            ])
            ->first();

        if (is_null($product)) {
            
            return response()->json([
                'error' => 'Product not found.'
            ], 404);

        }

        return response()->json($product, 200);

    }



    //api for admin

    protected function update_products_image(Request $request, $product_id)
    {   

      

        $product = Product::find($product_id);

        if (is_null($product)) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => 'Unknown product.'
            ], 404);
            
        }

        $product->save();

        $product_images = [];
      

        if ($request->hasFile('productimage') && $request->file('productimage')->isValid()) {
                   
                    $image = $request->file('productimage');
                    
                   // $image_name = 'thumb.' . $image->extension();
                    $current_timestamp = time();

                    $image_filename = $image->getClientOriginalName().$current_timestamp;
                    
                    $image->storeAs('products_images', $product->sku . '/' . $image_filename, 's3');
                
                    array_push($product_images, [
                        'product_id' => $product->id,
                        'is_thumb' => 0,
                        'path' => $image_filename
                    ]);

        }


         if (count($product_images) > 0) {
                    \DB::table('products_images')->insert($product_images);
                }

           

        \DB::commit();



        
        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => $product->id,
            ]
        ];

        return response()->json($prepared_response, 200);



    }


    protected function update_products_thumpimage(Request $request, $product_id)
    {   

        $product = Product::find($product_id);

        if (is_null($product)) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => 'Unknown product.'
            ], 404);
            
        }

        $product->save();

        $product_images = [];
        $current_timestamp = time();
       
        // Handle thumbnail
                if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                  
                    $thumbnail = $request->file('thumbnail');
        
                    $thumbnail_name = 'thumb' .$current_timestamp.'.' .$thumbnail->extension();
                   
                    $thumbnail->storeAs('products_images', $product->sku . '/' . $thumbnail_name, 's3');

                    array_push($product_images, [
                        'product_id' => $product->id,
                        'is_thumb' => 1,
                        'path' => $thumbnail_name
                    ]);

                }


         if (count($product_images) > 0) {
                    \DB::table('products_images')->insert($product_images);
                }

           

        \DB::commit();



        
        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => $product->id,
            ]
        ];

        return response()->json($prepared_response, 200);



    }



    public function delete_products_image(Request $request)
    {   
       
         if ($request->has('url')) {

             $image_path = $request->input('url');

             $urlparts = parse_url($image_path);

             $extracted = $urlparts['path'];

             $path = substr("$extracted",5);

            
             if(Storage::disk('s3')->exists($path)) {
              
                Storage::disk('s3')->delete($path);

             }

              $prepared_response = [
                        'status' => 1,
                    ];

                return response()->json($prepared_response, 200);

                

         }else{

                    $prepared_response = [
                        'status' => 0,
                    ];

                return response()->json($prepared_response, 200);

         }



    }





}
