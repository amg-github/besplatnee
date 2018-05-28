<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AjaxController extends Controller
{
	public function __construct() {

	}

    public function advert_follow(Request $request) {
        \Besplatnee::adverts()->addToFavorite($request->input('id'));

        return $this->success();
    }

    public function advert_hide(Request $request) {
        \Besplatnee::adverts()->hideForMe($request->input('id'));

        return $this->success();
    }

    public function advert_remove(Request $request) {

        return $this->success();
    }

    public function advert_commenting(Request $request) {
        \Besplatnee::adverts()->commenting($request->input('id'), $request->input('value'));

        return $this->success();
    }

    public function advert_query_remove(Request $request) {
        $id = $request->id;

        $query = \App\AdvertSearchQuery::find($id);

        $query->fill([
            'active'    =>  0,
        ])->save();

        return $this->success();
    }

    public function advert_editfield(Request $request) {
        $allowfields = ['name', 'price', 'content', 'extend_content', 'comment', 'main_phrase'];

        if($request->input('field') == 'comment') {
            return $this->advert_commenting($request);
        } else {
            $advert = \Besplatnee::adverts()->get($request->input('id'));
            if(!$advert) {  
                return $this->failure(['advert' => 'not found']);
            }

            if(\Gate::denies('edit', $advert)) {
                return $this->failure(['auth' => 'access denied']);
            }

            $field = $request->input('field');
            $value = $request->input('value');

            if(!in_array($field, $allowfields)) {
                return $this->failure(['field' => 'is not allow']);
            }

            $advert->$field = $value;
            $advert->save();

            return $this->success();
        }
    }

    public function banner_click(Request $request) {
        $banner = \Besplatnee::banners()->get($request->input('id'));

        if($banner) {
            $banner->clickcount++;
            $banner->save();
            return $this->success();
        } else {
            return $this->failure();
        }
    }

    public function banner_hide(Request $request) {
        \Besplatnee::banners()->hideForMe($request->input('id'));

        return $this->success();
    }

    public function banner_show(Request $request) {
        \Besplatnee::banners()->showForMe($request->input('id'));

        return $this->success();
    }

    public function property_list(Request $request) {
        $heading_id = $request->input('heading_id', 0);

        $properties = \Besplatnee::headings()->getProperties($heading_id);

        return $this->success($properties);
    }

    public function success($data = []) {
        return [
            'success' => true,
            'data' => $data,
            'errors' => [],
        ];
    }

    public function failure($errors = []) {
        return [
            'success' => false,
            'data' => [],
            'errors' => $errors,
        ];
    }

    public function advert_vip_accessibility(Request $request) {
        $heading    = $request->heading;
        $city       = $request->city ? $request->city : \Config::get('area')->id;

        $data   =   \DB::table('adverts')
            ->leftJoin('advert_city', 'adverts.id', '=', 'advert_city.advert_id')
            ->where('adverts.active', 1)
            ->whereNull('adverts.deleted_at')
            ->where('adverts.heading_id', $heading)
            ->where('advert_city.city_id', $city)
            ->whereNotNull('adverts.vip')
            ->pluck('adverts.vip');

        return $this->success($data->toArray());
    }

    public function location_check_entry(Request $request) {
        $long   =   $request->long;
        $lat    =   $request->lat;

        $range 	=	10;

        $range_long = $long + $range;
        $range_lat 	= $lat + $range;

        $data   =   \DB::table('cities')
            ->leftJoin('area_names', 'cities.name_key', '=', 'area_names.key')
            ->where('cities.northeast_latitude', '>=', $lat)
            ->where('cities.northeast_longitude', '>=', $long)
            ->where('cities.southwest_latitude', '<=', $lat)
            ->where('cities.southwest_longitude', '<=', $long)
            ->select('cities.id', 'area_names.nominative_local', 'area_names.nominative_international', 'cities.northeast_latitude',
        				'cities.northeast_longitude', 'cities.southwest_latitude', 'cities.southwest_longitude', 'cities.country_id')
            ->distinct()
            ->get();

        // foreach ($data as &$region) {
        // 	if ($region->northeast_latitude < $range_lat &&
        // 		$region->northeast_longitude < $range_long &&
        // 		$region->southwest_latitude > $range_lat &&
        // 		$region->southwest_longitude > $range_long)
        // 	{
        // 		$region->city = '123';
        // 	}
        // } 

        return $this->success($data->toArray());
    }

    public function country_getcities(Request $request) {
        $country_id = $request->input('id', 0);
        $firstletter = $request->input('firstletter', 'А');

        $data = \DB::table('cities')
            ->leftJoin('area_names', 'cities.name_key', '=', 'area_names.key')
            ->where('cities.active', 1)
            ->where('cities.country_id', $country_id)
            ->where('area_names.nominative_local', 'like', $firstletter . '%')
            ->select('cities.id', 'area_names.nominative_local', 'area_names.nominative_international')
            ->orderBy('area_names.nominative_local')
            ->distinct()
            ->get();

        return $this->success($data->toArray());
    }



    public function country_getfirstletters(Request $request) {
        $country_id = $request->input('id', 0);
        $lang = $request->input('lang', 'ru');

        if(!Cache::has('countries.firstletters.' . $country_id . '_' . $lang)) {
            $data = \DB::table('cities')
                ->leftJoin('area_names', 'cities.name_key', '=', 'area_names.key')
                ->where('cities.active', 1)
                ->where('cities.country_id', $country_id)
                ->where('area_names.language', $lang)
                ->select(\DB::raw('LEFT(area_names.nominative_local, 1) as firstletter'))
                ->orderBy('firstletter')
                ->distinct()
                ->get();

            Cache::forever('countries.firstletters.' . $country_id . '_' . $lang, $data->toArray());
        }

        return $this->success(Cache::get('countries.firstletters.' . $country_id . '_' . $lang));
    }

    public function country_getregions(Request $request) {
        $country_id = $request->input('id', 0);
        $lang = $request->input('lang', 'ru');

        $data = \DB::table('regions')
            ->leftJoin('area_names', 'regions.name_key', '=', 'area_names.key')
            ->where('regions.country_id', $country_id)
            ->where('area_names.language', $lang)
            ->select('regions.id', 'area_names.nominative_local', 'area_names.nominative_international')
            ->orderBy('area_names.nominative_local')
            ->distinct()
            ->get();

        return $this->success($data->toArray());
    }

    public function country_getcitiesbyregion(Request $request) {
        $region_id = $request->input('id', 0);
        $lang = $request->input('lang', 'ru');

        $data = \DB::table('cities')
            ->leftJoin('area_names', 'cities.name_key', '=', 'area_names.key')
            ->where('cities.region_id', $region_id)
            ->where('area_names.language', $lang)
            ->select('cities.id', 'area_names.nominative_local', 'area_names.nominative_international')
            ->orderBy('area_names.nominative_local')
            ->distinct()
            ->get();

        return $this->success($data->toArray());
    }

    public function geoobjects_getbyparentid(Request $request) {
        $parent_id = $request->parent_id;

        $data = \App\GeoObject::where('parent_id', $parent_id)
                ->get();

        return $this->success($data->toArray());
    }

    public function language_set(Request $request) {
        $request->session()->put('settings.language_id', $request->input('id', 1));

        return $this->success(['l' => $request->session()->get('settings.language_id')]);
    }

    public function headings_getproperties(Request $request) {
        $heading = $request->input('id', 0);
        $properties = \Besplatnee::headings()->getProperties($heading);

        $data = $properties->toArray();
        foreach($properties as $i => $property) {
            $data[$i]['parent_name'] = \App\Property::where('id', $property->parent_id)->value('name');
            $data[$i]['title'] = __($property->title);
            $data[$i]['values'] = \Besplatnee::headings()->propertyMakeValues($property);
            $data[$i]['description'] = __($property->description);
        }

        return $this->success($data);
    }

    public function admin_get_access_regions(Request $request) {
        $country_id = $request->input('id', null);

        $data = [];
        foreach(\Auth::user()->getAccessRegions($country_id) as $region) {
            $data[] = ['id' => $region->id, 'nominative_local' => $region->getName()];
        }

        return $this->success($data);
    }

    public function admin_get_access_cities(Request $request) {
        $region_id = $request->input('id', null);
        
        $data = [];
        foreach(\Auth::user()->getAccessCities($region_id) as $city) {
            $data[] = ['id' => $city->id, 'nominative_local' => $city->getName()];
        }

        return $this->success($data);
    }
}
