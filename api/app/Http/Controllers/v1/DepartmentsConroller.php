<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Departments;
use App\Models\DepartmentCategoryDepartment;
use App\Http\Resources\Departments as DepartmentsResource;
use App\Http\Resources\DepartmentsCollection;
use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorsCollection;


class DepartmentsConroller extends Controller
{   
     use ControllerTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Departments::query()->whereNull('deleted_at');

        $query = $query->when($request->has('popular'), function($query) {
            $query->where('is_popular', 1);
        });


        $query = $query->orderByRaw("FIELD(name , 'ramadan specials' ) DESC");

        $query = $query->orderBy('name', 'asc');

        if ($request->has('page')) {
            
            $per_page = (int) $request->query('per_page', 15);
            $query = $query->paginate($per_page);

        } else {
            
            $query = $query->get();

        }

        $collection = new DepartmentsCollection($query);

        return $collection->additional([
            'status' => 1
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDepartmentsByCordinates(Request $req, $category_id)
    {   

        $unhased = Hashids::decode($category_id);
        
        $decoded_id = array_shift($unhased);


        $lat   = $req->query('lat');
        
        $long  = $req->query('long');

        $query = DepartmentCategoryDepartment::with('settings');

        //$query = DepartmentCategoryDepartment::query();
        
        $query = $query->join('departments', 'department_category_department.department_id' ,'=', 'departments.id' );


        $query = $query->select('department_category_department.category_id' , 'departments.id','departments.user_type_id','departments.name','departments.email','departments.icon','departments.image',DB::raw("6371 * acos(cos(radians(" . $lat . ")) * cos(radians(departments.latitude)) * cos(radians(departments.longitude) - radians(" . $long . ")) + sin(radians(" .$lat. ")) * sin(radians(departments.latitude))) AS distance"));
        
        $query = $query->where('department_category_department.category_id', $decoded_id);

        $query = $query->having('distance', '<', 5); 
        
        $query = $query->orderBy('departments.name', 'asc');
        
    
        $query = $query->get();

        $collection = new DepartmentsCollection($query);

        return $collection->additional([
            'status' => 1
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                'unique:departments,name',
            ],
        ];

        $messages = [
            'name.required' => 'Please enter the name of the  department.',
            'name.string' => 'The name should be a string.',
            'name.min' => 'The name should be at least 3 characters long.',
            'name.max' => 'The name should not be longer than 64 characters.',
            'name.unique' => 'The  department ":input" already exists.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $department = Departments::create([
            'name' => $request->input('name')
        ]);
        
        return response()->json($department, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $department_id)
    {
        $unhased = Hashids::decode($department_id);
        $decoded_id = array_shift($unhased);
        
        $department = Departments::find($decoded_id);

        if (is_null($department)) {

            return response()->json([
                'status' => 0,
                'message' => 'Department not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $department->toArray()
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


    protected function update_department_icon(Request $request, $department_id)
    {   

        $department = Departments::find($department_id);

        if (is_null($department)) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => 'Unknown department.'
            ], 404);
            
        }
       // print_r($department->name);die;
        $department->save();

        $department_images = [];
       

        if ($request->hasFile('departmentimage') && $request->file('departmentimage')->isValid()) {

             $encode =  Hashids::encode($department->id);
      
             $image_path = 'http://v2api.valucart.com/img/departments/'.$encode.'/'.$department->icon;
            

             $urlparts = parse_url($image_path);

             $extracted = $urlparts['path'];

             $path = substr("$extracted",30);

                
             if(Storage::disk('s3')->exists($path)) {
             
                Storage::disk('s3')->delete($path);

             }


                    $thumbnail = $request->file('departmentimage');
                    
                    $current_timestamp = time();
                    
                    $thumbnail_name = $department->name .$current_timestamp.'.'. $thumbnail->extension();
                    
                    $thumbnail->storeAs('departments_icons', Hashids::encode($department->id) . '/' . $thumbnail_name, 's3');
                
                    \DB::table('departments')
                        ->where('id', $department->id)
                        ->update([
                            'icon' => $thumbnail_name,
                        ]);

        }


        return response()->json([
            'status' => 1,
        ]);


    }


    protected function update_department_image(Request $request, $department_id)
    {   

        $department = Departments::find($department_id);

        if (is_null($department)) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => 'Unknown department.'
            ], 404);
            
        }
       // print_r($department->name);die;
        $department->save();

        $department_images = [];
       

        if ($request->hasFile('department_backimage') && $request->file('department_backimage')->isValid()) {

             $encode =  Hashids::encode($department->id);
      
             $image_path = 'http://v2api.valucart.com/img/departments/'.$encode.'/'.$department->image;
            

             $urlparts = parse_url($image_path);

             $extracted = $urlparts['path'];

             $path = substr("$extracted",30);

                
             if(Storage::disk('s3')->exists($path)) {
             
                Storage::disk('s3')->delete($path);

             }


                    $thumbnail = $request->file('department_backimage');
                    
                    $current_timestamp = time();
                    
                    $thumbnail_name = $department->name.'thumb' .$current_timestamp.'.'. $thumbnail->extension();
                    
                    $thumbnail->storeAs('departments_icons', Hashids::encode($department->id) . '/' . $thumbnail_name, 's3');
                
                    \DB::table('departments')
                        ->where('id', $department->id)
                        ->update([
                            'image' => $thumbnail_name,
                        ]);

        }


        return response()->json([
            'status' => 1,
        ]);


    }


    //get vendors by location
    public function getVendorsByCordinates(Request $req, $category_id){

        $pattern = DB::table('vendor_location')->select('name', 'id','latitude','longitude','range')->get();

        $request = array('latitude' => 25.2447198,'longitude' =>55.2962462 );

        if (!empty($pattern)){

            foreach($pattern as $res){

                if ($res->latitude == '' || $res->longitude == ''){
                    continue;
                }

                $check_in_range = $this->check_range($res, $request);

                if ($check_in_range){

                    $new_res[] = $res->id;
                }
            }


            $ids = implode(',', $new_res);

            $query = new Departments();

            $suppliers = $query
                 ->select('departments.id','departments.name','departments.image','vendor_location.vendor_id',
                         'vendor_location.short_name','system_settings.minimum_order','system_settings.delivery_charge')
                ->leftjoin('vendor_location', 'departments.id', '=', 'vendor_location.vendor_id')
                ->leftjoin('system_settings', 'departments.id', '=', 'system_settings.vendor_id')
                ->whereIn('vendor_location.id', [1, 2, 3]);

            $collection = $suppliers->get();
            $perPage = 5;

            return VendorsCollection::collection($suppliers->paginate($perPage))->additional(['status' => 1]);

        }
    }

    function check_range($rs, $rq) {

            $latitude2 = $rq['latitude'];

            $longitude2 = $rq['longitude'];

            $latitude1 = $rs->latitude;

            $longitude1 = $rs->longitude;

            $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

            if ($range <= $rs->range) {

                  $latitude2 = $rq['latitude'];

                  $longitude2 = $rq['longitude'];

                  $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

                  if ($range <= $rs->range) {

                        return true;

                  } else {

                        return false;

                  }

            } else {

                  return false;

            }

      }


    function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {

            $earth_radius = 6371;

            $dLat = deg2rad($latitude2 - $latitude1);

            $dLon = deg2rad($longitude2 - $longitude1);

            $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * asin(sqrt($a));

            $d = $earth_radius * $c;

            return $d;

      }






}