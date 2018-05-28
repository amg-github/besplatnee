<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\City;
use Illuminate\Support\Facades\Auth;
use Gate;
use Illuminate\Support\Facades\Validator;

class CitiesController extends Controller
{
    public function get(Request $request) {
    	$cities = City::skip($request->input('offset', 0))->take($request->input('limit', 100));
    	if($request->input('show_unactive') != '1') {
    		$cities = $cities->where('active', 1);
    	}

    	return response([
    		'success' => true,
    		'cities' => $cities->get()->toArray(),
    	]);
    }

    public function update(Request $request) {

    }

    public function delete(Request $request) {
    	
    }

    public function add(Request $request) {
    	if(Auth::user()->checkPolicy('city_add')) {
	    	$validator = Validator::make($request->all(), [
	          'name' => 'required|max:255|unique:cities,name',
	          'country' => 'required',
	          'region' => 'required',
	          'index' => 'required|unique:cities,index',
	          'allias' => 'required|alpha_dash|unique:cities,allias',
	        ]);

	        if($validator->fails()) {
	        	return response([
	        		'success' => false,
	        		'messages' => $validator->messages(),
	        	]);
	        }
    	} else {
    		return response([
    			'success' => false,
    			'message' => 'access_denied',
    		]);
    	}

    	try {
    		$city = City::create($request->all());
    	} catch(\Throwable $e) {
    		return response([
    			'success' => false,
    			'message' => $e->getMessage(),
    		]);
    	}


    	return response([
    		'success' => 'true',
    		'city' => $city->toArray(),
    	]);

    }
}
