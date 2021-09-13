<?php

namespace App\OAuth;

use RuntimeException;
use League\OAuth2\Server\Entities\ClientEntityInterface;

use Laravel\Passport\Bridge\User;
use Laravel\Passport\Bridge\UserRepository;


class UserRepositoryOverride extends UserRepository
{

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username, $password, $grant_type, ClientEntityInterface $client_entity)
    {
        
        // Use a different provider based on the OAuth cliene
        if ($client_entity->getIdentifier() == env('ADMIN_OAUTH_CLIENT')) {

            $provider = config('auth.guards.administrators.provider');

        } else {

            $provider = config('auth.guards.customers.provider');

        }
        
        $model = config('auth.providers.'.$provider.'.model');

        if (is_null($model)) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        if (method_exists($model, 'findForPassport')) {
            $user = (new $model)->findForPassport($username);
        } else {
            $user = (new $model)->where('email', $username)->whereNull("oauth_provider")->first();
        }

        if (! $user) {
            return;
        } elseif (method_exists($user, 'validateForPassportPasswordGrant')) {
            if (! $user->validateForPassportPasswordGrant($password)) {
                return;
            }
        } elseif (! $this->hasher->check($password, $user->getAuthPassword())) {
            return;
        }
        
        return new User($user->getAuthIdentifier());
    }
}
