<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\State;
use App\Http\Controllers\Controller;

class CreateStateController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validation_rules = [

            'name' => [
                'required',
                'string',
                'unique:states',
                'min:3',
                'max:64',
            ],

        ];

        $validation_messages = [
            'name.required' => 'Please provide a name for the new state.',
            'name.string' => 'The name of a state should be a string.',
            'name.unique' => 'The state ":input" alread exists.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer te 64 characters'
        ];

        $validator = Validator::make($request->all(), $validation_rules, $validation_messages);

        if ($validator->fails()) {

            return response()->json([
                'errors' => $validator->errors()
            ], 422);

        }

        $vendor = State::create([
            'name' => $request->input('name'),
            'published' => '1',
        ]);
        
        return response()->json($vendor, 201);
        
    }

}
