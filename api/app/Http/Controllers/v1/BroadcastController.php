<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Validation\Rule;

use App\Http\Resources\BroadcastMessagesCollection;
use App\Http\Resources\BroadcastMessages as BroadcastMessagesResource;
use App\Http\Controllers\Controller;
use App\Models\BroadcastMessages;

class BroadcastController extends Controller
{

    public function types(Request $request)
    {

        return response([
            "status" => 1,
            "data" => BroadcastMessages::$types
        ], 200);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /*
    public function index(Request $request)
    {
        $now = now()->toDateTimeString();

        $messages = BroadcastMessages::whereNull("unpublished_at")
                                        ->where("publish_at", "<=", $now)
                                        ->where(function ($query) use($now) {
                                            $query->whereNull("expires_at")->orWhere("expires_at", "<", $now);
                                        })
                                        ->get();

        $messages = new BroadcastMessagesCollection($messages);

        return $messages->additional([ "status" => 1 ]);

    }

    */

    public function index(Request $request)
    {
        return response()->json([
            'status' => 1,
            'data' => [
                [
                    "type" => "ok", // ok | notice | warning | red_alert
                    "message" => "Summer. Stay Home! Stay Safe.."
                ]
            ]
        ], 200);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $message_id)
    {
        
        $message = BroadcastMessages::find($message_id);

        if (is_null($message)) {

            return response()->json([
                "status" => 0,
                "message" => "Message not found."
            ], 404);

        }

        return response()->json([
            "status" => 1,
            "data" => $message->toArray()
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

        $rules = [

            "type" => [
                "required",
                Rule::in(BroadcastMessages::$types),
            ],
            "message" => [
                "required",
                "max:1024"
            ],
            "publish_at" => [
                "nullable",
                "date"
            ],
            "expires_at" => [
                "nullable",
                "date"
            ],

        ];
        
        $messages = [
            "type.required" => "Provide the message type.",
            "type.in" => "Invalid message type.",
            "message.required" => "Message is required.",
            "message.max" => "Message too long.",
            "publish_at.date" => "Enter valid datetime for publish at.",
            "expires_at.date" => "Enter valid datetime for expires at.",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => $validator->errors()->toArray(),
            ], 422);

        }

        $type = $request->input("type");
        $message = $request->input("message");
        $publish_at = $request->input("publish_at");
        $expires_at = $request->input("expires_at");

        $time_zone = CarbonTimeZone::create("Asia/Dubai");

        if ($publish_at) {
            $publish_at = Carbon::parse($expires_at, $time_zone);
            $publish_at->setTimezone("UTC");
        } else {
            $publish_at = Carbon::now($time_zone)->setTimezone("UTC");
        }

        if ($expires_at) {
            $expires_at = Carbon::parse($expires_at, $time_zone);
            $expires_at->setTimezone("UTC");
        }
        
        $msg = BroadcastMessages::create([
            "type" => $type,
            "message" => $message,
            "publish_at" => $publish_at,
            "expires_at" => $expires_at
        ]);

        return response()->json($msg, 201);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $message_id)
    {
        
        $msg = BroadcastMessages::find($message_id);

        if (is_null($msg)) {

            return response()->json([
                "status" => 0,
                "message" => "Message not found."
            ], 404);

        }

        $rules = [

            "type" => [
                "nullable",
                Rule::in(BroadcastMessages::$types),
            ],
            "message" => [
                "nullable",
                "max:1024"
            ],
            "publish_at" => [
                "nullable",
                "date"
            ],
            "expires_at" => [
                "nullable",
                "date"
            ],

            "published" => [
                "nullable",
                "boolean"
            ]

        ];
        
        $messages = [
            "type.in" => "Invalid message type.",
            "message.max" => "Message too long.",
            "publish_at.date" => "Enter valid datetime for publish at.",
            "expires_at.date" => "Enter valid datetime for expires at.",
            "publish.boolean" => "Publish must be a boolean."
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => $validator->errors()->toArray(),
            ], 422);

        }

        $type = $request->input("type");
        $message = $request->input("message");
        $publish_at = $request->input("publish_at");
        $expires_at = $request->input("expires_at");
        $published = $request->input('published', null);

        $time_zone = CarbonTimeZone::create("Asia/Dubai");

        if ($publish_at) {
            $publish_at = Carbon::parse($expires_at, $time_zone);
            $publish_at->setTimezone("UTC");
        }

        if ($expires_at) {
            $expires_at = Carbon::parse($expires_at, $time_zone);
            $expires_at->setTimezone("UTC");
        }
        
        if ($type) {
            $msg->type = $type;
        }
        
        if ($message) {
            $msg->message = $message;
        }
        
        if ($publish_at) {
            $msg->publish_at = $publish_at;
        }
        
        $msg->expires_at = $expires_at;

        if (!is_null($published) && $published) {
            $msg->unpublished_at = null;
        } else if(!is_null($published) && !$published) {
            $msg->unpublished_at = Carbon::now($time_zone)->setTimezone("UTC");
        }

        $msg->save();

        return response()->json($msg, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        
        $message = BroadcastMessages::withTrashed()->where("id", $id)->first();

        if (is_null($message)) {
            
            return response()->json([
                "status" => 1,
                "message" => "Unknown message.",
            ], 404);

        }

        $permanent = $request->has("permanent");

        if ($permanent) {
            $message->forceDelete();
        } else {
            $message->delete();
        }
        
        return response(null, 204);

    }
}
