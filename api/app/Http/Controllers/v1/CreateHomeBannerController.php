<?php

namespace App\Http\Controllers\v1;

use Validator;
use Illuminate\Http\Request;

use App\Models\HomeBanner;
use App\Http\Controllers\Controller;

class CreateHomeBannerController extends Controller
{

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
                'min:3',
                'max:256'
            ],
    
            'redirect_url' => [
                'required',
                'url',
                'min:3',
                'max:1024'
            ],

            'landscape' => [
                'required',
                'file',
                'image',
                'max:10240',
            ],

            'portrait' => [
                'required',
                'file',
                'image',
                'max:10240',
            ]

        ];

        $messages = [
            'name.required' => 'Please provide a discriptive name for the banner.',
            'name.min' => 'Name should be at least 3 characters',
            'name.max' => 'Name must not be longer than 256 characters.',
            'redirect_url.required' => 'Please provide a redirect URL for the banner.',
            'redirect_url.min' => 'Redirect URL should be at least 3 characters',
            'redirect_url.max' => 'Redirect URL must not be longer than 1024 characters.',
            'landscape.required' => 'Please provide the landscape banner (for large screens).',
            'landscape.image' => 'Landscape must a picture.',
            'landscape.size' => 'Landscape picture must not be bigger than 10Mb.',
            'portrait.required' => 'Please provide the portrait banner (for small/mobile screens).',
            'portrait.image' => 'Portrait must a picture.',
            'portrait.size' => 'Portrait picture must not be bigger than 10Mb.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);

        }

        $name = $request->name;
        $href = $request->redirect_url;

        // Prepare names
        $landscape = $request->file('landscape');
        $portrait = $request->file('portrait');

        $time = time();

        $landscape_name = strtolower($name) . '-landscape-' . $time . '.' . $landscape->extension();
        $portrait_name = strtolower($name) . '-portrait-' . $time . '.' . $portrait->extension();
        
        \DB::beginTransaction();

        try {
            
            // update db
            $banner = HomeBanner::create([
                'name' => $name,
                'href' => $href,
                'landscape' => $landscape_name,
                'portrait' => $portrait_name,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ]);

            // move files to aws
            $landscape->storeAs('banners', $landscape_name, 's3');
            $portrait->storeAs('banners', $portrait_name, 's3');

        } catch (\Throwable $e) {

            \DB::rollback();
            throw $e;

        }

        \DB::commit();

        // response
        return response()->json($banner, 201);

    }

}
