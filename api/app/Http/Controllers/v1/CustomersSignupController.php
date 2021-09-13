<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\Customer;
use App\Http\Requests\CustomerSignupRequest;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class CustomersSignupController extends Controller
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

        $validator = Validator::make(
            $request->all(),
            $this->get_rules(),
            $this->get_messages()
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()->toArray(),
            ], 422);

        }

        // Make customer
        $customer = Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number', null),
            'gender' => $request->input('gender', null),
            'secret' => Hash::make($request->input('password')),
        ]);

        $verification_code = $this->generate_string();

        // Store email verifiction code
        \DB::table('customer_email_verfication_codes')->insert([
            'customer_id' => $customer->id,
            'code' => $verification_code, 
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
            'expires_at' => now()->addDays(1)->format('Y-m-d H:i:s'),
        ]);

        $fcm_token = $request->input('fcm_token');
        $device_type = 'android';

                // if(isset($request->input('device_type'))){

                //     $device_type = $request->input('device_type');

                // }else{

                //     $device_type = 'ios';

                // }

        DB::table('fcmtokens')->insert(
             ['fcm_token' => $fcm_token, 'customer_id' => $customer->id,'device_type'=>$device_type]
        );

        // Send cunstomer email
        /*
         if ($customer->email) {

             $email = new \App\Mail\CustomerSignUp($customer, $verification_code);
             Mail::to($customer->email)->send($email);

         }
        */
         
        return response()->json([
            'status' => 1,
            'token_type' => 'Bearer',
            'access_token' => $this->make_token($customer)['access_token'],
            // 'data' => $customer->toArray(),
        ], 201);

    }

    protected function get_rules()
    {
        
        return [

            'name' => [
                'nullable',
                'regex:/^[a-z0-9\s]+$/i',
                'min:3',
                'max:64',
            ],
    
            'email' => [
                'required',
                'email',
                Rule::unique('customers')
            ],
    
            'phone_number' => [
               'nullable',
              // 'phone:AUTO,AE',
                //Rule::unique('customers')
            ],
    
            'gender' => [
                'nullable',
                //Rule::in(['female', 'male']),
            ],
    
            'password' => [
                'required',
                'string',
                'min:8',
            ],

            'agreed_to_terms' => [
                'required',
                'accepted',
            ],
            
        ];

    }

    protected function get_messages()
    {

        return [
            'name.required' => 'Please provide your full name.',
            'name.regex' => 'Name should only contain letters and numbers.',
            'name.min' => 'Name should be at least 3 characters long',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'The email address seems to be invalid.',
            'email.unique' => 'The email address :input is already associated with another customer.',
            // 'phone_number.required' => 'Please provide your phone number',
            'phone_number.phone' => 'The phone number :input seems to be invalid.',
            'phone_number.unique' => 'The phone number :input is already associated with another customer.',
            'gender.in' => 'Gender sholud be male or female.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password should be at least 8 characters long.',
            'agreed_to_terms.required' => 'Please comfirm that you agree to terms of use to continue.',
            'agreed_to_terms.accepted' => 'Please comfirm that you agree to terms of use to continue.',
        ];

    }

    protected function make_token(Customer $customer)
    {
        $access_tokens = resolve(\Laravel\Passport\Bridge\AccessTokenRepository::class);

        $client = \DB::table('oauth_clients')
            ->where('id', env('PASSWORD_ACCESS_TOKEN_CLIENT'))
            ->first(['id', 'name', 'redirect']);
        
        $oauth_client = new \Laravel\Passport\Bridge\Client($client->id, $client->name, $client->redirect);
        
        $token = $access_tokens->getNewToken($oauth_client, [new \Laravel\Passport\Bridge\Scope('*')], $customer->id);
        $token->setClient($oauth_client);
        $token->setIdentifier((string) \Str::uuid());
        $token->setExpiryDateTime(now()->addDays(30));

        $private_key = env('OAUTH_KEYS_LOCATION') . 'oauth-private.key';
        $key = new \League\OAuth2\Server\CryptKey($private_key);
        
        $access_tokens->persistNewAccessToken($token);

        // Make refresh token
        // $refresh_tokens = resolve(\Laravel\Passport\Bridge\RefreshTokenRepository::class);
        // $refresh_token = new \Laravel\Passport\Bridge\RefreshToken;
        // $refresh_token->setAccessToken($token);
        // $refresh_token->setIdentifier((string) \Str::uuid());
        // $refresh_token->setExpiryDateTime(now()->addDays(90));

        // $refresh_tokens->persistNewRefreshToken($refresh_token);

        return [
            'access_token' => (string) $token->convertToJWT($key),
            'refresh_token' => ''
        ];

    }

}
