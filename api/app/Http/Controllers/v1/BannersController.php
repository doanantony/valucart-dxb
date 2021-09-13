<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Hashids;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Bundles;
use App\Models\Departments;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\BundlesCategoryies;

use App\Http\Resources\Collection;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class BannersController extends Controller
{

    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $position = "home_banners")
    {
        
        $query = Banner::query();
        
        if ($position && in_array($position, Banner::$positions)) {
            $query = $query->where("position", $position);
        }

        $query = $query->orderBy("order", "asc");

        if ($request->has("page")) {
            
            $per_page = $request->input("per_page", 15);
            $banners = $query->paginate($per_page);

        } else {

            $banners = $query->get();

        }

        $collection = new Collection($banners);

        return $collection->additional([
            "status" => 1
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

            "position" => [
                "required",
                Rule::in(Banner::$positions),
            ],

            "name" => [
                "nullable",
                "min:3",
                "max:128"
            ],

            "landscape" => [
                "required",
                "file",
                "image",
                "max:10240",
            ],

            "portrait" => [
                "required",
                "file",
                "image",
                "max:10240",
            ],

            "redirect_url" => [
                "nullable",
                "url",
                "min:3",
                "max:512"
            ],

            "resource_type" => [
                "nullable",
                "required_with:resource_identifier",
                Rule::in(Banner::$resource_types),
            ],
            
            "resource_identifier" => [
                "nullable"
            ],

        ];

        $messages = [
            "position.required" => "Please provide the position for this banner.",
            "position.in" => "Unknown banner position.",
            "name.min" => "Name should be at least 3 characters",
            "name.max" => "Name must not be longer than 128 characters.",
            "landscape.required" => "Please provide the landscape banner (for large screens).",
            "landscape.image" => "Landscape must a picture.",
            "landscape.size" => "Landscape picture must not be bigger than 10Mb.",
            "portrait.required" => "Please provide the portrait banner (for small/mobile screens).",
            "portrait.image" => "Portrait must a picture.",
            "portrait.size" => "Portrait picture must not be bigger than 10Mb.",
            "redirect_url.min" => "Redirect URL should be at least 3 characters",
            "redirect_url.max" => "Redirect URL must not be longer than 512 characters.",
            "resource_type.required_with" => "Please provide the resource type",
            "resource_type.in" => "Unknown resource type."
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
            ], 422);

        }

        $linked_resource = null;

        $position = $request->input("position");
        $name = $request->input("name");
        $redirect_url = $request->input("redirect_url");
        $resource_type = $request->input("resource_type");
        $resource_identifier = $request->input("resource_identifier");

        if ($resource_identifier && $resource_type) {
            
            $db_resource_id = $resource_identifier;

            if (!is_numeric($db_resource_id)) {
                $db_resource_id = $this->unhash_id($db_resource_id);
            }

            switch ($resource_type) {

                case "product":
                    $linked_resource = Product::find($db_resource_id);
                    break;

                case "bundle":
                    $linked_resource = Bundles::find($db_resource_id);
                    break;
                
                case "product_department":
                    $linked_resource = Departments::find($db_resource_id);
                    break;
                
                case "product_category":
                    $linked_resource = Category::find($db_resource_id);
                    break;

                case "product_sub_category":
                    $linked_resource = Subcategory::find($db_resource_id);
                    break;

                case "product_brand":
                    $linked_resource = Brand::find($db_resource_id);
                    break;

                case "bundle_category":
                    $linked_resource = BundlesCategoryies::find($db_resource_id);
                    break;
                
                default:
                    $linked_resource = null;
                    break;
            
            }

            if (!$linked_resource) {

                return response()->json([
                    "status" => 0,
                    "errors" => "The resource type or resource identifier might be invalid!",
                ], 422);
    
            }

        }

        // prepare order
        $group_count = Banner::where("position", $position)->count();
        $order = $group_count + 1;

        // Prepare names
        $landscape = $request->file("landscape");
        $portrait = $request->file("portrait");
        
        $landscape_name = substr(sha1($landscape->getClientOriginalName() . time()), 0, 12) . "-landscape." . $landscape->extension();
        $portrait_name = substr(sha1($portrait->getClientOriginalName() . time()), 0, 12) . "-portrait." . $portrait->extension();
        
        \DB::beginTransaction();

        try {
            
           // $resource_identifier_id = Hashids::encode($resource_identifier);
            // update db
            $banner = Banner::create([
                "position" => $position,
                "name" => $name,
                "landscape" => $landscape_name,
                "portrait" => $portrait_name,
                "href" => $redirect_url,
                "resource_type" => $resource_type,
                "resource_identifier" => $resource_identifier,
                "order" => $order,
            ]);

            // move files to aws
            $landscape->storeAs("banners/" . $position, $landscape_name, "s3");
            $portrait->storeAs("banners/" . $position, $portrait_name, "s3");

        } catch (\Throwable $e) {

            \DB::rollback();
            throw $e;

        }

        \DB::commit();

        // response
        return response()->json($banner, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        
        $banner = Banner::find($id);
        
        if (!$banner) {

            return response()->json([
                "status" => 0,
                "message" => "Banner not found"
            ], 404);

        }
        
        return response()->json($banner, 200);

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
        
        $banner = Banner::find($id);
        
        if (!$banner) {

            return response()->json([
                "status" => 0,
                "message" => "Banner not found"
            ], 404);

        }

        $rules = [

            "position" => [
                "nullable",
                Rule::in(Banner::$positions),
            ],

            "name" => [
                "nullable",
                "min:3",
                "max:128"
            ],

            "redirect_url" => [
                "nullable",
                "url",
                "min:3",
                "max:512"
            ],

            "resource_type" => [
                "nullable",
                "required_with:resource_identifier",
                Rule::in(Banner::$resource_types),
            ],
            
            "resource_identifier" => [
                "nullable"
            ],

        ];

        $messages = [
            "position.in" => "Unknown banner position.",
            "name.min" => "Name should be at least 3 characters",
            "name.max" => "Name must not be longer than 128 characters.",
            "redirect_url.min" => "Redirect URL should be at least 3 characters",
            "redirect_url.max" => "Redirect URL must not be longer than 512 characters.",
            "resource_type.required_with" => "Please provide the resource type",
            "resource_type.in" => "Unknown resource type."
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
            ], 422);

        }

        $linked_resource = null;

        $position = $request->input("position");
        $name = $request->input("name");
        $redirect_url = $request->input("redirect_url");
        $resource_type = $request->input("resource_type");
        $resource_identifier = $request->input("resource_identifier");

        if ($resource_identifier && $resource_type) {
            
            $db_resource_id = $resource_identifier;

            if (!is_numeric($db_resource_id)) {
                $db_resource_id = $this->unhash_id($db_resource_id);
            }

            switch ($resource_type) {

                case "product":
                    $linked_resource = Product::find($db_resource_id);
                    break;

                case "bundle":
                    $linked_resource = Bundles::find($db_resource_id);
                    break;
                
                case "product_department":
                    $linked_resource = Departments::find($db_resource_id);
                    break;
                
                case "product_category":
                    $linked_resource = Category::find($db_resource_id);
                    break;

                case "product_sub_category":
                    $linked_resource = Subcategory::find($db_resource_id);
                    break;

                case "product_brand":
                    $linked_resource = Brand::find($db_resource_id);
                    break;

                case "bundle_category":
                    $linked_resource = BundlesCategoryies::find($db_resource_id);
                    break;
                
                default:
                    $linked_resource = null;
                    break;
            
            }

            if (!$linked_resource) {

                return response()->json([
                    "status" => 0,
                    "errors" => "The resource type or resource identifier might be invalid!",
                ], 422);
    
            }

        }
        
        
        \DB::beginTransaction();

        try {
            
            // update db
            if ($name) {
                $banner->name = $name;
            }
            
            if ($redirect_url) {
                $banner->href = $redirect_url;
            }
    
            if ($resource_type) {
                $banner->resource_type = $resource_type;
            }
    
            if ($resource_identifier) {
                $banner->resource_identifier = $resource_identifier;
            }

            $banner->save();

            if ($position) {

                $old_position = $banner->position;

                $banner->position = Banner::$positions[($position - 1)];
                $banner->save();
                
                \Storage::disk("s3")->move(
                    "banners/" . $old_position . "/" . $banner->landscape,
                    "banners/" . $position . "/" . $banner->landscape
                );

                \Storage::disk("s3")->move(
                    "banners/" . $old_position . "/" . $banner->portrait,
                    "banners/" . $position . "/" . $banner->portrait
                );

            }

        } catch (\Throwable $e) {

            \DB::rollback();
            throw $e;

        }

        \DB::commit();

        // response
        return response()->json($banner, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        
        $banner = Banner::find($id);
        
        if (!$banner) {

            return response()->json([
                "status" => 0,
                "message" => "Banner not found"
            ], 404);

        }

        \DB::beginTransaction();

        try {
            
            $banner->delete();
            
            \Storage::disk('s3')->delete("banners/" . $banner->position . "/" . $banner->landscape);
            \Storage::disk('s3')->delete("banners/" . $banner->position . "/" . $banner->portrait);

        } catch (\Throwable $e) {

            \DB::rollback();
            throw $e;

        }

        \DB::commit();

        return response("", 204);

    }

    public function publishUnpublish(Request $request, $id)
    {
        
        $banner = Banner::find($id);
        
        if (!$banner) {

            return response()->json([
                "status" => 0,
                "message" => "Banner not found"
            ], 404);

        }

        $banner->published_at = is_null($banner->published_at) ? now()->format("Y-m-d H:i:s") : null ;
        
        $banner->save();

        return response()->json($banner, 200);

    }

    public function reorder(Request $request)
    {

        $rules = [
            "banner" => [
                "required",
            ],
            "move_to" => [
                "required",
                "integer",
            ]
        ];

        $messages = [
            "banner.required" => "Please provide the banner identifier!",
            "move_to.required" => "Please provide move to order position!",
            "move_to.integer" => "Move to must be an integer",
        ];

        $validator = Validator::make($rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                "message" => "The give data was invalid",
                "errors" => $validator->errors(),
            ], 422);

        }

        $banner = $request->input("banner");
        $move_to = $request->input("move_to", 1);
        
        $banner = Banner::find($banner);

        if (!$banner) {

            return response()->json([
                "status" => 0,
                "message" => "Banner not found"
            ], 404);

        }

        $group = Banner::where("position", $banner->position)
            ->orderBy("order", "asc")
            ->get();

        $count = $group->count();

        if ($count == 1 || $move_to < 1 || $move_to > $count) {

            $collection = new Collection($group);
            return response()->json($collection, 200);

        }


        $current_order = $banner->order;

        // if banner is moved back
        if ($move_to > $current_order) {

            foreach ($group as $b) {
                
                $b_order = $b->order;

                if (($b_order > $current_order) && ($b_order <= $move_to)) {
                    $b->order = $b_order - 1;
                    $b->save();
                }

            }

            $banner->order = $move_to;
            $banner->save();

        } 

        // If banner is moved front
        else if ($move_to < $current_order) {

            foreach ($group as $b) {
                
                $b_order = $b->order;

                if (($b_order < $current_order) && ($b_order >= $move_to)) {
                    $b->order = $b_order + 1;
                    $b->save();
                }

            }

            $banner->order = $move_to;
            $banner->save();

        }

        // refetch banners
        $banners = Banner::where("position", $banner->position)
            ->orderBy("order", "asc")
            ->get();
        
        $collection = new Collection($banners);
        return response()->json($collection, 200);

    }

    public function get_banner_positions(Request $request)
    {

        $positions = collect(Banner::$positions);

        $positions = $positions->map(function($position, $i) {

            return [
                "id" => $i + 1,
                "position" => $position
            ];

        });

        return response()->json([
            "status" => 1,
            "data" => $positions,
        ], 200);

    }

    public function get_resource_types(Request $request)
    {

        $resource_types = collect(Banner::$resource_types);

        $resource_types = $resource_types->map(function($resource_type, $i) {

            return [
                "id" => $i + 1,
                "position" => $resource_type
            ];

        });

        return response()->json([
            "status" => 1,
            "data" => $resource_types,
        ], 200);

    }

}
