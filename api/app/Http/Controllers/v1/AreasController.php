<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;

use App\Models\Area;
use App\Http\Resources\AreasCollection;
use App\Http\Controllers\Controller;

class AreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // Start the query
        $query = Area::query();

        // Filters
        $state = $request->query('state');
        $query = $query->when(!is_null($state), function($query) use ($state) {

            $states = explode(',', $state);
            return $query->whereIn('state_id', $states);

        });
        
        $query = $query->orderBy('state_id', 'asc')->orderBy('name', 'asc');

        if ($request->has('page')) {

            $per_page = (int) $request->query('per_page', 15);

            $collection = new AreasCollection(
                $query->paginate($per_page)
            );

        } else {

            $collection = new AreasCollection(
                $query->get()
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
    public function show(Request $request, $area_id)
    {
        
        $area = Area::find($area_id);

        if (is_null($area)) {
            
            return response()->json([
                'status' => 0,
                'message' => 'Area not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $area
        ], 200);

    }

}
