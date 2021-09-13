<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\BundlesCategories;
use App\Http\Resources\BundlesCategoriesCollection;
use App\Http\Resources\BundlesCategories as BundleCategoriesResource;

use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class BundlesCategoriesController extends Controller
{   
     
    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $page = (int) $request->query('page');
        $per_page = (int) $request->query('per_page', 15);

        if ($page) {

            $collection = new BundlesCategoriesCollection(
                BundlesCategories::paginate($per_page)
            );

        } else {

            $collection = new BundlesCategoriesCollection(
                BundlesCategories::all()
            );

        }

        return $collection->additional([
            'status' => 1
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [

            'name' => [
                'required',
                'string',
                'min:3',
                'max:64',
                'unique:bundles_categories,name',
            ],

        ];

        $messages = [
            'name.required' => 'Please enter the name of the Bundle Category.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The Bundle category :input already exists.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $category = BundlesCategories::create([
            'name' => $request->input('name')
        ]);
        
        return response()->json($category, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $category_id)
    {

        $category_id = $this->unhash_id($category_id);
        
        $category = BundlesCategories::find($category_id);

        if (is_null($category)) {

            return response()->json([
                'status' => 0,
                'message' => 'Bundle category not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $category
        ], 201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category_id)
    {
        
        $category_id = $this->unhash_id($category_id);

        $category = BundlesCategories::find($category_id);

        if (is_null($category)) {

            return response('', 404);

        }

        $rules = [

            'name' => [
                'required',
                'string',
                'min:3',
                'max:64',
                Rule::unique('bundles_categories', 'name')->ignore($category->id),
            ],

        ];

        $messages = [
            'name.required' => 'Please provide the name of the category.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The Bundle category :input already exists.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $category->name = $request->input('name');
        $category->save();
        
        return response()->json($category, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id)
    {
        
        $category_id = $this->unhash_id($category_id);

        $category = BundlesCategories::find($category_id);

        if (is_null($category)) {

            return response('', 404);

        }

        $category->detele();

        return response('', 204);

    }

}
