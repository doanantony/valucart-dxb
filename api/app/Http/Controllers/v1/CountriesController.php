<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Country;
use App\Http\Resources\CountriesCollection;

use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{

    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        

        if ($request->has('page')) {

            $per_page = (int) $request->query('per_page', 15);

            $collection = new CountriesCollection(
                Country::paginate($per_page)
            );

        } else {

            $collection = new CountriesCollection(
                Country::all()
            );

        }

        return $collection->additional([
            'status' => 1
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $country_id)
    {   
        
        $country_id = $this->unhash_id($country_id);
        
        $country = Country::find($country_id);

        if (is_null($country)) {

            return response()->json([
                'status' => 0,
                'message' => 'Country not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $country,
        ], 200);

    }

}
