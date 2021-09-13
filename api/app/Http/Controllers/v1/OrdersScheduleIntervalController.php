<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\OrdersScheduleInterval;
use App\Http\Resources\OrdersScheduleIntervalCollection;

class OrdersScheduleIntervalController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = OrdersScheduleInterval::query();

        if ($request->has('per_page')) {

            $per_page = (int) $request->query('per_page', 15);
            $intervals = $query->paginate($per_page);

        } else {

            $intervals = OrdersScheduleInterval::all();

        }

        $collection = new OrdersScheduleIntervalCollection($intervals);

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
                'min:3',
                'max:64',
                'unique:order_schedule_interval,name'
            ],
            'interval' => [
                'required',
                'regex:/^[a-z0-9\s]+$/i',
                'unique:order_schedule_interval,interval'
            ]
        ];
    
        $messages = [
            'name.required' => 'Please provide the name.',
            'name.min' => 'The name must be at lease 3 characters',
            'name.max' => 'The name must not be longer than 64 characters.',
            'name.unique' => 'The interval named :input already exists',
            'interval.required' => 'Please enter the interval as a number of days',
            'interval.regex' => 'Interval seems to contain invalid characters.',
            'interval.unique' => 'Interval :input already exists',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'massage' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ]);

        }

        $interval = OrdersScheduleInterval::create([
            'name' => $request->input('name'),
            'interval' => $request->input('interval'),
        ]);

        return response()->json($interval, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $interval_id)
    {
        
        $interval = OrdersScheduleInterval::find($interval_id);

        if (is_null($interval)) {

            return response()->json([
                'status' => 0,
                'message' => 'Interval not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $interval,
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $interval_id)
    {
        
        $interval = OrdersScheduleInterval::find($interval_id);

        if (is_null($interval)) {

            return response()->json([
                'status' => 0,
                'message' => 'Interval not found'
            ], 404);

        }

        $rules = [
            'name' => [
                'required',
                'min:3',
                'max:64',
                Rule::unique('order_schedule_interval', 'name')->ignore($interval->id),
            ],
            'interval' => [
                'required',
                'regex:/^[a-z0-9\s]+$/i',
                Rule::unique('order_schedule_interval', 'interval')->ignore($interval->id),
            ]
        ];
    
        $messages = [
            'name.required' => 'Please provide the name.',
            'name.min' => 'The name must be at lease 3 characters',
            'name.max' => 'The name must not be longer than 64 characters.',
            'name.unique' => 'The interval named :input already exists',
            'interval.required' => 'Please enter the interval as a number of days',
            'interval.regex' => 'Interval seems to contain invalid characters.',
            'interval.unique' => 'Interval :input already exists',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'massage' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ]);

        }

        $interval->name = $request->input('name');
        $interval->interval = $request->input('interval');

        $interval->save();

        return response()->json([
            'status' => 1,
            'data' => $interval,
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $interval_id)
    {
        
        $interval = OrdersScheduleInterval::find($interval_id);

        if (is_null($interval)) {

            return response()->json([
                'status' => 0,
                'message' => 'Interval not found'
            ], 404);

        }

        $interval->delete();

        return response()->json([
            'status' => 1,
        ], 204);

    }
}
