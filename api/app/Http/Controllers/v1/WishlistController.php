<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;
use App\Models\Wishlist;

use Illuminate\Support\Facades\DB;
use App\Rules\Hashexists;
use App\Models\Product;
use App\Models\Bundles;

use App\Http\Resources\WishlistCollection;


class WishlistController extends Controller
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

        $wishlist = Wishlist::where('customer_id', $user->id)->get();

        if ($wishlist->count() <= 0) {

            return response()->json([
                'status' => 1,
                'data' => [
                    'products' => [],
                    'bundles' => [],
                    'total_items' => 0,
                ]
            ], 200);

        }

        $wishlist_products = [];

        $wishlist_bundles = [];

        foreach ($wishlist as $item) {

            if($item->item_type == 'product')
            {
                $wishlist_products[] = $item->item_id;
            }

            if($item->item_type == 'bundle')
            {
                $wishlist_bundles[] = $item->item_id;
            }

        }


        if (!empty($wishlist_products)) {

            $wishlist_products = Product::with('images','packaging_quantity_unit')->whereIn('id', $wishlist_products)->get();

        }

        if (!empty($wishlist_bundles)) {

            $wishlist_bundles = Bundles::whereIn('id', $wishlist_bundles)->get();

        }


        return response()->json([
            'status' => 1,
            'data' => [
                'products' => $wishlist_products,
                'bundles' => $wishlist_bundles,
                'total_items' => $wishlist->count(),
            ]
        ], 200);


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
       $user = $request->user();
    
       $rules = [
            'action' => [
                'required',
                'string',
                'min:3',
                'max:10',
                Rule::in(['add','remove'])
            ],
            'item_id' => [
                'required',
            ],
            'item_type' => [
                'required',
                'string',
                'min:4',
                'max:25',
                Rule::in(['product','bundle'])
            ],
        ];

        $messages = [
            'action.required' => 'Please provide the action(add or remove).',
            'action.string' => 'The action should be a string.',
            'action.min' => 'The action should be at least 3 characters long.',
            'action.max' => 'The action should not be longer than 10 characters.',
            'item_id.required' => 'Please provider an Item Id',
            'item_type.required' => 'Please provide the Item type(bundle or product).',
            'item_type.string' => 'The Item type should be a string.',
            'item_type.min' => 'The Item Type should be at least 3 characters long.',
            'item_type.max' => 'The Item Type should not be longer than 10 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate(); 

        $user = $request->user();

        $action = $request->input('action');

        $item_type = $request->input('item_type');

        $unhased = Hashids::decode($request->input('item_id'));

        $decoded_item_id = array_shift($unhased);


        if($action == "add"){

            $item_exists = DB::table('wishlist')
                ->where('item_id', $decoded_item_id)
                ->where('item_type', $item_type)
                ->where('customer_id',$user->id)
                ->exists();

            if($item_exists){

             return response()->json([
                        'status' => 0,
                        'message' => 'Item is already added to wishlist'
                    ], 200);
            }

            $wishlist = Wishlist::create([
                'customer_id' => $user->id,
                'item_type'   => $item_type,
                'item_id'     => $decoded_item_id,
            ]);
            
            $total_items = DB::table('wishlist')->where('customer_id',$user->id)->count();

            $wishlist = Wishlist::where('customer_id', $user->id)->get();

            $wishlist_products = [];

            $wishlist_bundles = [];

            foreach ($wishlist as $item) {

                if($item->item_type == 'product')
                {
                    $wishlist_products[] = $item->item_id;
                }

                if($item->item_type == 'bundle')
                {
                    $wishlist_bundles[] = $item->item_id;
                }

            }

            if (!empty($wishlist_products)) {

            $wishlist_products = Product::with('images','packaging_quantity_unit')->whereIn('id', $wishlist_products)->get();

            }

            if (!empty($wishlist_bundles)) {

                $wishlist_bundles = Bundles::whereIn('id', $wishlist_bundles)->get();

            }


            return response()->json([
                'status' => 1,
                'data' => [
                    'products' => $wishlist_products,
                    'bundles' => $wishlist_bundles,
                    'total_items' => $wishlist->count(),
                ]
            ], 200);


        
        }else
        {

            $item_exists = DB::table('wishlist')
                ->where('item_id', $decoded_item_id)
                ->where('item_type', $item_type)
                ->where('customer_id',$user->id)
                ->exists();

            if(!$item_exists){

             return response()->json([
                        'status' => 0,
                        'message' => 'Item is not found in wishlist'
                    ], 200);
            }


            DB::table('wishlist')
                            ->where('customer_id',$user->id)
                            ->where('item_id',$decoded_item_id)
                            ->where('item_type',$item_type)
                            ->delete();

            $total_items = DB::table('wishlist')->where('customer_id',$user->id)->count();

            $wishlist = Wishlist::where('customer_id', $user->id)->get();

            $wishlist_products = [];

            $wishlist_bundles = [];

            foreach ($wishlist as $item) {

                if($item->item_type == 'product')
                {
                    $wishlist_products[] = $item->item_id;
                }

                if($item->item_type == 'bundle')
                {
                    $wishlist_bundles[] = $item->item_id;
                }

            }

                if (!empty($wishlist_products)) {

                $wishlist_products = Product::with('images','packaging_quantity_unit')->whereIn('id', $wishlist_products)->get();

                }

                if (!empty($wishlist_bundles)) {

                    $wishlist_bundles = Bundles::whereIn('id', $wishlist_bundles)->get();

                }

            return response()->json([
                'status' => 1,
                'data' => [
                    'products' => $wishlist_products,
                    'bundles' => $wishlist_bundles,
                    'total_items' => $wishlist->count(),
                ]
            ], 200);
            

                                
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy(Request $request)
    {   

        $user = $request->user();

        DB::table('wishlist')
                            ->where('customer_id',$user->id)
                            ->delete();
                            
         return response()->json([
                'status' => 1,
                'data' => [
                    'products' => [],
                    'bundles' => [],
                    'total_items' => 0,
                ]
            ], 200);
           


    }
}
