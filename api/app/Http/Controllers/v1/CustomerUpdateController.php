<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

use App\Rules\Hashexists;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class CustomerUpdateController extends Controller
{

    use ControllerTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $customer = $request->user();
        
        $validator = Validator::make(
            $request->all(),
            $this->get_rules($customer),
            $this->get_messages()
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        // Check that customers email is verified
        if (!is_null($customer->email) &&
            !$customer->email_verified &&
            (
                !$request->has("email") || ($request->has("email") && $request->input("email") == $customer->email)
            )) {
            
            return response()->json([
                'status' => 0,
                'message' => 'Please verify your email address to continue.',
            ], 422);

        }

        // Update customer information
        $save_changes = false;
        $verify_email = false;

        $name = $request->input('name');
        if (!is_null($name) && $name != $customer->name) {
            $customer->name = $name;
            $save_changes = true;
        }

        $gender = $request->input('gender');
        if (!is_null($gender) && $gender != $customer->gender) {
            $customer->gender = $gender;
            $save_changes = true;
        }

        $country = $request->input('country');
        if (!is_null($country) && $country != $customer->country) {
            $customer->country_id = $this->unhash_id($country);
            $save_changes = true;
        }

        $email = $request->input('email');
        if (!is_null($email) && $email != $customer->email) {
            $customer->email = $email;
            $customer->email_verified = '0';
            $save_changes = true;
            $verify_email = true;
        }

        $phone_number = $request->input('phone_number');
        if (!is_null($phone_number) && $phone_number != $customer->phone_number) {
            $customer->phone_number = $phone_number;
            $customer->phone_number_verified = '0';
            $save_changes = true;
        }

        if ($save_changes) {

            $customer->save();
            
            $send_to = null;
            $send_to = $customer->contact_email ? $customer->contact_email : $customer->email ;

            Mail::to($send_to)->send(new \App\Mail\CustomerInforChanged($customer));

            if ($verify_email) {

                // Verify new email
                $verification_code = $this->generate_string();

                // Store email verifiction code
                \DB::table('customer_email_verfication_codes')->updateOrInsert([
                        'customer_id' => $customer->id
                    ], [
                        'code' => $verification_code, 
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                        'expires_at' => now()->addDays(1)->format('Y-m-d H:i:s'),
                    ]
                );
                
                Mail::to($request->input('email'))
                    ->send(new \App\Mail\CustomerInforChanged($customer, $verification_code));

            }
        
        }

        return response()->json([
            'status' => 1,
            'message' => 'Your information was successfully updated.'
        ], 201);

    }

    protected function get_rules($customer)
    {
        
        return [

            'name' => [
                'required',
                'regex:/^[a-z0-9\s]+$/i',
                'min:3',
                'max:64',
            ],
    
            'email' => [
                'required',
                'email',
                Rule::unique('customers')->ignore($customer->id)
            ],
    
            'phone_number' => [
                'nullable',
                'phone:AUTO,AE',
                Rule::unique('customers')->ignore($customer->id)
            ],
    
            'gender' => [
                'nullable',
                Rule::in(['female', 'male']),
            ],
    
            'country' => [
                'nullable',
                new Hashexists('countries', 'id')
            ]
            
        ];

    }

    protected function get_messages()
    {

        return [
            'name.required' => 'Please enter your name.',
            'name.regex' => 'Name should only contain letters and numbers.',
            'name.min' => 'Name should be at least 3 characters long',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'The email address seems to be invalid.',
            'email.unique' => 'The email address :input is already associated with another customer.',
            'phone_number.required' => 'Please provide your phone number.',
            'phone_number.phone' => 'The phone number :input seems to be invalid.',
            'phone_number.unique' => 'The phone number :input is already associated with another customer.',
            'gender.in' => 'Gender sholud be male or female.',
        ];

    }

}
