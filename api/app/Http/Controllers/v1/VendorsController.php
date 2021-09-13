<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;
use App\Rules\Hashexists;
use App\Models\Vendor;
use App\Http\Resources\VendorsCollection;
use App\Http\Resources\Vendor as VendorResource;

class VendorsController extends Controller
{

    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {

    //     $per_page = (int) $request->query('per_page', 15);
        
    //     $query = Vendor::query();

    //     $query = $query->orderByRaw("FIELD(name , 'meatone' ) DESC");

    //     $query = $query->orderBy('name', 'asc');

    //     $vendors = new VendorsCollection(
    //         Vendor::paginate($per_page)
    //     );

    //     return $vendors->additional([
    //         'status' => 1
    //     ]);

    // }


     public function index(Request $request)
    {

        $per_page = (int) $request->query('per_page', 15);

        $query = Vendor::query();

        $query = $query->where("name", "!=", "vcgt")->whereNotNull("path");

        $query = $query->orderByRaw("FIELD(name , 'Emcooop') DESC");

        $query = $query->orderBy('name', 'asc');

        $collection = new VendorsCollection($query->paginate($per_page));

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
    public function store_bck(Request $request)
    {

        $validation_rules = [

            'name' => [
                'required',
                'string',
                'unique:vendors',
                'min:2',
                'max:50',
            ],
            
            'short_name' => [
                'required',
                'string',
                'unique:vendors',
                'min:2',
                'max:12',
            ]

        ];

        $validation_messages = [
            'name.required' => 'Please provide a name for the new vendor.',
            'name.string' => 'The name of a vendor should be a string.',
            'name.unique' => 'The vendor ":input" alread exists.',
            'name.min' => 'The name should be at least two characters long.',
            'name.max' => 'The name should not be longer te 50 characters',
            'short_name.required' => 'Please provide a short name for the new vendor.',
            'short_name.string' => 'The short name should be a string.',
            'short_name.unique' => 'The vendor short name ":input" is already taken.',
            'short_name.min' => 'The short name should be at least two characters long.',
            'short_name.max' => 'The short name should not be longer te 12 characters'
        ];

        $validator = Validator::make($request->all(), $validation_rules, $validation_messages);

        if ($validator->fails()) {

            return response()->json([
                'errors' => $validator->errors()
            ], 422);

        }

        $vendor = Vendor::create([
            'name' => $request->input('name'),
            'short_name' => $request->input('short_name')
        ]);
        
        return response()->json($vendor, 201);
        
    }

    //
    protected function store(Request $request)
    {



        $rules = [

            'name' => [
                'required',
                'string',
                'unique:vendors',
                'min:2',
                'max:64'
            ],

            'short_name' => [
                'required',
                'string',
                'unique:vendors',
                'min:2',
                'max:12',
            ],

            'thumbnail' => [
                'required',
                'file',
                'image',
                'max:10240',
            ],

        ];

        $messages = [
            'name.required' => 'Please provide a name for the new vendor.',
            'name.string' => 'The name of a vendor should be a string.',
            'name.unique' => 'The vendor ":input" alread exists.',
            'name.min' => 'The name should be at least two characters long.',
            'name.max' => 'The name should not be longer te 50 characters',
            'short_name.required' => 'Please provide a short name for the new vendor.',
            'short_name.string' => 'The short name should be a string.',
            'short_name.unique' => 'The vendor short name ":input" is already taken.',
            'short_name.min' => 'The short name should be at least two characters long.',
            'short_name.max' => 'The short name should not be longer te 12 characters'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }
          







        \DB::beginTransaction();

            try {
                
                $vendor = Vendor::create([
                    'name' => $request->input('name'),
                    'short_name' => $request->input('short_name')
                ]);

                $vendor_images = [];

                // Handle thumbnail
                if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            
                    $thumbnail = $request->file('thumbnail');
        
                    $thumbnail_name = 'thumb.' . $thumbnail->extension();
                    
                    $thumbnail->storeAs('vendors', Hashids::encode($vendor->id) . '/' . $thumbnail_name, 's3');

                    $vendor->path = $thumbnail_name;

                    $vendor->save();
                }
                

            } catch (\Throwable $e) {

                \DB::rollback();
                throw $e;

            }

        \DB::commit();

        $prepared_response = [
            'status' => 1,
             'data' => $vendor
        ];
        
        return response()->json($prepared_response, 200);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $vendor_id)
    {

        $vendor_id = $this->unhash_id($vendor_id);

        $vendor = Vendor::find($vendor_id);

        if ($vendor) {

            return response()->json([
                'status' => 1,
                'data' => $vendor,
            ], 200);

        }

        return response()->json([
            'staus' => 0,
            'message' => 'Vendor not found.'
        ], 404);

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
    public function update(Request $request)
    {
        
        $rules = [

                'vendor_id' => [
                        'required',
                         new Hashexists('vendors', 'id')
                     ],

                'thumbnail' => [
                    'required',
                    'file',
                    'image',
                    'max:10240',
                ],

        ];

        $messages = [
            'vendor_id.required' => 'Please provide vendor id.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $vendor_id = $request->input('vendor_id');
        $unhased = Hashids::decode($vendor_id);
        $id = array_shift($unhased);

                \DB::beginTransaction();

            try {
                
                // $vendor = Vendor::create([
                //     'name' => $request->input('name'),
                //     'short_name' => $request->input('short_name')
                // ]);
                $vendor = Vendor::find($id);

                $vendor_images = [];

                // Handle thumbnail
                if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            
                    $thumbnail = $request->file('thumbnail');
                   
                    $thumbnail_name = 'thumb.' . $thumbnail->extension();
                    
                    $thumbnail->storeAs('vendors', Hashids::encode($id) . '/' . $thumbnail_name, 's3');

                    $vendor->path = $thumbnail_name;
                    $vendor->save();

                    // DB::table('vendors')
                    //     ->where('id', $id)
                    //     ->update(['path' => $thumbnail_name]);


                }
                

            } catch (\Throwable $e) {

                \DB::rollback();
                throw $e;

            }

        \DB::commit();

        $prepared_response = [
            'status' => 1,
             'data' => $vendor
        ];
        
        return response()->json($prepared_response, 200);




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
