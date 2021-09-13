<?php

namespace App\Http\Controllers\v1;

use Validator;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Models\Orders;
use App\Models\OrdersScheduleInterval;
use App\Http\Resources\OrdersCollection;
use App\Http\Controllers\Controller;

class OrdersScheduleController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $customer = $request->user();

        $rules = [ 

            'order_id' => [
                'required',
            ],
    
            'start_date' => [
                'nullable',
                Rule::requiredIf(function() use ($request) {
                    return !in_array((int)$request->input('interval_id'), [1, 2, 3, 4, 5, 6, 7]);
                }),
                'date',
            ],
    
            'interval_id' => [
                'required',
            ],
        
        ];

        $messages = [
              'order_id.required' => 'Please select order.',
              'start_date.required' => 'Please provide the start date',
              'start_date.date' => 'The provided start date seems to be invalid.',
              'interval_id.required' => 'Please select the order interval interval.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 1,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $order_id = $request->input('order_id');
        $start_date = $request->input('start_date');
        $interval_id = $request->input('interval_id');

        $order = Orders::find($order_id);
        $interval = OrdersScheduleInterval::find($interval_id);
        
        if (is_null($order)) {

            return response()->json([
                'status' => 1,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'order_id' => [
                        'The order identifier seems to be invalid.'
                    ]
                ],
            ], 422);

        }

        if (is_null($interval)) {

            return response()->json([
                'status' => 1,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'interval_id' => [
                        'The interval identifier seems to be invalid.'
                    ]
                ],
            ], 422);

        }

        $today = Carbon::today();
        $start_date = Carbon::parse($start_date);

        if ($start_date->lessThanOrEqualTo($today) || $start_date->isFriday()) {
            
            return response()->json([
                'status' => 1,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'start_date' => [
                        'Start date can not be today or past and can not be Friday.'
                    ]
                ],
            ], 422);

        }

        $days_of_week = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday'
        ];

        $schedule_next_date = Orders::get_next_schedule_order_date($interval->interval, $start_date);

        $schedule_start_date = in_array($interval->interval, $days_of_week) ? $schedule_next_date : $start_date ;

        Orders::where('id', $order_id)->update([
            'schedule_start_date' => $schedule_start_date->format('Y-m-d'),
            'schedule_interval_id' => $interval->id,
            'schedule_next_date' => $schedule_next_date->format('Y-m-d'),
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Order ' . $order->order_reference . ' has been scheduled for ' . $interval->name . ' starting on ' . $schedule_start_date->format('Y-m-d'),
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $rules = [ 

            'order_id' => [
                'required',
            ],
        
        ];
    
        $messages = [
              'order_id.required' => 'Please select order.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 1,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $order_id = $request->input('order_id');

        $order = Orders::find($order_id);
        
        if (is_null($order)) {

            return response()->json([
                'status' => 1,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'order_id' => [
                        'The order identifier seems to be invalid.'
                    ]
                ],
            ], 422);

        }

        $order->schedule_start_date = null;
        $order->schedule_interval_id = null;
        $order->schedule_next_date = null;

        $order->save();

        return response()->json([
            'status' => 1,
            'message' => 'Schedule for order ' . $order->order_reference . ' has been removed.',
        ], 201);

    }

    public function index(Request $request)
    {

        $customer = $request->user();
        
        $orders = Orders::with('interval')
            ->where('customer_id', $customer->id)
            ->whereNotNull('schedule_start_date')
            ->whereNotNull('schedule_interval_id')
            ->whereNotNull('schedule_next_date');


        if ($request->has('page')) {
            
            $per_page = (int) $request->query('per_page', 15);
            $orders = $orders->paginate($per_page);

        } else {
            
            $orders = $orders->get();

        }
        
        $orders = $orders->map(function($order) {
            
            return [
                'order_id' => $order->id,
                'order_reference' => $order->order_reference,
                'schedule_next_date' => $order->schedule_next_date->format('l jS F Y'),
                'schedule_interval' => $order->interval->name,
            ];

        });

        $collection = new OrdersCollection($orders);

        return $collection->additional([
            'status' => 1
        ]);

    }

}
