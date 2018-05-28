<?php

namespace App\Http\Controllers\Api\v1;

use App\Advert;
use App\User;
use App\City;
use App\Heading;
use App\Phone;
use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Gate;
use Illuminate\Support\Facades\Validator;

class AdvertsController extends Controller
{
    public function get(Request $request) {
    	if($request->has('ids')) {
    		$ids = $request->input('ids');
    		if(!is_array($ids)) {
    			$ids = [$ids];
    		}
        } else {
            $ids = [];
        }

    	$adverts = Advert::whereIn('id', $ids)->with('properties')->with('context')->with('medias')->get();
        $advertsArray = [];

        foreach($adverts as $advert) {
            $advertArray = $advert->toArray();
            // -gates
            $advertsArray[] = $advertArray;
        }
        

    	return response([
    		'success' => true,
    		'adverts' => $advertsArray,
    	]);
    }

    public function search(Request $request) {
        // offset
        $adverts = Advert::skip($request->input('offset', 0))->take($request->input('limit', 999999999));

        if(($findText = $request->input('find_text', '')) != '') {
            $adverts->where('name', 'like', '%' . $findText . '%');
            $adverts->where('content', 'like', '%' . $findText . '%');
            $adverts->where('main_phrase', 'like', '%' . $findText . '%');
        }


        $adverts = $adverts->with('heading')->with('properties')->with('context')->with('medias')->get();
        $advertsArray = $adverts->toArray();

        /*foreach($adverts as $advert) {
            $advertArray = $advert->toArray();

            $properties = [];
            foreach($advertArray['properties'] as $property) {
                $properties[$property['property_id']] = $property['value'];
            }
            $advertArray['properties'] = $properties;
            // -gates
            $properties = [];
            foreach($advert->heading->properties()->get()->toArray() as $property) {
                $value = array_key_exists($property['id'], $advertArray['properties']) ? 
                    $advertArray['properties'][$property['id']] : $property['default'];
                
                $properties[$property['name']] = $value;
            }
            $advertArray['properties'] = $properties;

            $advertsArray[] = $advertArray;
        }*/
        

        return response([
            'success' => true,
            'adverts' => $advertsArray,
        ]);
    }

    public function add(Request $request) {
    	if($request->has('owner_id') && $request->input('owner_id') != Auth::user()->id) {
    		if(Auth::user()->checkPolicy('advert_add_from_another')) {
    			try {
    				$user = User::find($request->input('owner_id'))->firstOrFail();
    			} catch(\Throwable $e) {
    				return response([
    					'success' => false,
    					'message' => 'user_not_found',
    				]);
    			}
    		} else {
    			return response([
					'success' => false,
					'message' => 'access_denied',
				]);
    		}
    	} else {
    		$user = Auth::user();
    	}

        $validator = Validator::make($request->all(), [
          'name' => 'required|max:255',
          'content' => 'required',
          'phone' => 'required|in:' . $user->phones()
                ->where('verify', true)
                ->where('blocked', false)
                ->get()->implode('phone', ','),
          'main_phrase' => 'required|max:255',
          'city_id' => 'required|in:' . City::where('active', true)->get()->implode('id', ','),
          'heading_id' => 'required|in:' . Heading::where('active', true)->get()->implode('id', ','),
        ]);

        if($validator->fails()) {
            return response([
                'success' => false,
                'messages' => $validator->messages(),
            ]);
        } else {
            $advert = new Advert();
            $advert->name = $request->input('name');
            $advert->owner_id = $user->id;
            $advert->heading_id = $request->input('heading_id');
            $advert->content = $request->input('content');
            $advert->main_phrase = $request->input('main_phrase');
            $advert->city_id = $request->input('city_id');
            $advert->phone_id = Phone::where('phone', $request->input('phone'))->first()->id;
            try {
                $advert->save();

                if($request->has('properties')) {
                    foreach($properties = Property::whereIn('name', array_keys($request->input('properties', [])))->get() as $property) {
                        $pivotData = ['value' => $request->input('properties', [])[$property->name]];
                        if($advert->properties->contains($property)) {
                            $advert->properties()->updateExistingPivot($property->id, $pivotData);
                        } else {
                            $advert->properties()->attach($property->id, $pivotData);
                        }
                    }
                }

                $advertArray = $advert->toArray();
                $advertArray['properties'] = $advert->properties()->get()->toArray();

                return response([
                    'success' => true,
                    'advert' => $advertArray,
                ]);
            } catch(\Throwable $e) {
                return response([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return response([
            'success' => true,
        ]);
    }

    public function delete(Request $request) {

    }

    public function extend(Request $request) {

    }

    public function update(Request $request) {

    }
}
