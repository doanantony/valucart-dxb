<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Brand;
use App\Http\Resources\BrandsCollection;
use App\Http\Resources\Brand as BrandResource;

use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class BrandsController extends Controller
{

    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $per_page = (int) $request->query('per_page', 15);

        $collection = new BrandsCollection(Brand::paginate($per_page));

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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:64',
                'unique:brands,name',
            ],
        ];

        $messages = [
            'name.required' => 'Please enter the name of the brand.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The brand ":input" already exists.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $brand = Brand::create([
            'name' =>$request->input('name')
        ]);
        
        return response()->json($brand, 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $brand_id)
    {   
        
        $unhased = Hashids::decode($brand_id);
        $decoded_id = array_shift($unhased);
        
        $brand = Brand::find($decoded_id);

        if (is_null($brand)) {

            return response()->json([
                'status' => 0,
                'message' => 'Brand not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $brand->toArray()
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
}
