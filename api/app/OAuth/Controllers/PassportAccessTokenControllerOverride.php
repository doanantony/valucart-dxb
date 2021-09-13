<?php
namespace App\OAuth\Controllers;
use Exception;
use Throwable;
use Socialite;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Models\Customer;


class PassportAccessTokenControllerOverride extends AccessTokenController
{
/**
* Authorize a client to access the user's account.
*
* @param  \Psr\Http\Message\ServerRequestInterface  $request
* @return \Illuminate\Http\Response
*/


public function issueToken(ServerRequestInterface $request)
{ 
    //try {
        $request_body = $request->getParsedBody();
        if (array_key_exists('provider', $request_body) && array_key_exists('token', $request_body)) {
            if (!in_array($request_body['provider'], ['facebook', 'google'])) {
                throw new \Exception('Provider not supported!');
            }
          /*  $social_user_profile = Socialite::driver($request_body['provider'])
            ->stateless()
            ->userFromToken($request_body['token']);
            //print_r($social_user_profile);*/

           /* $customer = Customer::where('oauth_provider', $request_body['provider'])
            ->where('oauth_privider_user_id', $social_user_profile->getId())
            ->first();*/
           /* $user_email = null;
            if ($user_email) {
                $customer = Customer::create([
                    'oauth_provider' => $request_body['provider'],
                    'oauth_privider_user_id' => 'no id',
                    'name' => $request_body['username'],
                    'email' => $request_body['email'],
                    'contact_email' => $request_body['email'],
                    'email_verified' => '1',
                ]);
                return response()->json([
                    'token_type' => 'Bearer',
                    'access_token' => $request_body['token']
                ], 200);
            } else { */
               // $user_email = $social_user_profile->getName();
               $user_email = $request_body['email'];
                if(!$user_email) {
                    throw new \Exception('Valucart requires your email address to sign up.');
                }
                
                $auth_type = $request_body['auth_type'];
                
                if($auth_type == 'register'){

                    if (Customer::where('email', $user_email)->exists()) {
                        //throw new \Exception('Email ' + $user_email + ' is already associated with another account');
                        return response()->json([
                            'status' => 400,
                            'message' => 'Email is already associated with another account'
                        ], 400);
                    }
        
                $customer = Customer::create([
                    'oauth_provider' => $request_body['provider'],
                    'oauth_privider_user_id' => $request_body['provider_id'],
                    'name' => $request_body['username'],
                    'email' => $request_body['email'],
                    'contact_email' => $request_body['email'],
                    'email_verified' => '1',
                ]);
                
                
                if ($customer) {
                    return response()->json([
                        'status' => 1,
                        'token_type' => 'Bearer',
                        'access_token' => $this->make_token_for_social_login($customer)['access_token']
                    ], 200);
                }
                
                }
                
                if($auth_type == 'login'){
                    $customer = Customer::where('oauth_provider', $request_body['provider'])
                        ->where('oauth_privider_user_id', $request_body['provider_id'])
                        ->first();
                        
                if ($customer) {
                    return response()->json([
                        'token_type' => 'Bearer',
                        'access_token' => $this->make_token_for_social_login($customer)['access_token']
                    ], 200);
                }
                }
           // }
        }
    // } catch(\GuzzleHttp\Exception\ClientException $e) {
    //     return response()->json([
    //         'status' => 0,
    //         'message' => $e->getMessage(),
    //     ], 401);
    // } catch(\Exception $e) {
    //     return response()->json([
    //         'status' => 0,
    //         'message' => 'The given data was invalid.',
    //         'errors' => [
    //             'provider' => $e->getMessage()
    //         ],
    //     ], 422);
    // }
    return $this->withErrorHandling(function () use ($request,$request_body) {
        $response = $this->convertResponse(
            $this->server->respondToAccessTokenRequest($request, new Psr7Response)
        );
        $decoded_response = json_decode($response->content(), true);
        $decoded_response['status'] = 1;
        $arr = explode('.',$decoded_response['access_token']);
        $test = base64_decode($arr[1]);
        $test = json_decode($test, true);
        $customer_id = (int) $test['sub'];
        return response()->json($decoded_response, 200);
    });
}


/**
* Perform the given callback with exception handling.
*
* @param  \Closure  $callback
* @return \Illuminate\Http\Response
*/


protected function withErrorHandling($callback)
{
    try {
        return $callback();
    } catch (OAuthServerException $e) {
        $this->exceptionHandler()->report($e);
        // return $this->convertResponse(
        //     $e->generateHttpResponse(new Psr7Response)
        // );
        return response()->json([
            'status' => 0,
            'message' => $e->getMessage()
        ], $e->getHttpStatusCode());
    } catch (Exception $e) {
        $this->exceptionHandler()->report($e);
        return new Response($this->configuration()->get('app.debug') ? $e->getMessage() : 'Error.', 500);
    } catch (Throwable $e) {
        $this->exceptionHandler()->report(new FatalThrowableError($e));
        return new Response($this->configuration()->get('app.debug') ? $e->getMessage() : 'Error.', 500);
    }
}

protected function make_token_for_social_login(Customer $customer)
{
    $access_tokens = resolve(\Laravel\Passport\Bridge\AccessTokenRepository::class);
    $client = \DB::table('oauth_clients')
    ->where('id', env('PASSWORD_ACCESS_TOKEN_CLIENT'))
    ->first(['id', 'name', 'redirect']);
    $oauth_client = new \Laravel\Passport\Bridge\Client($client->id, $client->name, $client->redirect);
    $token = $access_tokens->getNewToken($oauth_client, [new \Laravel\Passport\Bridge\Scope('*')], $customer->id);
    $token->setClient($oauth_client);
    $token->setIdentifier((string) \Str::uuid());
    $token->setExpiryDateTime(now()->addDays(30));
    $private_key = env('OAUTH_KEYS_LOCATION') . 'oauth-private.key';
    $key = new \League\OAuth2\Server\CryptKey($private_key);
    $access_tokens->persistNewAccessToken($token);
    // Make refresh token
    // $refresh_tokens = resolve(\Laravel\Passport\Bridge\RefreshTokenRepository::class);
    // $refresh_token = new \Laravel\Passport\Bridge\RefreshToken;
    // $refresh_token->setAccessToken($token);
    // $refresh_token->setIdentifier((string) \Str::uuid());
    // $refresh_token->setExpiryDateTime(now()->addDays(90));
    // $refresh_tokens->persistNewRefreshToken($refresh_token);
    return [
        'access_token' => (string) $token->convertToJWT($key),
        'refresh_token' => ''
    ];
}
}