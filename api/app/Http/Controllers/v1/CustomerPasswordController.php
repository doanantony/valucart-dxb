<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class CustomerPasswordController extends Controller
{
    
    use ControllerTrait;

    public function change_password(Request $request)
    {

        $rules = [

            'current_password' => [
                'required'
            ],

            'new_password' => [
                'required',
                'min:8',
                'confirmed',
            ],

            'new_password_confirmation' => [
                'required',
                'min:8',
            ],

        ];

        $messages = [
            'current_password.required' => 'Please enter you current password.',
            'new_password.require' => 'Please enter you new password',
            'new_password.min' => 'New password must be at least 8 characters long',
            'new_password_confirmation.required' => 'Please confirm you new password',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $customer = $request->user();

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors(),
            ], 422);

        }

        // Check that customer is not signed up by auth provider
        if (!is_null($customer->oauth_provider)) {

            return response()->json([
                'status' => 0,
                'message' => 'Unable to change passowrd, you are logged in with ' . $customer->oauth_provider,
            ], 422);

        }

        // Check that customers email is verified
        if (!$customer->email_verified) {
            
            return response()->json([
                'status' => 0,
                'message' => 'Please verify your email.',
            ], 422);

        }

        if (!Hash::check($request->current_password, $customer->secret)) {
            
            return response()->json([
                'status' => 0,
                'message' => 'Invalid client credentials.',
            ], 401);

        }

        $customer->secret = Hash::make($request->new_password);
        $customer->save();

        // Send customer email
        if ($customer->email) {
            
            $email = new \App\Mail\CustomerPasswordChanged($customer);
            Mail::to($customer)->send($email);

        }

        return response()->json([
            'status' => 1,
            'message' => 'Your password has been successfuly changed.'
        ]);

    }

}
