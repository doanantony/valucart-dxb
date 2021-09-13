<?php

namespace App\Models\v2;

use Hashids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class Customer extends Authenticatable
{
    
    use HasApiTokens, EntityTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'gender',
        'email',
        'contact_email',
        'email_verified',
        'phone_number',
        'secret',
        'oauth_provider',
        'oauth_privider_user_id',
        'cart_id',
        'fcm_token',
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'email',
        'gender',
        'email_verified',
        'phone_number',
        'phone_number_verified',
        'oauth_provider',
         'wallet',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
        'phone_number_verified' => 'boolean'
    ];

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return \App\User
     */
    public function findForPassport($username)
    {
        return $this->where('email', $username)->whereNull("oauth_provider")->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->secret);
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\v2\CustomerAddress');
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);

        foreach ($attributes as $key => $value) {
            $attributes[$key] = is_null($value) ? '' : $value;
        }

        return $attributes;

    }

}
