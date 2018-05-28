<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Application;
use Illuminate\Support\Facades\Auth;

class ApiCheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $app = $request->input('__authenticatedApp');

        if($request->has('__authenticated_user_token')) {
            $userAuthCode = $request->input('__authenticated_user_token');
            if (!$userAuthCode || !$user = $app->users()->wherePivot('authorization_code', $userAuthCode)->first()) {
                return response([
                    'success' => false,
                    'message' => 'invalid_user_token',
                ], 400);
            }
        } else {
            if(!$user = User::find($app->owner_id)->first()) {
                return response([
                    'success' => false,
                    'message' => 'owner_not_found',
                ], 400);
            }
        }

        Auth::login($user);

        return $next($request);
    }
}
