<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Firebase\JWT\JWT;
use App\User;

class Application extends Model
{
    protected $fillable = [
    	'name', 
    	'description'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function generateAuthToken()
	{
	    $jwt = JWT::encode([
	        'iss' => $_ENV['APP_NAME'],
	        'sub' => $this->key,
	        'iat' => time(),
	        'exp' => time() + $_ENV['APP_TOKEN_TIME'],
	    ], $_ENV['APP_TOKEN_SECRET']);

	    return $jwt;
	}

	public function users() {
		return $this->belongsToMany('App\User');
	}
}
