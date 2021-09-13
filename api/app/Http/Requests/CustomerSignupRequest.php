<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerSignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
                Rule::unique('customers')->where(function ($query) {
                    return $query->whereNull('oauth_provider');
                })
            ],

            'phone_number' => [
                'nullable',
                'phone:AUTO,AE',
                Rule::unique('customers')->where(function ($query) {
                    return $query->whereNull('oauth_provider');
                })
            ],

            'gender' => [
                'nullable',
                Rule::in(['female', 'male']),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],
            
        ];

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {

        return [
            'name.required' => 'Please provide your full name.',
            'name.regex' => 'Name should only contain letters and numbers.',
            'name.min' => 'Name should be at least 3 characters long',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'The email address seems to be invalid.',
            'email.unique' => 'The email address ":input" is already associated with another customer.',
            'phone_number.phone' => 'The phone number ":input" seems to be invalid.',
            'phone_number.unique' => 'The phone number ":input" is already associated with another customer.',
            'gender.in' => 'Gender sholud be male or female.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password should be at least 8 characters long.'
        ];
        
    }

}
