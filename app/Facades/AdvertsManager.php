<?php
namespace App\Facades;

use Illuminate\Support\Facades\Auth;
use App\Advert;
use App\Context;
use App\Heading;
use App\City;
use App\Property;
use App\AdvertProperty;
use Illuminate\Support\Str;
use App\Settings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class AdvertsManager extends ModelsManager 
{
	public $model = Advert::class;
	public $fullmode = false;

	public function enableFullMode() {
		$this->fullmode = true;
	}

	public function disableFullMode() {
		$this->fullmode = false;
	}

	public function setData($advert, $data) {
		$data = array_merge($this->getEmpty(), $data);

		$data['price'] = floatval(str_replace(' ', '', str_replace(',', '.', $data['price'])));

		if(!$data['type']) { 
			$data['type'] = 4;
		}

		$advert->fill($data);

		$advert->setOwner($data['owner_id']);
		$advert->definedLocation();

		if(isset($data['created_at'])) {
			$advert->created_at = $data['created_at'];
			$advert->fakeupdated_at = $data['created_at'];

			$advert->save();
		} else {
			$advert->save();
			$advert->fakeupdated_at = $advert->created_at;
			$advert->save();
		}

		if(isset($data['city_ids'])) { 
			$advert->setCities($data['city_ids']);
		}

		if(isset($data['region_ids'])) { 
			$advert->setRegions($data['region_ids']);
		}

		if(isset($data['country_ids'])) { 
			$advert->setCountries($data['country_ids']);
		}

		if(isset($data['properties'])) { 
			$advert->setProperties($data['properties']);
		}

		if(isset($data['photos'])) { 
			$advert->setPhotos($data['photos']); 
			$advert->generateThumbs();
		}

		if(isset($data['videos'])) { 
			$advert->setVideos($data['videos']);
		}

		if(isset($data['vip']) && $data['vip']) {

			$bill = [
				'type'			=>	'vip',
				'deleted_at'	=>	\Carbon\Carbon::now()->addDays(env('VIP_DAYS_TO_EXPIRE')),
				'change'		=>	true,
			];

			$advert->pickup($bill);
		}

		if(isset($data['bill'])) { 
			$advert->pickup($data['bill']);
		}

		if(isset($data['bills'])) { 
			foreach ($data['bills'] as $bill) {
				$advert->pickup($bill);
			}
		}

		return $advert;
	}

	public function validate($data, $isValidate = false) {
		$rules = [
            'name' => 'required|max:' . Settings::getOption('adverts.name.size', 40),
            'content' => 'required|max:' . Settings::getOption('adverts.content.size', 300),
            'main_phrase' => 'max:' . Settings::getOption('adverts.main_phrase.size', 50),
            'city_ids.*' => 'required_if:duplicate_in_all_cities,' . Advert::DO_NOT_DUPLICATE_TO_ALL_CITIES . '|exists:cities,id',
            'region_ids.*' => 'nullable|exists:regions,id',
            'country_ids.*' => 'nullable|exists:countries,id',
            'heading_id' => 'required|exists:headings,id',
            'price' => 'nullable|price',
            'currency_id' => 'nullable|exists:currencies,id',
            'extend_content' => 'nullable|max:' . Settings::getOption('adverts.extend_content.size', 10000),
            'show_phone' => 'required|in:0,1',
            'contacts' => 'nullable|max:' . Settings::getOption('adverts.contacts.size', 2000),
            'address' => 'nullable',
            'send_to_print' => 'nullable|in:0,1',
            'site_url' => 'nullable|extended_url',
            'status' => 'nullable|exists:bill_statuses,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'vip' => 'nullable|numeric|in:1,2,3,4,5',

            'bill.advert_template_id' => 'nullable|exists_or_in:advert_templates,id,0',
            'bill.status' => 'required_with_unless:bill.advert_template_id,0|in:0,1,2,3,4,5,6',
            'bill.deleted_at' => 'required_with_unless:bill.advert_template_id,0|date_format:"Y-m-d H:i:s"',
            'bill.price' => 'required_with_unless:bill.advert_template_id,0|price',

            'user.phone' => 'required|phone',
            'user.fullname' => 'nullable',
            'user.email' => 'nullable|email',

            'photos.*' => [
            	'regex:/^([a-zA-Z0-9\/]+)\.(png|gif|jpg|jpeg)$/',
            ],
            'videos.*' => [
            	'regex:/^([a-zA-Z0-9\/]+)\.(mp4|3gp|avi|flv|wmv|mov|webm)|([0-9a-zA-Z-_]{11})$/',
            ],
        ];

		$heading_id = isset($data['heading_id']) ? $data['heading_id'] : 0;

		foreach(\Besplatnee::headings()->getProperties($heading_id) as $property) {
			$rules['properties.' . $property->name] = $property->rules();
		}

		$validator = Validator::make($data, $rules);

        return $isValidate ? ($validator->fails() ?? $validator->messages()) : $validator;
	}

	public function get($id, $removed = false) {
		$advert = $this->model::with('heading')
			->with('context')
			->with('medias')
			->with('properties')
			->with('cities')
			->with('megaAdvert');

		if($removed && $this->hasSoftDelete()) {
			$advert->withTrashed();
		}

		return $advert->find($id);
	}

	public function getAsArray($id) {
		$advert = $this->get($id);
		if($advert) {
			$advertData = $advert->toArray();

			$advertData['properties'] = $advert->properties()->pluck('value', 'name');
			$advertData['city_ids'] = $advert->cities()->pluck('id');
			$advertData['photos'] = $advert->medias()->where('type', 'image')->pluck('path');
			$advertData['videos'] = $advert->medias()->where('type', 'video')->pluck('path');
			$advertData['mega'] = $advert->megaAdvert ? $advert->megaAdvert->toArray() : $this->getEmptyMegaAdvert();

			return $advertData;
		} else {
			return null;
		}
	}

	public function activate($id, $value = true) {
		$advert = $this->get($id);
		if($advert) {
			$advert->active = $value;
			$advert->save();
		}
	}

	public function blocked($id, $value = true) {
		$advert = $this->get($id);
		if($advert) {
			$advert->blocked = $value;
			$advert->save();
		}
	}

	public function approved($id, $value = true) {
		$advert = $this->get($id);
		if($advert) {
			$advert->approved = $value;
			$advert->save();
		}
	}

	public function addToFavorite($id) {
		if(\Auth::check()) {
			\Auth::user()->customAdverts()->sync([$id => ['favorite' => true]], false);
		}
	}

	public function hideForMe($id) {
		if(\Auth::check()) {
			\Auth::user()->customAdverts()->sync([$id => ['hidden' => true]], false);
		}
	}

	public function removeRequest($id) {

	}

	public function commenting($id, $comment) {
		if(\Auth::check()) {
			\Auth::user()->customAdverts()->sync([$id => ['comment' => $comment]], false);
		}
	}

	public function getList($properties) {
		$properties = array_merge([
			'limit' => 0,
			'offset' => 0,
			'maxLimit' => 0,
			'paginate' => false,
			'actived' => true,
			'blocked' => false,
			'deleted' => false,
			'approved' => null,
			'headings' => [],
			'cities' => [],
			'regions' => [],
			'countries' => [],
			'return' => 'model', // model, array
			'sortRaw' => [],
			'sort' => ['updated_at' => 'DESC'],
			'not-ids' => [],
			'level' => 0,
			'search' => '',
			'searchfields' => ['name','content'],
			'filters' => [],
			'pickuped' => null,
			'pickup-statuses' => [],
			'onlyImages' => false,
			'published' => null,
			'in_all_cities' => null,
			'random'		=> false,
			'in_all_cities_for_countries' => null,
			'in_all_cities_for_regions' => null,
		], $properties);

		if($properties['maxLimit'] > 0 && $properties['limit'] > $properties['maxLimit']) {
			$properties['limit'] = $properties['maxLimit'];
		}

		// $requestCacheKey = 'advertmanager.getlist'.md5('advertmanager.getlist'.@json_encode($properties));
		// if(\Cache::has($requestCacheKey)) {
		// 	return \Cache::get($requestCacheKey);
		// }

		$adverts = (new \App\Advert)->newQuery();

		if($properties['actived'] !== null) {
			$adverts->where('active', $properties['actived']);
		}

		if($properties['blocked'] !== null) {
			$adverts->where('blocked', $properties['blocked']);
		}

		if($properties['approved'] !== null) {
			$adverts->where('approved', $properties['approved']);
		}

		$isEmptyHeadings = count($properties['headings']) == 0;
		$isNullHeading = count($properties['headings']) == 1 && $properties['headings'][0] == null;
		$isZeroHeading = count($properties['headings']) == 1 && $properties['headings'][0] == 0;

		if(!$isEmptyHeadings && !$isNullHeading && !$isZeroHeading) {
			//$properties['headings'] = \DB::table('headings')->whereNull('parent_id')->pluck('id')->all();

			$parents = $properties['headings'];
			for($i = 0; $i < $properties['level']; $i++) {
				if(count($parents) == 0) { break ; }

				$childrens = \DB::table('headings')->whereIn('parent_id', $parents)->pluck('id')->all();

				$properties['headings'] = array_merge($properties['headings'], $childrens);
				$parents = $childrens;
			}

			if(count($properties['headings']) == 1) {
				$adverts = $adverts->where('heading_id', $properties['headings'][0]);
			} else {
				$adverts = $adverts->whereIn('heading_id', $properties['headings']);
			}
		}

		if($properties['pickuped'] !== null) {
			if($properties['pickuped']) {
				if(count($properties['pickup-statuses']) > 0) {
					$adverts->whereHas('allBills', function ($query) use ($properties) {
						$query->whereIn('status', $properties['pickup-statuses']);
					});
				} else {
					$adverts->has('allBills');
				}
			} else {
				$adverts->doesntHave('allBills');
			}
		}

		if($properties['deleted'] === true) {
			$adverts->onlyTrashed();
		} elseif($properties['deleted'] === null) {
			$adverts->withTrashed();
		}

		if($properties['published'] === true) {
			$adverts->where('unpublished_on', '>', date('Y-m-d H:i:s'));
		} elseif($properties['published'] === false) {
			$adverts->where('unpublished_on', '<=', date('Y-m-d H:i:s'));
		}

		if(count($properties['not-ids']) > 0) {
			$adverts->whereNotIn('id', $properties['not-ids']);
		}

		/* filters */
		if(count($properties['filters']) > 0) {
			foreach($properties['filters'] as $name => $value) {
				$adverts->whereHas('properties', function ($query) use ($name, $value) {

					$query->where(function ($query) use ($name) {
						$query->where('id', $name)->orWhere('name', $name);
					});

					$query->where('advert_property.value', $value);

				});
			}
		}

		if(isset($properties['onlyImages']) && $properties['onlyImages']) {
			$adverts->whereHas('medias', function ($q) {
				$q->where('type', 'image');
			});
		}

		$searchQuery = trim($properties['search']);
		if($searchQuery != '') {
			$adverts->where(function ($q) use ($searchQuery, $properties) {
				foreach($properties['searchfields'] as $field) {
					switch($field) {
						case 'id':
								$q->orWhere('id', $searchQuery);
							break;
						case 'phone':
								$userIdsByPhone = \App\User::where('phone', 'like', "%{$searchQuery}%")->pluck('id');
								if($userIdsByPhone->count() > 0) {
									$q->orWhereIn('owner_id', $userIdsByPhone);
								}
							break;
						default:
							$q->orWhere($field, 'like', "%{$searchQuery}%");
					}
				}
			});
		}

		//$idsInGeoObjects = [];

		// echo 'cities';
		// dump($properties['cities']);
		// echo 'regions';
		// dump($properties['regions']);
		// echo 'countries';
		// dump($properties['countries']);

		// if(count($properties['cities']) > 0) {
		// 	$_idsInGeoObjects = \DB::table('advert_city')->whereIn('city_id', $properties['cities'])->select('advert_id')->pluck('advert_id');
		// 	dump($_idsInGeoObjects);
		// 	$idsInGeoObjects = $_idsInGeoObjects;
		// }

		// if(count($properties['regions']) > 0) {
		// 	$idsInGeoObjects = $idsInGeoObjects->merge(\DB::table('advert_region')->whereIn('region_id', $properties['regions'])->select('region_id')->pluck('advert_id'));
		// 	dump($_idsInGeoObjects);
		// 	$idsInGeoObjects = $idsInGeoObjects->merge($_idsInGeoObjects);
		// }

		// if(count($properties['countries']) > 0) {
		// 	$idsInGeoObjects = $idsInGeoObjects->merge(\DB::table('advert_country')->whereIn('country_id', $properties['countries'])->select('advert_id')->pluck('advert_id'));
		// 	dump($_idsInGeoObjects);
		// 	$idsInGeoObjects = $idsInGeoObjects->merge($_idsInGeoObjects);
		// }

		// dd('end');
		// return ;

		// if($idsInGeoObjects->count() > 0) {
		// 	$adverts->whereIn('id', $idsInGeoObjects);
		// } elseif($properties['in_all_cities'] !== null) {
		//  	$adverts->where('duplicate_in_all_cities', $properties['in_all_cities']);
		// }

		if(count($properties['cities']) > 0 || count($properties['regions']) > 0 || count($properties['countries']) > 0) {
			$adverts->where(function ($query) use ($properties) {
				$query->where(function ($query) use ($properties) {
					if(count($properties['cities']) > 0) {
						// $query->whereHas('cities', function ($query) use ($properties) {
						// 	if(count($properties['cities']) > 1) {
						// 		$query->whereIn('id', $properties['cities']);
						// 	} else {
						// 		$query->where('id', $properties['cities'][0]);
						// 	}
						// });

						$query->whereExists(function ($query) use($properties) {
							$query->select(\DB::raw(1))
								->from('advert_city')
								->whereRaw('`advert_city`.`advert_id` = `adverts`.`id`');

							if(count($properties['cities']) > 1) {
								$query->whereRaw('`advert_city`.`city_id` in(' . implode(',', $properties['cities']) . ')');
							} else {
								$query->whereRaw('`advert_city`.`city_id` = ' . $properties['cities'][0]);
							}
						});
					}

					// if(count($properties['regions']) > 0) {
					// 	$query->orWhereHas('regions', function ($query) use ($properties) {
					// 		$query->whereIn('id', $properties['regions']);
					// 	});
					// }

					// if(count($properties['countries']) > 0) {
					// 	$query->orWhereHas('countries', function ($query) use ($properties) {
					// 		$query->whereIn('id', $properties['countries']);
					// 	});
					// }
				});

				if($properties['in_all_cities'] !== null) {
					$query->where('duplicate_in_all_cities', $properties['in_all_cities']);
				} else {
					$query->orWhere('duplicate_in_all_cities', true);
				}

				/*$cities = \App\City::whereIn('id', $properties['cities'])->get(['region_id', 'country_id']);

				$regions = $cities->pluck('region_id');
				$countries = $cities->pluck('country_id');

				$query->orWhereHas('regions', function ($query) use ($regions) {
					$query->whereIn('id', $regions);
				});

				$query->orWhereHas('countries', function ($query) use ($countries) {
					$query->whereIn('id', $countries);
				});*/
			});
		} else {
			if($properties['in_all_cities'] !== null) {
				$adverts->where('duplicate_in_all_cities', $properties['in_all_cities']);
			}
		}

		if($properties['maxLimit'] > 0) {
			$adverts->take($properties['maxLimit']);
		}

		 if(!empty($properties['sortRaw'])) {
		 	foreach($properties['sortRaw'] as $stroke) {
		 		$adverts->orderByRaw($stroke);
		 	}
		 }


		 foreach($properties['sort'] as $sortby => $sortdir) {
		 	$adverts->orderBy($sortby, $sortdir);
		 }

		 if($properties['random'] == true) {
		 	$adverts->inRandomOrder();
		 }

		//$advertIds = $adverts->pluck('id');

		//$adverts = \App\Advert::whereIn('id', $advertIds);

		$adverts->with('heading')
			->with('owner')
			->with('medias')
			->with('properties')
			->with('cities')
			->with('bills')
			->with('bills.template');

		 // print_r( $adverts->getBindings() );
		 // dd($adverts->toSql());

		if($properties['paginate']) {
			//$adverts = $this->_customPaginate($adverts, $properties['limit'], count($advertIds));
			$adverts = $adverts->paginate($properties['limit']);
		} else {
			if($properties['limit'] > 0) {
				$adverts->take($properties['limit'])->skip($properties['offset']);
			}

			$adverts = $adverts->get();
		}

		switch($properties['return']) {
			case 'model': break;
			case 'array': 
					$data = $adverts->toArray();
					$data['properties'] = $adverts->properties()->pluck('value', 'name');
					$data['medias'] = [];
					foreach($adverts->medias as $media) {
						if(!array_key_exists($media['type'], $data['medias'])) {
							$data['medias'][$media['type']] = [];
						}

						$data['medias'][$media['type']][] = $media->toArray();
					}

					$data['cities'] = $adverts->cities->toArray();
					$data['heading'] = $adverts->heading->toArray();

					$adverts = $data;
				break;
			default: break;
		}

		// \Cache::add($requestCacheKey, $adverts, 10);

		return $adverts;
	}

	public function getListForCity($cityId) {
		return Advert::leftJoin('advert_city', 'advert_city.advert_id', '=', 'adverts.id')
			->where('city_id', $cityId)
			->where('active', true)
			->where('blocked', false)
			->with(['heading','owner','medias','properties','cities','bills','bills.template'])
			->orderByRaw(\DB::raw('-vip desc'))
			->orderBy('accented', 'desc')
			->orderBy('type', 'asc')
			->orderBy('fakeupdated_at', 'desc')
			->paginate(50);
	}

	public function getListForCityByHeading($cityId, $headingId) {
		return Advert::leftJoin('advert_city', 'advert_city.advert_id', '=', 'adverts.id')
			->where('heading_id', $headingId)
			->where('city_id', $cityId)
			->where('active', true)
			->where('blocked', false)
			->with(['heading','owner','medias','properties','cities','bills','bills.template'])
			->orderByRaw(\DB::raw('-vip desc'))
			->orderBy('accented', 'desc')
			->orderBy('type', 'asc')
			->orderBy('fakeupdated_at', 'desc')
			->paginate(50);
	}

	public function _customPaginate($builder, $perPage, $total = null, $columns = ['*'], $pageName = 'page', $page = null) {
		$page = $page ?: Paginator::resolveCurrentPage($pageName);

		$total = $total ?: $builder->toBase()->getCountForPagination();
        $results = $total
            ? $builder->forPage($page, $perPage)->get($columns)
            : $builder->getModel()->newCollection();

        return Container::getInstance()->makeWith(LengthAwarePaginator::class, [
            'items' => $results, 'total' => $total, 'perPage' => $perPage, 'currentPage' => $page, 'options' => [
	            'path' => Paginator::resolveCurrentPath(),
	            'pageName' => $pageName,
	        ],
        ]);
	}

	public function _customEmptyPaginate($perPage, $total = null, $columns = ['*'], $pageName = 'page', $page = null) {
		$page = $page ?: Paginator::resolveCurrentPage($pageName);

		$total = 0;
        $results = collect();

        return Container::getInstance()->makeWith(LengthAwarePaginator::class, [
            'items' => $results, 'total' => $total, 'perPage' => $perPage, 'currentPage' => $page, 'options' => [
	            'path' => Paginator::resolveCurrentPath(),
	            'pageName' => $pageName,
	        ],
        ]);
	}

	public function touch($adverts) {
		\App\Advert::whereIn('id', $adverts->pluck('id'))
			->update([
				'viewcount' => \DB::raw('viewcount + 1'),
			]);
	}

	public function getCategoryPath($id) {
		$path = [];
		$advert = \App\Advert::find($id);



		if(!$advert) {
			return $path;
		}



		if($advert->heading->parent) {
			$title = __($advert->heading->parent->name);
			$url = $advert->heading->parent->getUrl();
			$path[] = ['title' => $title, 'url' => $url];
		}
		

		$title = __($advert->heading->name);
		$url = $advert->heading->getUrl();
		$path[] = ['title' => $title, 'url' => $url];

		



		if($advert->properties->count() > 0) {
			$headingAliases = \App\HeadingAlias::where('heading_id', $advert->heading->id)->where('language', 'ru')->where(function ($q) use($advert) {
				$advertProperties = $advert->properties;
				foreach($advertProperties as $advertProperty) {
					$q->orWhere(function ($q) use($advertProperty) {
						$q->where('property_id', $advertProperty->id);
						$q->where('property_value', $advertProperty->pivot->value);
					});
				}
			})->get();

			foreach($headingAliases as $alias) {
				$path[] = ['title' => __($alias->getName()), 'url' => $alias->getUrl()];
			}

		}

		return $path;
	}
}