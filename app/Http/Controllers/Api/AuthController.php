<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Application;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticateApp(Request $request) {
    	$credentials = base64_decode(
        	Str::substr($request->header('Authorization'), 6)
    	);

	    try {
	        list($appKey, $appSecret) = explode(':', $credentials);

	        $app = Application::whereKeyAndSecret($appKey, $appSecret)->firstOrFail();
	    } catch (\Throwable $e) {
            return response([
                'success' => false,
                'message' => 'invalid_credentials'
            ], 400);
	    }

	    if (! $app->active) {
            return response([
                'success' => false,
                'message' => 'app_inactive'
            ], 403);
	    }

	    return response([
	        'token_type' => 'Bearer',
	        'access_token' => $app->generateAuthToken(),
	        'expires_in' => $_ENV['APP_TOKEN_TIME'],
	    ]);
    }
}
