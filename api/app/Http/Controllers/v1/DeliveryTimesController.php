<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;

use App\Models\Cart;
use App\Models\DeliveryTimeSlot;
use App\Models\CustomerBundle;

use App\Http\Controllers\Controller;

class DeliveryTimesController extends Controller
{

    protected $rules = [

        'from' => [
            'required',
            'regex:/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9](\s?(am|pm))?$/i'
        ],
        
        'to' => [
            'required',
            'regex:/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9](\s?(am|pm))?$/i'
        ],

        'name' => [
            'nullable',
            'max:64'
        ]
        
    ];

    protected $messages = [
        'from.required' => 'Please provide the from time.',
        'from.regex' => 'The from time seems to be invalid.',
        'to.required' => 'Please provide the to time.',
        'to.regex' => 'The to time seems to be invalid.',
        'name.max' => 'Name too long.'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $for = $request->input("for", null);
        $cart = $request->input("cart", null);

        $cart_type = null;

        if (Cart::exists($cart)) {
            $cart_type = "cart";
        } else if (CustomerBundle::exists($cart)) {
            $cart_type = "meatmonday";
        }

        $customer = $request->user();
        
        $cart = !is_null($customer) ? Cart::find($customer->id) : Cart::find($cart) ;
        
        $with_extended_lead_time = false;

        if (!is_null($cart)) {
            $with_extended_lead_time = $cart->needs_extended_delivery_time();
        }
        
        $time_slots = DeliveryTimeSlot::all($for, $with_extended_lead_time, $cart_type);
        
        return response()->json([
            'status' => 1,
            'data' => $time_slots,
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        
        $validator->validate();

        $tz = CarbonTimeZone::create('Asia/Dubai');

        $from = $request->input('from');
        $to = $request->input('to');
        $cut_off = $request->input('cut_off');
        $name = $request->input('name');

        $from = Carbon::parse($from, $tz);
        $to = Carbon::parse($to, $tz);

        if ($cut_off) {
            
            try {
                
                $parsed_cut_off = Carbon::parse($cut_off, $tz);

            } catch (\Exception $e) {
                
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'cut_off' => [
                            'Invalid cut of time!'
                        ]
                    ]
                ], 422);

            }

        }

        $from->setTimezone('UTC');
        $to->setTimezone('UTC');

        if ($from->isAfter($to) || $from->equalTo($to)) {
            
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'from' => [
                        'The from time must be time before the to time.'
                    ]
                ]
            ], 422);

        }

        $exists = DeliveryTimeSlot::whereTime('from', '=', $from->format('H:i:s'))
            ->whereTime('to', '=', $to->format('H:i:s'))
            ->exists();
            
        if ($exists) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'duplicate' => [
                        'The delivery time slot already exists.',
                    ]
                ]
            ]);

        }

        $time_slot = DeliveryTimeSlot::create([
            'time_slots' => $name ? $name : $from->format('H:i') . " - " . $to->format('H:i'),
            'from' => $from->format('H:i:s'),
            'to' => $to->format('H:i:s'),
            'cut_off' => $cut_off,
            'unpublished_at' => now()
        ]);

        return response()->json($time_slot, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $time_slot = DeliveryTimeSlot::find($id);

        if (is_null($time_slot)) {
            
            return response()->json([
                'status' => 1,
                'message' => 'Unknowm delivery time slot',
            ], 404);

        }
        
        return response()->json([
            'status' => 1,
            'data' => $time_slot,
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

        $time_slot = DeliveryTimeSlot::find($id);

        if (is_null($time_slot)) {
            
            return response()->json([
                'message' => 'Unknown delivery time slot'
            ], 404);

        }

        $rules = [

            'from' => [
                'nullable',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9](\s?(am|pm))?$/i'
            ],
            
            'to' => [
                'nullable',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9](\s?(am|pm))?$/i'
            ],
    
            'name' => [
                'nullable',
                'max:64'
            ],

            'publish' => [
                'nullable',
                'boolean'
            ]
            
        ];
    
        $messages = [
            'from.regex' => 'The from time seems to be invalid.',
            'to.regex' => 'The to time seems to be invalid.',
            'name.max' => 'Name too long.',
            'publish.boolean' => 'Publish must be a boolean'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        
        $validator->validate();

        $tz = CarbonTimeZone::create('Asia/Dubai');

        $from = $request->input('from');
        $to = $request->input('to');
        $cut_off = $request->input('cut_off');
        $name = $request->input('name');
        $publish = $request->input('published', null);

        $from = $from ? Carbon::parse($from, $tz) : $time_slot->from ;
        $to = $to ? Carbon::parse($to, $tz) : $time_slot->to ;

        $from->setTimezone('UTC');
        $to->setTimezone('UTC');

        if ($cut_off && $cut_off !== 'none') {
            
            try {
                
                Carbon::parse($cut_off, $tz);

            } catch (\Exception $e) {
                
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'cut_off' => [
                            'Invalid cut of time!'
                        ]
                    ]
                ], 422);

            }

        }

        if ($from->isAfter($to) || $from->equalTo($to)) {
            
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'from' => [
                        'The from time must be time before the to time.'
                    ]
                ]
            ], 422);

        }

        $exists = DeliveryTimeSlot::whereTime('from', '=', $from->format('H:i:s'))
            ->whereTime('to', '=', $to->format('H:i:s'))
            ->where('id', '!=', $time_slot->id)
            ->exists();
            
        if ($exists) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'duplicate' => [
                        'The delivery time slot already exists.',
                    ]
                ]
            ]);

        }

        $time_slot->from = $from;
        $time_slot->to = $to;
        $time_slot->time_slots = $name ? $name : $from->format('H:i') . " - " . $to->format('H:i');
        $time_slot->cut_off = $cut_off ? $cut_off == 'none' ? null : $cut_off : $time_slot->cut_off ;

        if (!is_null($publish) && $publish) {
            $time_slot->unpublished_at = null;
        } else if(!is_null($publish) && !$publish) {
            $time_slot->unpublished_at = Carbon::now($tz)->setTimezone("UTC");
        }

        $time_slot->save();

        return response()->json($time_slot, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        
        $time_slot = DeliveryTimeSlot::withTrashed()->where('id', $id)->first();

        if (is_null($time_slot)) {
            
            return response()->json([
                'status' => 1,
                'message' => 'Unknown delivery time slot',
            ], 404);

        }

        $permanent = $request->has('permanent');

        if ($permanent) {
            $time_slot->forceDelete();
        } else {
            $time_slot->delete();
        }
        
        return response(null, 204);

    }

}
