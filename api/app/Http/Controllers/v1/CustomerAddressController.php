<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;

use App\Models\CustomerAddress;
use App\Http\Controllers\Controller;

class CustomerAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $customer = $request->user();

        return response()->json([
            'status' => 1,
            'data' => $customer->addresses,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $address_id)
    {
        $address = CustomerAddress::find($address_id);

        if (is_null($address)) {

            return response()->json([
                'status' => 0,
                'message' => 'Address not found.',
            ], 422);

        }
        
        return response()->json([
            'status' => 1,
            'data' => $address
        ]);

    }

    public function delete(Request $request, $address_id)
    {
        $address = CustomerAddress::find($address_id);

        if (is_null($address)) {

            return response()->json([
                'status' => 0,
                'message' => 'Address not found.',
            ], 422);

        }

        $address->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Address was successfuly deleted.'
        ], 200);

    }

}
