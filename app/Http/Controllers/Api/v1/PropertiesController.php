<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Property;
use Illuminate\Support\Facades\Auth;
use Gate;
use Illuminate\Support\Facades\Validator;

class PropertiesController extends Controller
{
    public function add(Request $request) {
    	if(Auth::user()->checkPolicy('property_add')) {
	    	$validator = Validator::make($request->all(), [
	          'name' => 'required|max:255|unique:properties,name',
	          'title' => 'required|max:255|unique:properties,title',
	          'type' => 'required|in:text',
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
    		$property = Property::create($request->all());
    	} catch(\Throwable $e) {
    		return response([
    			'success' => false,
    			'message' => $e->getMessage(),
    		]);
    	}


    	return response([
    		'success' => 'true',
    		'heading' => $property->toArray(),
    	]);
    }
}
