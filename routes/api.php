<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api.auth.checktoken', 'api.auth.checkuser'])->any('/v{version}/{group}.{action}', function (Request $request, $version, $group, $action) {
	/*try {*/
		return \App::make('\App\Http\Controllers\Api\v' . $version . '\\' . ucfirst($group) . 'Controller')
			->callAction($action, [$request]);
	/*} catch(\Throwable $e) {
        return response([
            'success' => false,
            'message' => 'action_not_found'
        ], 404);
	}*/
})->where([
	'version' => '[1-9]+[0-9]*', 
	'group' => '[A-Za-z_]+', 
	'action' => '[A-Za-z_]+',
]);

Route::any('/v{version}/auth', 'Api\AuthController@authenticateApp')->where('version', '[1-9]+[0-9]*');