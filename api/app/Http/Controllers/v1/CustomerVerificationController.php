<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use App\Models\Customer;
use App\Http\Requests\CustomerSignupRequest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class CustomerVerificationController extends Controller
{

    use ControllerTrait;

    public function resend_email_code(Request $request)
    {

        // $rules = [
        //     'email' => [
        //         'required',
        //         'email'
        //     ]
        // ];

        // $messages = [
        //     'email.required' => 'Please provide your email address.',
        //     'email.email' => 'The email address seems to be invalid.'
        // ];

        // $email = $request->email;

        // $customer = Customer::where('email', $email)->whereNull('oauth_provider')->first();

        // $validator = Validator::make($request->all(), $rules, $messages);

        // $validator->after(function($validator) use ($email, $customer) {

        //     if (is_null($customer)) {
        //         $validator->errors()->add('email', 'Unknown email address ' . $email . '.');
        //     }

        // });

        // if ($validator->fails()) {

        //     return response()->json([
        //         'status' => 0,
        //         'message' => 'The given data was invalid.',
        //         'errors' => $validator->errors()
        //     ]);

        // }

        $customer = $request->user();

        $verification_code = $this->generate_string();

        // Store email verifiction code
        \DB::table('customer_email_verfication_codes')
            ->where([ 'customer_id' => $customer->id ])
            ->update([
                'code' => $verification_code, 
                'updated_at' => now()->format('Y-m-d H:i:s'),
                'expires_at' => now()->addDays(1)->format('Y-m-d H:i:s')
            ]);

        // Send cunstomer email
        if ($customer->email) {
            $email = new \App\Mail\CustomerResendEmailCode($customer, $verification_code);
            Mail::to($customer)->send($email);
        }

        return response()->json([
            'status' => 1,
            'message' => 'A verification code has been sent to ' . $customer->email . '.'
        ]);
        
    }
    
    public function verify_email(Request $request)
    {

        $rules = [
            'code' => [ 'required' ]
        ];

        $messages = [
            'code.required' => 'Please provide the verification code.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);

        }

        $customer = $request->user();

        $code = \DB::table('customer_email_verfication_codes')
            ->where('customer_id', $customer->id)
            ->where('code', $request->code)
            ->first();

        if (is_null($code) ) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'code' => [
                        'The code might be invalid or expired, please try again.'
                    ]
                ]
                    ], 422);

        }

        $expiry = Carbon::parse($code->expires_at);

        if ($expiry->isPast()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'code' => [
                        'The code might be invalid or expired, please try again.'
                    ]
                ]
            ], 422);

        }

        // Verify customer email
        $customer->contact_email = $customer->email;
        $customer->email_verified = '1';
        $customer->save();

        \DB::table('customer_email_verfication_codes')
            ->where([ 'customer_id' => $customer->id ])
            ->update([
                'updated_at' => now()->format('Y-m-d H:i:s'),
                'expires_at' => now()->format('Y-m-d H:i:s')
            ]);

        return response()->json([
            'status' => 1,
            'message' => 'Your email was successfuly verified.'
        ]);

    }

}
