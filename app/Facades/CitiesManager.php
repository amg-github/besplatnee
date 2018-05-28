<?php 
namespace App\Facades;

use App\City;

class CitiesManager extends ModelsManager {
	const TYPE_NOMINATIVE_LOCAL         = 'nominative_local';
	const TYPE_GENITIVE_LOCAL           = 'genitive_local';
	const TYPE_ACCUSATIVE_LOCAL         = 'accusative_local';
	const TYPE_DATIVE_LOCAL             = 'dative_local';
	const TYPE_ERGATIVE_LOCAL           = 'ergative_local';
	const TYPE_NOMINATIVE_INTERNATIONAL = 'nominative_international';
	const TYPE_GENITIVE_INTERNATIONAL   = 'genitive_international';
	const TYPE_ACCUSATIVE_INTERNATIONAL = 'accusative_international';
	const TYPE_DATIVE_INTERNATIONAL     = 'dative_international';
	const TYPE_ERGATIVE_INTERNATIONAL   = 'ergative_international';

	public $model = City::class;

	public function setData($city, $data) {
		$city->fill($data);
		if(empty($city->allias)) {
			$city->allias = \Slug::make($city->name);
		}

		$city->save();

		return $city;
	}

	public function getAll() {
		return City::where('active', true)->orderBy('name');
	}

	public function getCountries() {
		$aliasTable = (new \App\AreaName)->getTable();
		$countryTable = (new \App\Country)->getTable();

		$ids = \DB::table($countryTable)
			->leftJoin($aliasTable, $countryTable . '.name_key', '=', $aliasTable . '.key')
			->orderBy($aliasTable . '.nominative_local')
			->select($countryTable . '.id')
			->where('language', \App::getLocale())
			->pluck('id');

		return \App\Country::orderBy(\DB::raw("FIELD(id," . $ids->implode(',') . ')'))->get();
	}

	public function getByCountryId($country_id) {
		$aliasTable = (new \App\AreaName)->getTable();
		$cityTable = (new \App\City)->getTable();

		$ids = \DB::table($cityTable)
			->leftJoin($aliasTable, $cityTable . '.name_key', '=', $aliasTable . '.key')
			->orderBy($aliasTable . '.nominative_local')
			->select($cityTable . '.id')
			->where($cityTable . '.active', true)
			->where('language', \App::getLocale())
			->pluck('id');

		return \App\City::where('active', true)->orderBy(\DB::raw("FIELD(id," . $ids->implode(',') . ')'))->get();
	}

	public function getByRegionId($region_id = 0) {
		/*$aliasTable = (new \App\AreaName)->getTable();
		$cityTable = (new \App\City)->getTable();*/

		return \App\City::leftJoin('area_names', function ($join) {
			$join->on('area_names.key', '=', 'cities.name_key');
		})
			->where('area_names.language', config('language')->code)
			->where('active', true)
			->where('region_id', $region_id)
			->orderBy('area_names.nominative_local')
			->get(['area_names.*', 'cities.*']);

		/*$ids = \DB::table($cityTable)
			->leftJoin($aliasTable, $cityTable . '.name_key', '=', $aliasTable . '.key')
			->orderBy($aliasTable . '.nominative_local')
			->where('active', true)
			->where('language', \App::getLocale())
			->select($cityTable . '.id');

		if($region_id > 0) {
			$ids = $ids->where($cityTable . '.region_id', $region_id);
		}
		
		$ids = $ids->pluck('id');

		return $region_id > 0 
			? \App\City::where('active', true)
				->where('region_id', $region_id)
				->orderBy(\DB::raw("FIELD(id," . $ids->implode(',') . ')'))
				->get()
			: \App\City::where('active', true)
				->orderBy(\DB::raw("FIELD(id," . $ids->implode(',') . ')'))
				->get();*/
	}

	public function getRegions($country_id = 0) {
		$aliasTable = (new \App\AreaName)->getTable();
		$regionTable = (new \App\Region)->getTable();

		$ids = \DB::table($regionTable)
			->leftJoin($aliasTable, $regionTable . '.name_key', '=', $aliasTable . '.key')
			->orderBy($aliasTable . '.nominative_local')
			->select($regionTable . '.id');

		if($country_id > 0) {
			$ids = $ids->where($regionTable . '.country_id', $country_id);
		}
		
		$ids = $ids->pluck('id');

		return $country_id > 0 
			? \App\Region::where('country_id', $country_id)
				->orderBy(\DB::raw("FIELD(id," . $ids->implode(',') . ')'))
				->get()
			: \App\Region::orderBy(\DB::raw("FIELD(id," . $ids->implode(',') . ')'))
				->get();
	}

	public function getTreeArray() {
		$data = [];
		foreach($this->getCountries() as $sheet) {
			$childs = [];
			$values = [];

			foreach($this->getByCountryId($sheet->id) as $city) {
				$childs[] = [
					'title' => $city->name,
					'value' => $city->id,
				];

				$values[] = $city->id;
			}

			$data[] = [
				'title' => $sheet->name,
				'value' => implode(',', $values),
				'childs' => $childs,
			];
		}

		return $data;
	}

	public function getList($properties) {

	}

	public function getCurrencies($country = null) {
		if($country === null) { $country = \Config::get('area')->country; }

		if(!is_object($country)) {
			$country = \App\Country::find($country);
		}

		$currencies = \App\Currency::where('is_international', true);

		if($country) {
			$currencies->orWhere('id', $country->currency);
		}

		return $currencies->get();
	}

	public function getCountriesForLanguage($lang = null) {
		if($lang == null) { $lang = config('language')->code; }

		return \App\Country::leftJoin('area_names', function ($join) {
			$join->on('area_names.key', '=', 'countries.name_key');
		})->where('area_names.language', $lang)->has('cities')->orderBy('countries.id')->get(['area_names.*', 'countries.*']);
	}

	public function getByAlias($alias = null) {
		return \App\City::leftJoin('area_names', function ($join) {
			$join->on('area_names.key', '=', 'cities.name_key');
		})
			->where('area_names.language', config('language')->code)
			->where('area_names.nominative_international', $alias)
			->where('active', true)
			->first(['area_names.*', 'cities.*']);
	}
}