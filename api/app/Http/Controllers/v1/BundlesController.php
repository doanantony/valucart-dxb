<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Rules\Hashexists;
use App\Models\Bundles;
use App\Http\Resources\BundlesCollection;
use App\Models\Product;

use App\Models\BundleProducts;

use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class BundlesController extends Controller
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
        // Start the query
        $query = Bundles::with('images', 'category');

        $query = $query->orderByRaw("FIELD(name , 'Mutton Vegi Combo' ) DESC");

        $query = $query->orderBy('name', 'asc');

        // Filters
        $category = $request->query('category');
        $query = $query->when(!is_null($category), function($query) use ($category) {

            $categories = explode(',', $category);

            $categories = array_map(function($category) {
                return $this->unhash_id($category);
            }, $categories);

            return $query->whereIn('category_id', $categories);

        });

        $query = $query->when($request->has('popular'), function($query) {
            return $query->where('is_popular', '1');
        });
        
        if (!is_null($sorting)) {

            if($sorting == 'price:low-high'){

                $query->orderBy('valucart_price', 'asc');

            }else if ($sorting == 'price:high-low'){

                 $query->orderBy('valucart_price', 'desc');
            }
            else{

                $query->orderBy('created_at', 'desc');
            }

        }

        $query = $query->where('status',1);
        if ($request->has('page')) {

            $per_page = (int) $request->query('per_page', 15);

            $bundles = $query->paginate($per_page);

        } else {

            $bundles = $query->get();

        }

        // $bundles = $bundles->map(function($bundle, $key) {

            // return [
            //     'id' => Hashids::encode($bundle->id),
            //     'category' => Hashids::encode($bundle->category_id),
            //     'name' => $bundle->name,
            //     'description' => $bundle->description,
            //     'item_count' => $bundle->item_count,
            //     'maximum_selling_price' => $bundle->price,
            //     'valucart_price' => $bundle->valucart_price,
            //     'percentage_discount' => $bundle->percentage_discount,
            //     'is_popular' => (boolean) $bundle->is_popular,
            //     'thumbnail' => $bundle->thumbnail,
            // ];

        // });

        $collection = new BundlesCollection($bundles);

        return $collection->additional([
            'status' => 1
        ]);

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

            'category' => [
                'required',
                new Hashexists('bundles_categories', 'id')
            ],

            'name' => [
                'required',
                'string',
                'min:3',
                'max:64',
                'unique:bundles,name',
            ],

            'description' => [
                'required',
                'string',
                'min:3',
                'max:512',
            ],

        ];

        $messages = [
            'category.required' => 'Please provider a Category for this Bundle',
            'name.required' => 'Please enter the name of the Bundle.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The Bundle :input already exists.',
            'description.required' => 'Please enter the description of the Bundle.',
            'description.min' => 'The description should be at least 3 characters long.',
            'description.max' => 'The description should not be longer than 512 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $bundle = Bundles::create([
            'name' => $request->input('name'),
            'category_id' => $this->unhash_id($request->input('category')),
            'description' => $request->input('description')
        ]);
        
        return response()->json($bundle, 201);

    }

    /**
     * Update an existing bundle
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function update(Request $request, $bundle_id)
    {

        $bundle_id = $this->unhash_id($bundle_id);

        $bundle = Bundles::find($bundle_id);

        if (is_null($bundle)) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => 'Unknown bundle.'
            ], 404);
            
        }

        $rules = [

            'category' => [
                'nullable',
                new Hashexists('bundles_categories', 'id')
            ],

            'name' => [
                'nullable',
                'string',
                'min:3',
                'max:64',
                Rule::unique('bundles', 'name')->ignore($bundle->id),
            ],

            'description' => [
                'nullable',
                'string',
                'min:3',
                'max:512',
            ],

            'price' => [
                'nullable',
                'numeric',
            ],

            'popular' => [
                'nullable',
                'boolean'
            ],

            'thumbnail' => [
                'nullable',
                'file',
                'image',
                'max:10240',
            ],

            'images.*' => [
                'file',
                'image',
                'max:10240',
            ],

        ];

        $messages = [
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The Bundle :input already exists.',
            'description.min' => 'The description should be at least 3 characters long.',
            'description.max' => 'The description should not be longer than 512 characters.',
            'price.numeric' => 'Price must be a number',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        if ($request->has('name')) {
            $bundle->name = $request->input('name');
        }

        if ($request->has('category')) {
            $bundle->category_id = $this->unhash_id($request->input('category'));
        }

        if ($request->has('description')) {
            $bundle->description = $request->input('description');
        }

        if ($request->has('price')) {
            $bundle->valucart_price = $request->input('price');
        }

        if ($request->has('popular')) {
            $bundle->is_popular = !!$request->input('popular') ? 1 : 0 ;
        }

        $bundle->save();

        \DB::beginTransaction();

            try {
                
                $bunble_images = [];

                // Handle thumbnail
                if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                   
                    $thumbnail = $request->file('thumbnail');
        
                    $thumbnail_name = 'thumb.' . $thumbnail->extension();
        
                    $thumbnail->storeAs('bundles', Hashids::encode($bundle->id) . '/' . $thumbnail_name, 's3');

                    array_push($bunble_images, [
                        'bundle_id' => $bundle->id,
                        'is_thumb' => 1,
                        'path' => $thumbnail_name
                    ]);

                }
                
                // Handle images
                if ($request->hasFile('images')) {
                    
                    $images = $request->file('images');
                    
                    foreach ($images as $image) {
                        
                        if (!$image->isValid()) {
                            continue;
                        }

                        $image_filename = $image->getClientOriginalName();

                        $image->storeAs('bundles', Hashids::encode($bundle->id) . '/' . $image_filename, 's3');

                        array_push($bunble_images, [
                            'bundle_id' => $bundle->id,
                            'is_thumb' => 0,
                            'path' => $image_filename
                        ]);

                    }

                }

                if (count($bunble_images) > 0) {
                    \DB::table('bundles_images')->insert($bunble_images);
                }

            } catch (\Throwable $e) {

                \DB::rollback();
                throw $e;

            }

        \DB::commit();

        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => Hashids::encode($bundle->id),
                'category' => Hashids::encode($bundle->category_id),
                'name' => $bundle->name,
                'description' => $bundle->description,
                'item_count' => $bundle->item_count,
                'maximum_selling_price' => $bundle->price,
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $bundle_id)
    {
        
        $bundle_id = $this->unhash_id($bundle_id);

        $bundle = Bundles::with('products', 'products.images', 'images', 'category')
            ->where('id', $bundle_id)
            ->first();

        if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Bundles not found'
            ], 404);

        }

        $thumbnail = $bundle->images->filter(function ($image, $key) {
            return $image->is_thumb == 1;
        });

        $images = $bundle->images->filter(function ($image, $key) {
            return $image->is_thumb != 1;
        });

      //  $products = $bundle->products()->with('images','packaging_quantity_unit')->get();

        if ($request->has('with_atl_products')) {
            
            $products = $bundle->get_products_with_alternatives();

        } else {

            $products = $bundle->get_products_with_quantity();

        }

      // $products = $bundle->products()->with('images','packaging_quantity_unit')->get();

        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => Hashids::encode($bundle->id),
                'category' => Hashids::encode($bundle->category_id),
                'name' => $bundle->name,
                'description' => $bundle->description,
                'item_count' => $bundle->item_count,
                'maximum_selling_price' => $bundle->price,
                'valucart_price' => $bundle->valucart_price,
                'percentage_discount' => $bundle->percentage_discount,
                'is_popular' => $bundle->is_popular,
                'images' => $bundle->get_image_urls(),
                'thumbnail' => $bundle->thumbnail,
                'inventory' => $bundle->inventory,
                'products' => $products,
            ]
        ];

        return response()->json($prepared_response, 200);

    }

    public function edit_quantity(Request $request)
    {
       $rules = [

            'bundle_id' => [
                'required',
                new Hashexists('bundles', 'id')
            ],
            'product_id' => [
                'required',
                new Hashexists('products', 'id')
            ],
            'quantity' => [
                'required',
            ]

        ];

        $messages = [
            'bundle_id.required' => 'Please provide the bundle id',
            'product_id.required' => 'Please provide the product id',
            'quantity.integer' => 'The quantity should be a integer',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);

        }

        $quantity = $request->input('quantity');

        $bundle_id = $request->input('bundle_id');
        $unhased = Hashids::decode($bundle_id);
        $decoded_bundle_id = array_shift($unhased);

        $product_id = $request->input('product_id');
        $unhased = Hashids::decode($product_id);
        $decoded_product_id = array_shift($unhased);

        DB::table('bundles_products')
            ->where('bundle_id', $decoded_bundle_id)
            ->where('product_id', $decoded_product_id)
            ->update(['quantity' => $quantity]);

        return response()->json([
                        'status' => 1,
                    ], 200);
                        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $bundle_id)
    {
        
        $bundle_id = $this->unhash_id($bundle_id);

        $bundle = Bundles::find($bundle_id);

        if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Bundles not found'
            ], 404);

        }

        \DB::beginTransaction();

        try {

            $bundle->images()->delete();
            $bundle->delete();

            Storage::disk('s3')->deleteDirectory('bundles/' . $bundle->id);

        } catch (\Throwable $e) {

            \DB::rollback();
            throw $e;

        }

        \DB::commit();

        return response('', 204);

    }




    //admin Apis


    protected function update_bundle_image(Request $request, $bundle_id)
    {   


        $bundle = Bundles::find($bundle_id);


        if (is_null($bundle)) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => 'Unknown bundle.'
            ], 404);
            
        }

        $bundle->save();

        $bunble_images = [];


        if ($request->hasFile('bundleimage') && $request->file('bundleimage')->isValid()) {

             $encode =  Hashids::encode($bundle->id);
      
             DB::table('bundles_images')->where('bundle_id', $bundle_id)->delete();

                    $thumbnail = $request->file('bundleimage');
                    
                    $current_timestamp = time();
                    
                    $thumbnail_name = 'thumb_'. $current_timestamp.'.'. $thumbnail->extension();
                   
                    $thumbnail->storeAs('bundles', Hashids::encode($bundle->id) . '/' . $thumbnail_name, 's3');
                
                    array_push($bunble_images, [
                        'bundle_id' => $bundle->id,
                        'is_thumb' => 1,
                        'path' => $thumbnail_name
                    ]);

        }


         if (count($bunble_images) > 0) {
                    \DB::table('bundles_images')->insert($bunble_images);
                }

           

        \DB::commit();



        
        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => $bundle->id,
            ]
        ];

        return response()->json($prepared_response, 200);



    }



    //


   public function show_single_bundle(Request $request, $bundle_id)
    {
        
      //  $bundle_id = $this->unhash_id($bundle_id);

        $bundle = Bundles::with('products', 'products.images', 'images', 'category')
            ->where('id', $bundle_id)
            ->first();

        if (is_null($bundle)) {

            return response()->json([
                'status' => 0,
                'message' => 'Bundles not found'
            ], 404);

        }

        $thumbnail = $bundle->images->filter(function ($image, $key) {
            return $image->is_thumb == 1;
        });

        $images = $bundle->images->filter(function ($image, $key) {
            return $image->is_thumb != 1;
        });

      //  $products = $bundle->products()->with('images','packaging_quantity_unit')->get();

        if ($request->has('with_atl_products')) {
            
            $products = $bundle->get_products_with_alternatives();

        } else {

            $products = $bundle->get_products_with_quantity();

        }

      // $products = $bundle->products()->with('images','packaging_quantity_unit')->get();

        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => Hashids::encode($bundle->id),
                'category' => Hashids::encode($bundle->category_id),
                'name' => $bundle->name,
                'description' => $bundle->description,
                'item_count' => $bundle->item_count,
                'maximum_selling_price' => $bundle->price,
                'valucart_price' => $bundle->valucart_price,
                'percentage_discount' => $bundle->percentage_discount,
                'is_popular' => $bundle->is_popular,
                'images' => $bundle->get_image_urls(),
                'thumbnail' => $bundle->thumbnail,
                'inventory' => $bundle->inventory,
                'products' => $products,
            ]
        ];

        return response()->json($prepared_response, 200);

    }













}
