<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        $key_location = env('OAUTH_KEYS_LOCATION');

        Passport::loadKeysFrom($key_location);

        Passport::tokensExpireIn(now()->addDays(30));

        Passport::refreshTokensExpireIn(now()->addDays(90));

        Passport::personalAccessClientId(env('PERSONAL_ACCESS_TOKEN_CLIENT'));
        
        Route::post('{version?}/oauth/token', [
            'uses' => 'App\OAuth\Controllers\PassportAccessTokenControllerOverride@issueToken',
            'as' => 'passport.token',
            'middleware' => 'throttle',
        ])->where('version', 'v[1-9]');

        Route::post('oauth/token', [
            'uses' => 'App\OAuth\Controllers\PassportAccessTokenControllerOverride@issueToken',
            'as' => 'passport.token',
            'middleware' => 'throttle',
        ])->where('version', 'v[1-9]');

    }
    
}
