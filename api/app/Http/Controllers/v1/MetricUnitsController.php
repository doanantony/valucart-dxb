<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\MetricUnit;
use App\Http\Resources\MetricUnitsCollection;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class MetricUnitsController extends Controller
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

        $collection = new MetricUnitsCollection(MetricUnit::paginate($per_page));

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
                'unique:matric_units,name',
            ],
            'symbol' => [
                'required',
                'string',
                'min:1',
                'max:8',
                'unique:matric_units,symbol',
            ]
        ];

        $messages = [
            'name.required' => 'Please enter the name of the metric unit.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'Metric unit ":input" already exists.',
            'symbol.required' => 'Please enter a symbol.',
            'symbol.string' => 'The symbol should be a string.',
            'symbol.min' => 'The name should be at least 1 characters long.',
            'symbol.max' => 'The name should not be longer than 8 characters.',
            'symbol.unique' => 'Metric unit symbol ":input" already exists.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors(),
            ], 422);

        };

        $brand = MetricUnit::create([
            'name' => $request->input('name'),
            'symbol' => $request->input('symbol')
        ]);
        
        return response()->json([
            'status' => 1,
            'data' => $brand,
        ], 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $unit_id)
    {   
        
        $unit_id = $this->unhash_id($unit_id);
        
        $unit = MetricUnit::find($unit_id);

        if (is_null($unit)) {

            return response()->json([
                'status' => 0,
                'message' => 'Metric unit not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $unit,
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
