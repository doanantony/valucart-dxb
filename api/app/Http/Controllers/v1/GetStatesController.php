<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\State;
use App\Http\Resources\StatesCollection;

use App\Http\Controllers\Controller;

class GetStatesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = (int) $request->query('per_page', 15);
        
        $states = new StatesCollection(
            State::where('published', '1')->paginate($per_page)
        );

        return $states->additional([
            'status' => 1
        ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $state_id)
    {

        $state = State::find($state_id);

        if ($state) {

            return response()->json([
                'status' => 1,
                'data' => $state,
            ], 200);

        }

        return response()->json([
            'staus' => 0,
            'message' => 'State not found.'
        ], 404);

    }

}
