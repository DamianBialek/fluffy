<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'additional_tokens'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'additional_tokens' => 'array'
    ];

    protected $jwtCustomClaims = [];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return $this->jwtCustomClaims;
    }

    public function addAdditionalTokens($name, $token)
    {
        if(!is_array($this->additional_tokens)) {
            $this->additional_tokens = [];
        }

        $this->additional_tokens = array_merge($this->additional_tokens, array($name => $token));
    }

    public function getAdditionalToken($name)
    {
        return is_array($this->additional_tokens) && array_key_exists($name, $this->additional_tokens) ? $this->additional_tokens[$name] : null;
    }
}
