<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;

use App\Models\CustomerAddress;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class CreateCustomerAddressController extends Controller
{

    protected $rules = [
        'name' => 'nullable|string|min:1|max:64',
        'area' => 'required|exists:areas,id',
        // 'street' => 'nullable|string|min:1|max:128',
        'adress' => 'required|string|min:1|max:128',
        'phone_number' => 'nullable|string|min:1|max:52',
        // 'floor' => 'nullable|string|min:1|max:128',
        // 'apartment' => 'nullable|string|min:1|max:128',
        'landmark' => 'nullable|string|min:1|max:128',
        'notes' => 'nullable|string|min:1|max:512',
    ];

    protected $messages = [
        'name.string' => 'The name must be a string.',
        'name.min' => 'Name must be at least 1 characters long.',
        'name.max' => 'Name should not be longer than 64 characters long.',
        'area.required' => 'Please select an area.',
        'area.exists' => 'The given area seems to be invalid',
       //  'street.string' => 'Street must be a string.',
       // 'street.min' => 'Street must be at least 1 characters long.',
       // 'street.max' => 'Street should not be longer than 128 characters.',
        'adress.string' => 'Adress must be a string.',
        'adress.min' => 'Adress must be at least 1 characters long.',
        'adress.max' => 'Adress should not be longer than 128 characters.',
        'phone_number.string' => 'Phone No must be a string.',
        'phone_number.min' => 'Phone No must be at least 1 characters long.',
        'phone_number.max' => 'Phone No should not be longer than 128 characters.',
        //'floor.string' => 'Floor must be a string.',
        //'floor.min' => 'Floor must be at least 1 characters long.',
        //'floor.max' => 'Floor should not be longer than 128 characters.',
        //'apartment.string' => 'Apartment must be a string.',
        //'apartment.min' => 'Apartment must be at least 1 characters long.',
        //'apartment.max' => 'Apartment should not be longer than 128 characters.',
        'landmark.string' => 'Landmark must be a string.',
        'landmark.min' => 'Landmark must be at least 1 characters long.',
        'landmark.max' => 'Landmark should not be longer than 128 characters.',
        'notes.string' => 'Notes must be a string.',
        'notes.min' => 'Notes must be at least 1 characters long.',
        'notes.max' => 'Notes should not be longer than 512 characters.',
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $customer = $request->user();

        // Validate data
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        // Check that the customer has verified contact information
        if (!$customer->email && !$customer->phone_number) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'contact_information' => [
                        'Please update your contact information to continue.',
                    ]
                ],
            ], 422);

        }

        if ($customer->email && !$customer->email_verified && is_null($customer->contact_email)) {

            return response()->json([
                'status' => 0,
                'message' => 'Please verify you email address to continue.',
                'errors' => [
                    'contact_information' => [
                        'Please verify you email address to continue.',
                    ]
                ],
            ], 422);

        }

        if ($customer->email && $customer->email_verified && is_null($customer->contact_email)) {

            return response()->json([
                'status' => 0,
                'message' => 'Something went terribly wrong, please try again.',
                'errors' => [
                    'contact_information' => [
                        'Something went terribly wrong, please try again.',
                    ]
                ],
            ], 422);

        }

        // if ($customer->phone_number && !$customer->phone_number_verified) {

        //     return response()->json([
        //         'status' => 0,
        //         'message' => 'Please verify you phone number to continue.',
        //         'errors' => [],
        //     ], 422);

        // }

        $address = $customer->addresses()->create([
            'name' => $request->name,
            'area_id' => $request->area,
            //'street' => $request->street,
            'building' => $request->adress,
            //'floor' => $request->floor,
            //'apartment' => $request->apartment,
            'landmark' => $request->landmark,
            'notes' => $request->notes,
            'phone_number' =>$request->phone_number
        ]);

        return response()->json([
            'status' => 1,
            'data' => $address,
        ], 201);

    }

}
