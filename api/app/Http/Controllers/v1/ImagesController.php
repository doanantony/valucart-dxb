<?php

namespace App\Http\Controllers\v1;

use ImageServer;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

use App\Http\Controllers\Controller;

class ImagesController extends Controller
{

    public function handle(Request $request, ImageServer $imageServer, $folder, $path)
    {

        $full_path = null;

        $s3Client = new S3Client([
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);

        $new_source = new Filesystem(
            new AwsS3Adapter(
                $s3Client,
                env('AWS_BUCKET_V2'),
                null,
                [ 'StorageClass'  => 'REDUCED_REDUNDANCY' ]
            ),
            [
                'visibility' => 'public'
            ]
        );
        

        switch($folder) {
            
            case 'products':
                $imageServer->setSource($new_source);
                $full_path = '/products_images/' . $path;
                // $full_path = '/preLaunchBulkUpload/' . $path;
                break;

            case 'departments_icons':
                $imageServer->setSource($new_source);
                $full_path = '/departments_icons/' . $path;
                break;

            case 'communities':
                $full_path = '/communities_flags/' . $path;
                break;
            
            case 'banners':
                $imageServer->setSource($new_source);
                $full_path = 'banners/' . $path;
                break;

            case 'bundles':
                $imageServer->setSource($new_source);
                $full_path = 'bundles/' . $path;
                break;
            
            case 'vendors':
                $imageServer->setSource($new_source);
                $full_path = '/vendors/' . $path;
                break;

            case 'notification_images':
                $imageServer->setSource($new_source);
                $full_path = '/notification_images/' . $path;
                break;

            case 'offericons':
                $imageServer->setSource($new_source);
                $full_path = '/offericons/' . $path;
                break;

            case 'popular_customers':
                $full_path = '/popular_customers/' . $path;
                break;
                

        }
        
        if (!is_null($full_path)) {

            try {

                return $imageServer->getImageResponse($full_path, $request->query());

            } catch(\League\Glide\Filesystem\FileNotFoundException $e) {
                
                return response()->json([
                    'status' => 0,
                    'message' => 'Image not found!'
                ], 404);

            }

        }
        
        return response()->json([
            'status' => 0,
            'message' => 'Image not found!'
        ], 404);

    }

}
