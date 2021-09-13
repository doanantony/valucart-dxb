<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Offers;
use App\Http\Resources\OffersCollection;
use App\Http\Resources\Offers as OffersResource;

use App\Http\Controllers\ControllerTrait;
use App\Http\Controllers\Controller;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
       
        
        $per_page = (int) $request->query('per_page', 15);

        $query = Offers::query();

        $query = $query->where("status", "1");

        $collection = new OffersCollection($query->paginate($per_page));

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $brand_id)
    {   
        
        $unhased = Hashids::decode($brand_id);
        $decoded_id = array_shift($unhased);
        
        $offers = Offers::find($decoded_id);

        if (is_null($offers)) {

            return response()->json([
                'status' => 0,
                'message' => 'Offer not found'
            ], 404);

        }

        return response()->json([
            'status' => 1,
            'data' => $offers->toArray()
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


    protected function update_bankoffer_image(Request $request, $offer_id)
    {   

        $offer = Offers::find($offer_id);

        if (is_null($offer)) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => 'Unknown offer.'
            ], 404);
            
        }

       if ($request->hasFile('offerimage') && $request->file('offerimage')->isValid()) {

            $encode =  Hashids::encode($offer->id);
            
            $path = 'offericons/'.$encode.'/'.$offer->image;
           
            if(Storage::disk('s3')->exists($path)) {
              
                Storage::disk('s3')->delete($path);

             }

            $thumbnail = $request->file('offerimage');
                    
            $current_timestamp = time();
                    
            $thumbnail_name = 'thumb' .$current_timestamp. '.'.$thumbnail->extension();
          
            $thumbnail->storeAs('offericons', Hashids::encode($offer->id) . '/' . $thumbnail_name, 's3');

            DB::table('offers')
                ->where('id', $offer_id)
                ->update(['image' => $thumbnail_name]);

             return response()->json([
                'status' => 1,
            ], 200);


       }

    }



}
