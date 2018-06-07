<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Besplatnee;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Artisan;

class AdvertSearchQueriesController extends AdminController {

	public function groups() {
		return [
			
			$this->makeGroup('approved', 'admin.adverts.approved', true, [
				'approved' => true,
			]),

			$this->makeGroup('news', 'admin.adverts.news', false, [
				'approved' => false,
			]),

			$this->makeGroup('doubled', 'admin.adverts.doubled', false, [
				'doubled' => true,
			]),

		];
	}

	public function columns () {
		return [
			[
				'name' => 'status',
				'title' => __('admin.adverts.status'),
				'width' => '8%',
				'content' => [
					[
						'widget' => 'switcher',
						'subject' => 'approved',
						'on' => [
							'title' => '',
							'icon' => \Besplatnee::forms()->icons()->fontAwesome('check', 'green', '18px'),
							'font-size' => '18px',
							'color' => 'green',
							'alt' => __('admin.adverts.approved.check.title'),
						],
						'off' => [
							'title' => '',
							'icon' => \Besplatnee::forms()->icons()->fontAwesome('times', 'red', '18px'),
							'font-size' => '18px',
							'color' => 'red',
							'alt' => __('admin.adverts.approved.uncheck.title'),
						],
					],
				],
			],
			[
				'name' => 'actions',
				'title' => __('admin.adverts.query.actions'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'action',
						'action' => 'edit',
						'icon' => \Besplatnee::forms()->icons()->image(asset('img/post-params/5.png')),
					],
					[
						'widget' => 'action',
						'action' => 'remove',
						'icon' => \Besplatnee::forms()->icons()->image(asset('img/post-params/3.png')),
					],
				],
			],
            [
                'name' => 'query',
                'title' => __('admin.adverts.query.name'),
                'width' => '10%',
                'content' => [
					[
                        'widget' => 'field',
                        'field' => 'query',
                        'type' => 'text',
                    ],
				],
            ],
			[
				'name' => 'cities',
				'title' => __('admin.adverts.query.city'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'relation',
						'relation' => 'citiesNew',
						'view' => function ($city) {
							return $city->name;
						},
						'separator' => '<br>',
					],
				],
			],
			[
				'name' => 'follows',
				'title' => __('admin.adverts.query.follows'),
				'width' => '10%',
				'content' => [
					[
                        'widget' => 'field',
                        'field' => 'follows',
                        'type' => 'text',
                    ],
				],
			],
			[
				'name' => 'select',
				'title' => '
					<input id="advert-items-select-all" type="checkbox" onclick="advertItems_selectAll()">
					<script type="text/javascript">
						function advertItems_selectAll () {
							var state = $(\'#advert-items-select-all\').prop(\'checked\');
							$(\'.admin-model-list-item [name="ids[]"]\').prop(\'checked\', state);
						}
					</script>',
				'width' => '3%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($query) {
							return '<input type="checkbox" name="ids[]" value="'.$query->id.'">';
						}
					],
				],
			],
			
		];
	}

	public function filters () {
		$country_id = request()->input('country_id');
		$region_id = request()->input('region_id');
		$city_id = request()->input('city_id');

		if(in_array($country_id, ['', 'all', 'duplicate'])) {
			$country_id = null;
		}

		if(in_array($region_id, ['', 'all', 'duplicate'])) {
			$region_id = null;
		}

		if(in_array($city_id, ['', 'all', 'duplicate'])) {
			$city_id = null;
		}

		if(\Auth::user()->inAllCities()) {
			$areaFilterConfig = [
				'countries' => \Auth::user()->getAccessCountries(),
				'regions' => $country_id ? \Auth::user()->getAccessRegions($country_id) : collect(),
				'cities' => $region_id ? \Auth::user()->getAccessCities($region_id) : collect(),
				'country_show' => true,
				'region_show' => true,
				'city_show' => true,
				'item_size' => 4,
				'contain_size' => 5,
				'strict_find' => true,
			];
		} else {
			$accessCountries = \Auth::user()->getAccessCountries();
			$areaFilterConfig = [
				'countries' => $accessCountries,
				'country_show' => $accessCountries->count() > 1,
				'strict_find' => false,
			];

			if($areaFilterConfig['country_show']) {
				$areaFilterConfig['regions'] = $country_id ? \Auth::user()->getAccessRegions($country_id) : collect();
				$areaFilterConfig['cities'] = $region_id ? \Auth::user()->getAccessCities($region_id) : collect();
				$areaFilterConfig['region_show'] = true;
				$areaFilterConfig['city_show'] = true;
				$areaFilterConfig['item_size'] = 4;
				$areaFilterConfig['contain_size'] = 5;
			} else {
				$areaFilterConfig['regions'] = \Auth::user()->getAccessRegions();
				$areaFilterConfig['region_show'] = $areaFilterConfig['regions']->count() > 1;

				if($areaFilterConfig['region_show']) {
					$areaFilterConfig['cities'] = $region_id ? \Auth::user()->getAccessCities($region_id) : collect();
					$areaFilterConfig['city_show'] = true;
					$areaFilterConfig['item_size'] = 6;
					$areaFilterConfig['contain_size'] = 4;
				} else {
					$areaFilterConfig['cities'] = \Auth::user()->getAccessCities();
					$areaFilterConfig['city_show'] = $areaFilterConfig['cities']->count() > 1;

					if($areaFilterConfig['city_show']) {
						$areaFilterConfig['item_size'] = 12;
						$areaFilterConfig['contain_size'] = 2;
					} else {
						$areaFilterConfig['item_size'] = 0;
						$areaFilterConfig['contain_size'] = 0;
					}
				}
			}
		}

		return [
			[
				'name' => 'area',
				'title' => '',
				'default' => [],
				'multiple' => true,
				'type' => 'areapicker_query',
				'size' => $areaFilterConfig['contain_size'],
				'config' => $areaFilterConfig,
			],
			[
				'name' => 'name',
				'title' => 'Название',
				'type' => 'text',
				'size' => 2,
			],
		];
	}

	public function actions() {
		return [
			[
				'action' => 'approvedAll',
				'title' => __('admin.adverts.approved-all'),
			],
			[
				'action' => 'removeAll',
				'title' => __('admin.adverts.remove-all'),
			],
			[
				'action' => 'publishedAll',
				'title' => __('admin.adverts.published-all'),
			],
			[
				'action' => 'cloneAll',
				'title' => __('admin.adverts.clone-all'),
			],
			// [
			// 	'action' => 'cloneAllByRegion',
			// 	'title' => __('admin.adverts.clone-all-region'),
			// ],
			// [
			// 	'action' => 'cloneAllByCountry',
			// 	'title' => __('admin.adverts.clone-all-country'),
			// ],
		];
	}

	public function list(Request $request, $model) {
		parent::list($request, $model);

		$this->title('Поисковые запросы');

		$groups = [];

		array_walk($this->getGroup()['wheres'], function($value, $key) use (&$groups) {
			$groups[] = [$key, $value];
		});

		$queries = 

		$request->city_id ?

		\App\AdvertSearchQuery::
			where('active', '1')
			->where('query', 'like', '%'.$request->name.'%')
			->whereHas('citiesNew', function($q) use ($request) {
				$q->where('city_id', $request->city_id);
			})
			->where($groups)
			->orderBy('created_at', 'desc')
			->paginate(15)

		:

		\App\AdvertSearchQuery::
			where('active', '1')
			->where('query', 'like', '%'.$request->name.'%')
			->where($groups)
			->orderBy('created_at', 'desc')
			->paginate(15);

		
		return view('admin.list.queries', [
            'version' 	=> 	$this->version,
            'model' 	=> 	$model,
            'groups'	=>	$this->groups,
            'filters'	=>	$this->filters,
            'columns'	=>	$this->columns,
            'items'		=>	$queries,
            'actions' 	=> 	$this->actions,
        ]);
	}

	public function edit(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$query = \App\AdvertSearchQuery::find($id);

		if(!$query) { abort(404); }

		$request->merge([
            'approved' => $query->approved,
		    'city_ids' => $query->citiesNew()->pluck('id'),
		]);

		return view('admin.edit.query', [
            'version' => $this->version,
            'item' => $query,
            'model' => $model,
            'fields' => $this->fields,
            'errors' => $this->form_errors,
        ]);
	}

	public function save(Request $request, $model, $id) {

		$query = \App\AdvertSearchQuery::find($request->input('id'));

		// if(\Gate::denies('edit', $query)) { abort(403); }

		if(!$query) { abort(404); }

		$validator = Validator::make($request->all(), [
			'query'			=> 'required',
			'city_ids.*' 	=> 'required',
		]);

		if($validator->fails()) {
			$this->form_errors = $validator->messages();

			$this->complete_messages['danger'][] = 'Возникли ошибки при сохранении.';
            return $this->edit($request, $model, $id);
		}

		$city_ids = $request->input('city_ids');

		$advert_search_query_city = new \App\AdvertSearchQueryCity;

		$advert_search_query_city->where('advert_query_id', $query->id)->delete();

		foreach ($city_ids as $key => $city_id) {
			$advert_search_query_city->firstOrCreate([
                'advert_query_id'   =>  $query->id,
                'city_id'           =>  $city_id
            ]);
		}

		$query->fill($request->all());

		$query->save();

		$this->complete_messages['success'][] = 'Поисковой запрос успешно сохранен!';

		return redirect()->route('admin.edit', ['model' => $model, 'id' => $query->id]);
	}

	public function remove(Request $request, $model, $id) {
		$query = \App\AdvertSearchQuery::find($id);
        if(!$query) { abort(404); }

		if(\Gate::denies('remove', $query)) { abort(403); }

        $query->fill([
        	'active'	=>	0,
        ])->save();

        return redirect()->route('admin.list', ['model' => $model]);
	}



	public function removeAll(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$queries = \App\AdvertSearchQuery::whereIn('id', $ids)->get();
	        if(!$queries) { abort(404); }

	        foreach($queries as $query) {
	        	if(\Gate::denies('remove', $queries)) { 
	        		$query->fill([
	        			'active'	=>	0,
	        		])->save();
	        	}
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function approvedAll(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$queries = \App\AdvertSearchQuery::whereIn('id', $ids)->get();
	        if(!$queries) { abort(404); }

	        foreach($queries as $query) {
	        	$query->approved = true;
	        	$query->save();
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function cloneAll(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$queries = \App\AdvertSearchQuery::whereIn('id', $ids)->get();
	        if(!$queries) { abort(404); }

	        foreach($queries as $query) {

        		$new_query = $query->queryCity()->first();

        		$new_query->firstOrCreate([
	                'advert_query_id'   =>  $query->id,
	                'city_id'           =>  \Config::get('area')->id
	            ]);

        		$query->fill([
        			'doubled'	=> 1,
        		]);

        		$query->save();

	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

	// public function cloneAllByRegion(Request $request, $model, $id) {
	// 	$ids = $request->input('ids', []);

	// 	if(count($ids) > 0) {
	// 		$adverts = \App\Advert::whereIn('id', $ids)->get();
	//         if(!$adverts) { abort(404); }

	//         foreach($adverts as $advert) {
	//         	$new_advert = $advert->replicate();

	//         	$user_id = \Auth::user()->id;

	//         	$new_advert->fill([
	//         		'owner_id'		=>	$user_id,
	//         		'creator_id'	=>	$user_id,
	//         	]);

	//         	$new_advert->push();

 //        		$new_advert->regions()->sync([\Config::get('area')->region->id]);
	//         }
	//     }

 //        return redirect()->route('admin.list', ['model' => $model]);
	// }

	// public function cloneAllByCountry(Request $request, $model, $id) {
	// 	$ids = $request->input('ids', []);

	// 	if(count($ids) > 0) {
	// 		$adverts = \App\Advert::whereIn('id', $ids)->get();
	//         if(!$adverts) { abort(404); }

	//         foreach($adverts as $advert) {
	//         	$new_advert = $advert->replicate();

	//         	$user_id = \Auth::user()->id;

	//         	$new_advert->fill([
	//         		'owner_id'		=>	$user_id,
	//         		'creator_id'	=>	$user_id,
	//         	]);

	//         	$new_advert->push();

 //        		$new_advert->countries()->sync([\Config::get('area')->country->id]);
	//         }
	//     }

 //        return redirect()->route('admin.list', ['model' => $model]);
	// }
}