<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Subcategory;
use App\Http\Resources\Subcategory as SubcategoryResource;
use App\Http\Resources\SubcategoriesCollection;

use App\Rules\Hashexists;
use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class SubcategoriesController extends Controller
{

    use ControllerTrait;

    protected $accepted_filters = [
        'category' => 'category_id'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = (int) $request->query('per_page', 15);
        
        // filters
        $categories_filter = $request->query('category');

        // Start the query
        $query = Subcategory::query();

        $query = $query->when(!is_null($categories_filter), function($query) use ($categories_filter) {
            
            $hashed_ids = explode(',', $categories_filter);

            $categories = array_map(function($id) {
                return (int) $this->unhash_id($id);
            }, $hashed_ids);

            return $query->whereIn('category_id', $categories);

        });
        
        
        $query = $query->where("status", "1");
        
        $collection = new SubcategoriesCollection(
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
                'unique:subcategories,name',
            ],
            'category_id' => [
                'required',
                new Hashexists('categories', 'id')
            ],
        ];

        $messages = [
            'name.required' => 'Please enter the name of the subcategory.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The subcategory ":input" already exists.',
            'category_id.required' => 'Please provider a category for this subcategory',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();
        
        $category_id = $request->input('category_id');
        $unhased = Hashids::decode($category_id);
        $decoded_category_id = array_shift($unhased);

        $subcategory = Subcategory::create([
            'name' => $request->input('name'),
            'category_id' => $decoded_category_id
        ]);
        
        return response()->json($subcategory, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $subcategory_id)
    {

        $unhased = Hashids::decode($subcategory_id);
        $decoded_id = array_shift($unhased);
        
        $subcategory = Subcategory::find($decoded_id);

        if (is_null($subcategory)) {

            return response()->json([
                'status' => 0,
                'message' => 'Subcategory not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $subcategory->toArray()
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
