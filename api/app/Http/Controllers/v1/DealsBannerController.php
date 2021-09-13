<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\DealsBanner;
use App\Http\Resources\DealsBannerCollection;


use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class DealsBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $query = DealsBanner::with(
            'department',
        );

        $query = $query->where("published", "1");

        $query = $query->when($request->has('freedelivery'), function($query) {
            return $query->where('type', 'freedelivery');
        });

        $query = $query->when($request->has('expresselivery'), function($query) {
            return $query->where('type', 'expresselivery');
        });

        $query = $query->when($request->has('greatdeals'), function($query) {
            return $query->where('type', 'greatdeals');
        });

        $per_page = (int) $request->query('per_page', 14);

        $collection = new DealsBannerCollection($query->paginate($per_page));

        return $collection->additional([
            'status' => 1
        ]);
    }

}
