<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

// if($_SERVER['REMOTE_ADDR'] != '178.45.81.8') {
// 	die('На сайте в данный момент ведутся профилактические работы. В ближайшее время вы сможете пользоваться ресурсом.');
// 	return ;
// }

use App\Facades\Besplatnee;
use Illuminate\Http\Request;

// Artisan::call('view:clear');

Auth::routes();

Route::domain('{city}.' . env('APP_DOMAIN', 'besplatnee.net'))
		->get('/поиск-{alias}-в-городе-{city_name}-poisk-{alias_international}-v-gorode-{city_name_international}', 'AdvertController@searchRedirect')
		->name('search_redirect');

Route::domain(env('APP_DOMAIN', 'besplatnee.net'))
		->get('/поиск-{alias}-poisk-{alias_international}', 'AdvertController@searchRedirectNull')
		->name('search_redirect_null');


Route::domain('{city_alias}.' . env('APP_DOMAIN', 'besplatnee.net'))->get('/', 'AdvertController@index')
	->name('city');

Route::domain(env('APP_DOMAIN', 'besplatnee.net'))->get('/', 'HomeController@index')
	->name('home');

Route::get('/queries', 'HomeController@searchQueries')
	->name('searchQueries');

Route::get('/test', function (Request $request) {
	return require_once(dirname(__FILE__) . '/test.php');

	// $advertRepository = new \App\Repositories\AdvertRepository;
	// return $advertRepository->find(494539);

	// $repository = new \App\Repositories\GeoObjectRepository;
	// $repository->setPresenter(new \App\Presenters\GeoObject\CityPresenter);
	// dd($repository->findByAlias('samara'));

	return '';
});

Route::get('_run', function (Request $request) {
	return view('_run', [
		'offset' => $request->input('offset', 0),
		'limit' => $request->input('limit', 50),
	]);
});

Route::post('/import', function (Request $request) {
	$user = \App\User::where('phone', $request->input('user.phone'))->first();
	if(!$user) {
		$user = \Besplatnee::users()->add($request->input('user', []));
	}

	if(!$user) { return '0'; }

	$adv['creator_id'] = $user->id;
	$adv['owner_id'] = $user->id;

	$request->merge([
    	'creator_id' => $user->id,
    	'owner_id' => $user->id,
		'fullname' => $user->fullname(),
	]);

	if($adv = \App\Advert::where('owner_id', $user->id)->where('name', $request->input('name', ''))->first()) {
		$adv->created_at = $request->input('created_at');
		$adv->updated_at = $request->input('updated_at');
		$adv->fakeupdated_at = $request->input('fakeupdated_at');
		$adv->unpublished_on = $request->input('unpublished_on');
		$adv->heading_id = $request->input('heading_id');
		$adv->duplicate_in_all_cities = false;
		$adv->creator_id = $user->id;
		$adv->save();

		$adv->cities()->sync($request->input('city_ids', []));
		$adv->countries()->sync($request->input('country_ids', []));

		$adv;
	} else {
    	$adv = Besplatnee::adverts()->add($request->all());
    }

    if($adv) {
    	$redirect = \App\Redirect::where('source', $request->input('old_url'))->first();
    	if(!$redirect) {
    		$redirect = new \App\Redirect;
    	}

		$redirect->source = $request->input('old_url');
		$redirect->target = $adv->id;
		$redirect->active = true;

		$redirect->save();

    	return '1';
    } else {
    	return '0';
    }
});

// actions
Route::get('/ad/create', 'AdvertController@create')->name('advert-create');
Route::post('/ad/save', 'AdvertController@save')->name('advert-save');
Route::get('/ad/detail/{id}', 'AdvertController@detail')->name('advert-detail');
Route::get('/ad/preview/{id}', 'AdvertController@preview')->name('advert-preview');
Route::get('/ad/edit/{id}', 'AdvertController@edit')->name('advert-edit');
Route::get('/ad/success/{id}', 'AdvertController@success')->name('advert-success');
Route::post('/fileupload', 'UploadController@index')->name('fileupload');
Route::get('/sites/create', 'SiteController@create')->name('site.create');
Route::post('/sites/create', 'SiteController@save')->name('site.save');

Route::post('/search', 'AdvertController@search')->name('search');

Route::any('/ajax', function (Request $request) {
	return \App::make('\App\Http\Controllers\AjaxController')
		->callAction($request->input('action'), [$request]);
});

// админка
Route::group([
	'as' => 'admin.', 
	'prefix' => 'admin', 
	'middleware' => ['auth', 'auth.admin'],
	'namespace' => '\Admin',
], function () {

		Route::any('', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
		// список моделей
		Route::any('{model}', [
			'as' => 'list',
			'uses' => 'DashboardController@list'
		]);
		Route::any('{model}/remove/{id}', [
			'as' => 'remove',
			'uses' => 'DashboardController@remove',
		]);
		Route::any('{model}/inline/{action}/{id}', [
			'as' => 'inline',
			'uses' => 'DashboardController@inline',
		]);
		// создать модель
		Route::get('{model}/create', [
			'as' => 'create',
			'uses' => 'DashboardController@create'
		]);
		// сохранить новую модель
		Route::post('{model}/create', [
			'as' => 'create',
			'uses' => 'DashboardController@save'
		]);
		// редактировать модель
		Route::get('{model}/edit/{id}', [
			'as' => 'edit',
			'uses' => 'DashboardController@edit'
		]);
		// сохранить сеществующую модель
		Route::post('{model}/edit/{id}', [
			'as' => 'edit',
			'uses' => 'DashboardController@save'
		]);
});

// личный кабинет
Route::group([
	'as' => 'office.', 
	'prefix' => 'office', 
	'middleware' => ['auth']
], function () {

		Route::get('', ['as' => 'dashboard', 'uses' => 'OfficeController@index']);

		Route::get('adverts', ['as' => 'adverts', 'uses' => 'OfficeController@adverts']);
		Route::get('sites', ['as' => 'sites', 'uses' => 'OfficeController@sites']);
		Route::get('favorites', ['as' => 'favorites', 'uses' => 'OfficeController@favorites']);
		Route::get('mega', ['as' => 'mega', 'uses' => 'OfficeController@megaAdverts']);
		Route::get('sites', ['as' => 'sites', 'uses' => 'OfficeController@sites']);
		Route::get('shops', ['as' => 'shops', 'uses' => 'OfficeController@shops']);

});



/* adverts */

Route::get('/{alias_local}-{city_local}-{alias_international}-{city_international}-{id}', 'AdvertController@advert')
    ->name('advert')
    ->where([
    	'alias_local' => '[а-яА-Яa-zA-Z0-9_]+',
    	'city_local' => 'в_[а-яa-zA-ZА-Я_]+',
    	'alias_international' => '[a-zA-Z0-9_]+',
    	'city_international' => 'v_[a-zA-Z_]+',
    	'id' => '[0-9]+',
   ]);

Route::get('/{alias}-{city}-{sort}', 'AdvertController@category')
    ->name('category')
    ->where([
    	'alias' => '[a-zA-Zа-яА-Я0-9_]+',
    	'city' => 'v_[a-zA-Zа-яА-Я0-9_]+',
    	'sort' => '[a-z_]+',
    ]);

Route::get('/page/{alias}', 'PageController@index')->name('page');
Route::get('/partner/{alias}', 'SiteController@site')->name('partner');
Route::get('/magazin/{alias}', 'SiteController@shop')->name('magazin');
Route::get('/partner/{alias}/page/{id}', 'SiteController@page')->name('partnerpage');
Route::get('/magazin/{alias}/page/{id}', 'SiteController@page')->name('shoppage');
Route::get('/magazin/{alias}/product/{id}', 'SiteController@product')->name('product');




// sitemap
Route::get('/sitemap.xml', 'SiteMapController@index')->name('sitemap.xml');