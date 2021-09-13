<?php

namespace App\Http\Controllers\v1;

use Illuminate\Support\Facades\DB;

use Hashids;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

use App\Models\Subcategory;
use App\Models\Product;
use App\Models\FeaturedProduct;
use App\Http\Resources\FeaturedProductsCollection;
use App\Http\Resources\FeaturedProduct as FeaturedProductResource;

use App\Rules\Hashexists;
use App\Rules\Uniquehash;
use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class FeaturedProductsController extends Controller
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
        $subcategory_id = $request->query('subcategory_id');

        if (is_null($subcategory_id)) {

            $sub_featuredproducts = DB::table('products')
            ->Join('featured_products', 'products.id', '=', 'featured_products.product_id')
            ->paginate();

            $sub_featuredproducts = $sub_featuredproducts->map(function($product, $key) {
                $product->id = Hashids::encode($product->id);
                $product->brand_id = Hashids::encode($product->brand_id);
                return $product;
            });

             return response()->json([
                'status' => 1,
                'data' => $sub_featuredproducts,
            ], 201);


        }else{

        $unhased = Hashids::decode($subcategory_id);
        $decoded_id = array_shift($unhased);
        
        $subcategory = Subcategory::find($decoded_id);

        if (is_null($subcategory)) {

            return response()->json([
                'status' => 0,
                'message' => 'Subcategory not found'
            ], 404);

        }

        $sort_by ='';

        $val='';

        if (!is_null($sorting)) {

            if($sorting == 'price:low-high'){

                $sort_by =  'products.valucart_price';
                $val =  'asc' ;

            }else if ($sorting == 'price:high-low'){

                 $sort_by =  'products.valucart_price';
                 $val =  'desc' ;
            }else{
                $sort_by = 'products.created_at' ;
                $val =  'desc' ;
            }
            

        }
        
        if (!empty($sort_by && $val)){
            //print_r($decoded_id);die;
            $sub_featuredproducts = DB::table('products')
            ->Join('featured_products', 'products.id', '=', 'featured_products.product_id')
            ->where('products.subcategory_id', $decoded_id)
            ->orderBy($sort_by,$val)
            ->get();

        }else{

            $sub_featuredproducts = DB::table('products')
            ->Join('featured_products', 'products.id', '=', 'featured_products.product_id')
            ->where('products.subcategory_id', $decoded_id)
            ->get();

        }

    
       
        return response()->json([
                'status' => 1,
                'data' => $sub_featuredproducts,
            ], 201);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $rules = [
            'product' => [
                'required',
                new Hashexists('products', 'id'),
                new Uniquehash('featured_products', 'product_id'),
            ]
        ];

        $messages = [
           // 'product.unique' => 'The brand ":input" already exists.',
            'product.required' => 'Please provide a product',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $featuredproducts = FeaturedProduct::create([
            'product_id' => $this->unhash_id($request->input('product')),
        ]);
        
        return response()->json($featuredproducts, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $product_id)
    {   
        
        $product = Product::find($product_id);

        if (is_null($product)) {

            return response()->json([
                'status' => 0,
                'message' => 'Featured Product not found'
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
}
