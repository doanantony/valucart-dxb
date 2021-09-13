<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\DepartmentCategory;
use App\Http\Resources\DepartmentCategoryCollection;

use App\Rules\Hashexists;
use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class DepartmentCategoryController extends Controller
{

    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {

        $per_page = (int) $request->query('per_page', 50);
        
       
        // Start the query
        $query = DepartmentCategory::query();

        $query = $query->where("status", "1");

        $collection = new DepartmentCategoryCollection(
            $query->paginate($per_page)
        );

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
                'unique:categories,name',
            ],
        ];

        $messages = [
            'name.required' => 'Please enter the name of the category.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The category ":input" already exists.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $department_category = DepartmentCategory::create([
            'name' => $request->input('name'),
        ]);
        
        return response()->json($department_category, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $category_id)
    {

        $unhased = Hashids::decode($category_id);

        $decoded_id = array_shift($unhased);
        
        $category = DepartmentCategory::find($decoded_id);

        if (is_null($category)) {

            return response()->json([
                'status' => 0,
                'message' => 'Category not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $category->toArray()
        ], 200);

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
