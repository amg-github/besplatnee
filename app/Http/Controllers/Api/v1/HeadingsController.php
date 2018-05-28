<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Heading;
use App\Property;
use Illuminate\Support\Facades\Auth;
use Gate;
use Illuminate\Support\Facades\Validator;

class HeadingsController extends Controller
{
    public function get(Request $request) {
    	$headings = Heading::skip($request->input('offset', 0))->take($request->input('limit', 100));

    	if($request->input('show_unactive') != '1') {
    		$headings = $headings->where('active', true);
    	}

    	$headings = $headings->with('properties');

    	return response([
    		'success' => true,
    		'cities' => $headings->get()->toArray(),
    	]);
    }

    public function tree(Request $request) {
    	$request->merge(['offset' => 0, 'limit' => 99999999]);

    	return response([
    		'success' => true,
    		'tree' => $this->_tree($request, $request->input('parent_id', 0)),
    	]);
    }

    private function _tree(Request $request, $parent_id) {
    	$headings = [];

    	foreach($this->_subs($request, $parent_id)->get()->toArray() as $heading) {
    		$heading['childrens'] = $this->_tree($request, $heading['id']);
    		$headings[] = $heading;
    	}

    	return $headings;
    }

    private function _subs(Request $request, $parent_id) {
    	$headings = Heading::where('parent_id', $parent_id)
    		->skip($request->input('offset', 0))
    		->take($request->input('limit', 100))
    		->with('properties');

    	if($request->input('show_unactive') != '1') {
    		$headings = $headings->where('active', true);
    	}

    	return $headings;
    }

    public function subs(Request $request) {
    	$headings = $this->_subs($request, $request->input('parent_id', 0));

    	return response([
    		'success' => true,
    		'cities' => $headings->get()->toArray(),
    	]);
    }

    public function update(Request $request) {

    }

    public function delete(Request $request) {
    	
    }

    public function add(Request $request) {
    	if(Auth::user()->checkPolicy('heading_add')) {
	    	$validator = Validator::make($request->all(), [
	          'name' => 'required|max:255|unique:headings,name',
	          'parent_id' => 'required|in:0,' . Heading::all()->implode('id', ','),
	          'allias' => 'required|alpha_dash|unique:headings,allias',
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
    		$heading = Heading::create($request->all());
    	} catch(\Throwable $e) {
    		return response([
    			'success' => false,
    			'message' => $e->getMessage(),
    		]);
    	}

    	if($request->has('properties')) {
    		$pivotData = ['sort' => 0];
    		foreach($properties = Property::whereIn('name', $request->input('properties', []))->get() as $property) {
    			if($heading->properties->contains($property)) {
    				$heading->properties()->updateExistingPivot($property->id, $pivotData);
    			} else {
    				$heading->properties()->attach($property->id, $pivotData);
    			}
    		}
    	}


    	return response([
    		'success' => 'true',
    		'heading' => $heading->toArray(),
    	]);

    }
}
