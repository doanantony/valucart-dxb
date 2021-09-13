<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;

use App\Models\CustomerAddress;
use App\Http\Controllers\Controller;

class UpdateCustomerAddressController extends Controller
{

    protected $rules = [
        'name' => 'nullable|string|min:2|max:64',
        'area' => 'required|exists:areas,id',
        'street' => 'nullable|string|min:2|max:128',
        'building' => 'required|string|min:2|max:128',
        'floor' => 'nullable|string|min:1|max:128',
        'apartment' => 'nullable|string|min:2|max:128',
        'landmark' => 'nullable|string|min:2|max:128',
        'notes' => 'nullable|string|min:2|max:512',
    ];

    protected $messages = [
        'name.string' => 'The name must be a string.',
        'name.min' => 'Name must be at least 2 characters long.',
        'name.max' => 'Name should not be longer than 64 characters long.',
        'area.required' => 'Please select an area.',
        'area.exists' => 'The given area seems to be invalid',
        'street.string' => 'Street must be a string.',
        'street.min' => 'Street must be at least 2 characters long.',
        'street.max' => 'Street should not be longer than 128 characters.',
        'building.string' => 'Building must be a string.',
        'building.min' => 'Building must be at least 2 characters long.',
        'building.max' => 'Building should not be longer than 128 characters.',
        'floor.string' => 'Floor must be a string.',
        'floor.min' => 'Floor must be at least 2 characters long.',
        'floor.max' => 'Floor should not be longer than 128 characters.',
        'apartment.string' => 'Apartment must be a string.',
        'apartment.min' => 'Apartment must be at least 2 characters long.',
        'apartment.max' => 'Apartment should not be longer than 128 characters.',
        'landmark.string' => 'Landmark must be a string.',
        'landmark.min' => 'Landmark must be at least 2 characters long.',
        'landmark.max' => 'Landmark should not be longer than 128 characters.',
        'notes.string' => 'Notes must be a string.',
        'notes.min' => 'Notes must be at least 2 characters long.',
        'notes.max' => 'Notes should not be longer than 512 characters.',
    ];

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $address_id)
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
                'message' => 'Please update your contact information to continue.',
                'errors' => [],
            ], 422);

        }

        if ($customer->email && !$customer->email_verified && is_null($customer->contact_email)) {

            return response()->json([
                'status' => 0,
                'message' => 'Please verify you email address to continue.',
                'errors' => [],
            ], 422);

        }
        
        if ($customer->email && $customer->email_verified && is_null($customer->contact_email)) {

            return response()->json([
                'status' => 0,
                'message' => 'Something went terribly wrong, please try again.',
                'errors' => [],
            ], 422);

        }

        $address = CustomerAddress::where('id', $address_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (is_null($address)) {

            return response()->json([
                'status' => 0,
                'message' => 'Address not found.'
            ]);

        }

        $address->name = $request->name;
        $address->area_id = $request->area;
        $address->street = $request->street;
        $address->building = $request->building;
        $address->floor = $request->floor;
        $address->apartment = $request->apartment;
        $address->landmark = $request->landmark;
        $address->notes = $request->notes;

        $address->save();

        return response()->json([
            'status' => 1,
            'data' => $address,
        ], 201);

    }

}
