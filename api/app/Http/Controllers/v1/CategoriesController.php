<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoriesCollection;

use App\Rules\Hashexists;
use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
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
        
        // filters
        $departments_filter = $request->query('department');

        // Start the query
        $query = Category::query();

        $query = $query->when(!is_null($departments_filter), function($query) use ($departments_filter) {
            
            $hashed_ids = explode(',', $departments_filter);

            $departments = array_map(function($id) {
                return (int) $id;
            }, $hashed_ids);
            
            return $query->whereIn('department_id', $departments);

        });
        
        
        $query = $query->where("status", "1");

        $collection = new CategoriesCollection(
            $query->paginate($per_page)
        );

        return $collection->additional([
            'status' => 1
        ]);

    }


    public function getNearByCategories(Request $req)
    {
        $lat   = $req->query('lat');
        
        $long  = $req->query('long');

        $query =  Category::query();
        
        $query = $query->join('departments', 'departments.id', '=', 'categories.department_id');
        
        $query = $query->select('categories.*', DB::raw("6371 * acos(cos(radians(" . $lat . ")) * cos(radians(departments.latitude)) * cos(radians(departments.longitude) - radians(" . $long . ")) + sin(radians(" .$lat. ")) * sin(radians(departments.latitude))) AS distance"));
        
        $query = $query->where("status", "1");

        $query = $query->having('distance', '<', 5); 
        
        $query = $query->orderBy('name', 'asc');
        
        $collection = new CategoriesCollection(
            $query->get()
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
            'department_id' => [
                'required',
                new Hashexists('departments', 'id')
            ],
        ];

        $messages = [
            'name.required' => 'Please enter the name of the category.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The category ":input" already exists.',
            'department_id.required' => 'Please provider a department for this category',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $department_id = $request->input('department_id');
        $unhased = Hashids::decode($department_id);
        $decoded_department_id = array_shift($unhased);

        $category = Category::create([
            'name' => $request->input('name'),
            'department_id' => $decoded_department_id
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

        $unhased = Hashids::decode($category_id);
        $decoded_id = array_shift($unhased);
        
        $category = Category::find($decoded_id);

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
