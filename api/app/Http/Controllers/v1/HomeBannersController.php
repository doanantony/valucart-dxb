<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;

use App\Models\HomeBanner;
use App\Http\Resources\HomeBannersCollection;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class HomeBannersController extends Controller
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

            $collection = new HomeBannersCollection(
                HomeBanner::paginate($per_page)
            );

        } else {

            $collection = new HomeBannersCollection(
                HomeBanner::all()
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
    public function show(Request $request, $banner_id)
    {
        
     //   $banner_id = $this->unhash_id($banner_id);
        
        $banner = HomeBanner::find($banner_id);

        if (is_null($banner)) {

            return response()->json([
                'status' => 0,
                'message' => 'Banner not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $banner,
        ], 200);

    }

}
