<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;
use App\Models\HomeBanner;

class UpdateHomeBannerController extends Controller
{

    use ControllerTrait;

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $banner_id)
    {
        
        $rules = [

            'name' => [
                'nullable',
                'min:3',
                'max:256'
            ],
    
            'redirect_url' => [
                'nullable',
                'url',
                'min:3',
                'max:1024'
            ]

        ];

        $messages = [
            'name.min' => 'Name should be at least 3 characters',
            'name.max' => 'Name must not be longer than 256 characters.',
            'redirect_url.min' => 'Redirect URL should be at least 3 characters',
            'redirect_url.max' => 'Redirect URL must not be longer than 1024 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);

        }

     //   $banner_id = $this->unhash_id($banner_id);

        $banner = HomeBanner::find($banner_id);

        if (is_null($banner)) {

            return response()->json([
                'status' => 0,
                'errors' => [
                    'Banner not found'
                ],
            ], 404);

        }

        $name = preg_replace('/\s/', '-', $request->input('name'));
        $href = $request->input('redirect_url');

        $rename = false;

        if (!is_null($name) && $name != $banner->name) {

            $time = time();

            $landscape_name = $banner->landscape;
            $portrait_name = $banner->portrait;

            $landscape_name_ext = explode('.', $landscape_name);
            $landscape_name_ext = end($landscape_name_ext);
            $new_landscape_name = strtolower($name) . '-landscape-' . $time . '.' . $landscape_name_ext;

            $portrait_name_ext = explode('.', $portrait_name);
            $portrait_name_ext = end($portrait_name_ext);
            $new_portrait_name = strtolower($name) . '-portrait-' . $time . '.' . $portrait_name_ext;

            $banner->name = $name;
            $banner->landscape = $new_landscape_name;
            $banner->portrait = $new_portrait_name;

        }

        if (!is_null($href)) {
            $banner->href = $href;
        }
        
        \DB::beginTransaction();

        try {
            
            // update db
            $banner->save();
            
            if (!is_null($name) && isset($new_landscape_name) && isset($new_portrait_name)) {
                
                Storage::disk('s3')->move('banners/' . $landscape_name, 'banners/' . $new_landscape_name);
                Storage::disk('s3')->move('banners/' . $portrait_name, 'banners/' . $new_portrait_name);

            }

        } catch (\Throwable $e) {

            \DB::rollback();
            throw $e;

        }

        \DB::commit();
        
        return response()->json($banner, 200);

    }

}
