<?php

namespace App\Http\Controllers\v1;

use libphonenumber\PhoneNumberUtil;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class AccountRecoveryController extends Controller
{

    use ControllerTrait;

    public function get_customer_identifier(Request $request)
    {

        $username = $request->input('username');

        if (!$username) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "username" => [
                        "Please provide your registered email address or phone number."
                    ]
                ]
            ], 422);

        }

        // Check that identifier exist as email or phone number
        // and that the customer in not logged with auth provider

        $id_type = null;

        try {

            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {

                $id_type = "email";
    
            } elseif (
                PhoneNumberUtil::getInstance()
                    ->isValidNumber(PhoneNumberUtil::getInstance()
                    ->parse($username, "AE"))
            ) {
    
                $id_type = "phone_number";
    
            } else {
    
                return response()->json([
                    "status" => 0,
                    "message" => "The given data was invalid.",
                    "errors" => [
                        "username" => [
                            "The given email or phone number was incorrect."
                        ]
                    ]
                ], 422);
    
            }

        } catch (\libphonenumber\NumberParseException $e) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "username" => [
                        "The given email or phone number was incorrect."
                    ]
                ]
            ], 422);

        }

        $customer = Customer::where($id_type, $username)->whereNull("oauth_provider")->whereNotNull("secret")->first();

        if (!$customer) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "username" => [
                        "Unrecognized email address or phone number.",
                    ]
                ],
            ], 422);

        }

        
        // $key = getenv("APP_KEY");
        // $key = substr($key, strpos($key, ":") + 1);

        // $data = $username . "." . Str::random(16) . "." . now()->addHours(24)->format("Y-m-d H:s:i");
        // $data = base64_encode($data);

        // $sign = hash_hmac("sha1", $data , $key);

        // $recovery_url = "http://customer-accounts.valucart.com/account-recovery?ref=" . $data . "." . $sign ;
        
        $otp = $this->generate_string();

        if ($customer->email) {

            \DB::table("auth_codes")->insert([
                "customer_id" => $customer->id,
                "delivery_mode" => $customer->email,
                "code" => $otp,
                "purpose" => "account_recovery",
                "created_at" => now()->format("Y-m-d H:s:i"),
                "updated_at" => now()->format("Y-m-d H:s:i"),
                "expires_at" => now()->addHours(24)->format("Y-m-d H:s:i"),
            ]);

            $email = new \App\Mail\CustomerAccountRecovery($customer, $otp);
            Mail::to($customer)->send($email);

        }

        return response()->json([
            "status" => 1,
            "ref" => $username,
            "message" => "You should recieve a four digit code shortly, use the code to recovery you account.",
        ], 200);

    }

    public function recovery_with_signed_link(Request $request)
    {

        

    }

    public function recovery_with_code(Request $request)
    {

        $rules = [
            "ref" => [
                "required"
            ],
            "code" => [
                "required"
            ],
            "new_password" => [
                "required",
                "confirmed"
            ],
            "new_password_confirmation" => [
                "required",
            ]
        ];

        $messages = [
            "new_password.required" => "Please enter your new password.",
            "new_password_confirmation.required" => "Please confirm your new password."
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => $validator->errors(),
            ], 422);

        }

        $ref = $request->input("ref");
        $code = $request->input("code");
        $new_password = $request->input("new_password");
        $new_password_confirmation = $request->input("new_password_confirmation");

        $auth_code = \DB::table("auth_codes")
            ->where("code", $code)
            ->where("delivery_mode", $ref)
            ->where("purpose", "account_recovery")
            ->first();
        
        if (!$auth_code) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "code" => [
                        "The the provided code seems to be invalid."
                    ]
                ]
            ], 422);

        }

        if (Carbon::parse($auth_code->expires_at)->isPast()) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "code" => [
                        "The the provided code seems to be expired."
                    ]
                ]
            ], 422);

        }
        
        $customer = Customer::find($auth_code->customer_id);

        if (!$customer) {

            return response()->json([
                "status" => 0,
                "message" => "The given data was invalid.",
                "errors" => [
                    "code" => [
                        "Something went wrong, please try again later."
                    ]
                ]
            ], 422);

        }

        $customer->secret = Hash::make($new_password);
        $customer->save();

        return response()->json([
            "status" => 1,
            "message" => "Your password was successfuly updated"
        ], 200);

    }

}
