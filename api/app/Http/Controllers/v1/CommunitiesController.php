<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Community;
use App\Http\Resources\CommunitiesCollection;
use App\Http\Resources\Community as CommunityResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

use App\Rules\Hashexists;

class CommunitiesController extends Controller
{

     use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_bck(Request $request)
    {   
      

        $per_page = (int) $request->query('per_page', 15);
        
        $communities = new CommunitiesCollection(
            Community::paginate($per_page)
        );

        return $communities->additional([
            'status' => 1
        ]);

    }

    public function index(Request $request)
    {
        
        // Start the query
        $query = Community::query();

       
        $query = $query->orderBy('name', 'asc');
      //  $query = $query->whereNotIn('id',[6, 9] );
        $query = $query->where('published', '1');
        $query = $query->where('name','!=', 'all');
        $query = $query->where('name','!=', 'Others');
        
        if ($request->has('page')) {

            $per_page = (int) $request->query('per_page', 15);

            $collection = new CommunitiesCollection(
                $query->paginate($per_page)
            );

        } else {

            $collection = new CommunitiesCollection(
                $query->get()
            );

      

        return $collection->additional([
            'status' => 1
        ]);

    }
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $country_id = $this->unhash_id($request->input('country'));

        $rules = [

            'name' => [
                'required',
                'string',
                'min:3',
                'max:64',
                Rule::unique('communities', 'name')->where(function() use ($country_id) {
                    return $query->where('country_id', $country_id);
                }),
            ],

            'country' => [
                'required',
                new Hashexists('countries', 'id')
            ],

            'published' => [
                'nullable',
                'boolean'
            ],

        ];

        $messages = [
            'name.required' => 'Please enter the name of the community.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The community ":input" already exists.',
            'country.required' => 'Please provide select a country for the community.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);

        }

        $community = Community::create([
            'name' =>$request->input('name'),
            'country_id' => $country_id,
            'published' => (int) $request->input('published', 1)
        ]);
        
        return response()->json($community, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $community_id)
    {   

        $community_id = $this->unhash_id($community_id);

        $community = Community::find($community_id);

        if ($community) {

            return response()->json([
                'status' => 1,
                'data' => $community,
            ], 200);

        }

        return response()->json([
            'staus' => 0,
            'message' => 'Community not found.'
        ], 404);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
