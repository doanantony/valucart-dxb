<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use League\Flysystem\Filesystem;

class GlideServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $s3Client = new \Aws\S3\S3Client([
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);
        
        $source = new Filesystem(
            new \League\Flysystem\AwsS3v3\AwsS3Adapter(
                $s3Client,
                env('AWS_BUCKET'),
                null,
                [ 'StorageClass'  => 'REDUCED_REDUNDANCY' ]
            ),
            [
                'visibility' => 'public'
            ]
        );

        $cache = new Filesystem(
            new \League\Flysystem\AwsS3v3\AwsS3Adapter(
                $s3Client,
                env('AWS_BUCKET_V2'),
                'cache',
                [ 'StorageClass'  => 'REDUCED_REDUNDANCY' ]
            ),
            [
                'visibility' => 'private'
            ]
        );

        // Set image manager
        $imageManager = new \Intervention\Image\ImageManager([
            'driver' => 'gd',
        ]);

        // Set manipulators
        $manipulators = [
            new \League\Glide\Manipulators\Orientation(),
            new \League\Glide\Manipulators\Crop(),
            new \League\Glide\Manipulators\Size(2000*2000),
            new \League\Glide\Manipulators\Brightness(),
            new \League\Glide\Manipulators\Contrast(),
            new \League\Glide\Manipulators\Gamma(),
            new \League\Glide\Manipulators\Sharpen(),
            new \League\Glide\Manipulators\Filter(),
            new \League\Glide\Manipulators\Blur(),
            new \League\Glide\Manipulators\Pixelate(),
            new \League\Glide\Manipulators\Watermark(),
            new \League\Glide\Manipulators\Background(),
            new \League\Glide\Manipulators\Border(),
            new \League\Glide\Manipulators\Encode(),
        ];

        // Set API
        $api = new \League\Glide\Api\Api($imageManager, $manipulators);

        $this->app->singleton(\League\Glide\Server::class, function ($app) use ($source, $cache, $api) {

            $server = new \League\Glide\Server($source, $cache, $api);

            // Set response factory
            $server->setResponseFactory(new \League\Glide\Responses\LaravelResponseFactory());

            return $server;

        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
