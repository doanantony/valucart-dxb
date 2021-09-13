<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Popularcustomers;
use App\Models\Customer;
use App\Http\Resources\PopularcustomersCollection;
use App\Http\Resources\Popularcustomers as PopularcustomersResource;

use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class PopularcustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $per_page = (int) $request->query('per_page', 15);

        $collection = new PopularcustomersCollection(Popularcustomers::paginate($per_page));

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
    public function store(Request $request)
    {
        //
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
        
        $popularcustomers = Customer::find($decoded_id);

        if (is_null($popularcustomers)) {

            return response()->json([
                'status' => 0,
                'message' => 'Popularcustomers not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $popularcustomers->toArray()
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
