<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdvertController extends AdminController {

	public function groups() {
		return [
			
			$this->makeGroup('news', 'admin.adverts.news', true, [
				'approved' => false,
			]),

			$this->makeGroup('approved', 'admin.adverts.approved', false, [
				'approved' => true,
				'published' => true,
			]),

			$this->makeGroup('archive', 'admin.adverts.archive', false, [
				'published' => false,
			]),

			$this->makeGroup('pickup_and_vip', 'admin.adverts.pickup_and_vip', false, [
				'pickuped' => true,
			]),

			$this->makeGroup('removed', 'admin.adverts.removed', false, [
				'deleted' => true,
			]),

		];
	}

	public function filters() {
		$filterByHeading_values = [];
		foreach(\Besplatnee::headings()->_getTree(null, 0) as $heading) {
			$filterByHeading_values[] = [
				'value' => $heading['root']->id, 
				'isroot' => true,
				'title' => __($heading['root']->name),
				'active' => collect(request()->input('headings', []))->contains($heading['root']->id),
			];
			foreach($heading['childrens'] as $children) {
				$filterByHeading_values[] = [
					'value' => $children->id, 
					'isroot' => false,
					'title' => __($children->name),
					'active' => collect(request()->input('headings', []))->contains($children->id),
				];
			}
		}

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
				'title' => __('admin.adverts.by_area'),
				'default' => [],
				'multiple' => true,
				'type' => 'areapicker',
				'size' => $areaFilterConfig['contain_size'],
				'config' => $areaFilterConfig,
			],
			[
				'name' => 'headings',
				'title' => __('admin.adverts.by_heading'),
				'default' => [],
				'multiple' => true,
				'type' => 'select',
				'values' => $filterByHeading_values,
				'size' => 2,
			],
		];
	}

	public function columns() {
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
				'title' => __('admin.adverts.actions'),
				'width' => '8%',
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
					[
						'widget' => 'action',
						'action' => 'pickup',
						'icon' => \Besplatnee::forms()->icons()->image(asset('img/post-params/1.png')),
					],
				],
			],
			[
				'name' => 'content',
				'title' => __('admin.adverts.content'),
				'width' => '44%',
				'content' => [
					[
						'widget' => 'advert',
					],
				],
			],
			[
				'name' => 'extend',
				'title' => __('admin.adverts.extend_content'),
				'width' => '18%',
				'content' => [
					[
						'widget' => 'field',
						'field' => 'extend_content',
						'type' => 'editable',
					],
				],
			],
			[
				'name' => 'cities',
				'title' => __('admin.adverts.cities'),
				'width' => '1%',
				'content' => [
					[
						'widget' => 'relation',
						'relation' => 'cities',
						'view' => function ($city) {
							return $city->name;
						},
						'separator' => ', ',
					],
				],
			],
			[
				'name' => 'created',
				'title' => __('admin.adverts.created'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'field-extend',
						'field' => 'getCreatedAtForCurrentTimeZone',
						'type' => 'date',
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
						'content' => function ($advert) {
							return '<input type="checkbox" name="ids[]" value="' . $advert->id . '">';
						}
					],
				],
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
			[
				'action' => 'cloneAllByRegion',
				'title' => __('admin.adverts.clone-all-region'),
			],
			[
				'action' => 'cloneAllByCountry',
				'title' => __('admin.adverts.clone-all-country'),
			],
		];
	}

	public function list(Request $request, $model) {
		parent::list($request, $model);

		$filterWheres = $this->getFilterWheres();
		$country_id = $request->input('country_id');
		$region_id = $request->input('region_id');
		$city_id = $request->input('city_id');

		if(!empty($country_id) && $country_id != 'all') {
			if($country_id == 'duplicate') {
				$filterWheres['in_all_cities'] = true;
			} else {
				$filterWheres['in_all_cities'] = false;
				$filterWheres['countries'] = [$country_id];

				if(!empty($region_id) && $region_id != 'all') {
					if($region_id == 'duplicate') {
						
					} else {
						$filterWheres['regions'] = [$region_id];

						if(!empty($city_id) && $city_id != 'all') {
							if($city_id == 'duplicate') {
								
							} else {
								$filterWheres['cities'] = [$city_id];
							}
						} else {
							$filterWheres['cities'] = \Auth::user()->getAccessCities($region_id, $country_id, true)->toArray();
						}
					}
				} else {
					$filterWheres['regions'] = \Auth::user()->getAccessRegions($country_id, true)->toArray();
					$filterWheres['cities'] = \Auth::user()->getAccessCities(null, $country_id, true)->toArray();
				}
			}
		} else {
			if(!\Auth::user()->inAllCities()) {
				$filterWheres['countries'] = \Auth::user()->getAccessCountries(true)->toArray();
				$filterWheres['regions'] = \Auth::user()->getAccessRegions(null, true)->toArray();
				$filterWheres['cities'] = \Auth::user()->getAccessCities(null, null, true)->toArray();
				$filterWheres['in_all_cities'] = false;
			}
		}

		//dd($filterWheres);


        $adverts = \Besplatnee::adverts()->getList(array_merge([
        	'limit' => 50,
			'paginate' => true,
			'actived' => null,
			'blocked' => null,
			'deleted' => false,
			'approved' => null,
			'headings' => [],
			'cities' => [],
			'sort' => ['created_at' => 'DESC'],
			'level' => 999,
			'filters' => [],
        ], $this->getGroup()['wheres'], $filterWheres));

        $adverts->appends($request->query());

        return view('admin.list.items', [
            'version' => $this->version,
            'items' => $adverts,
            'filters' => $this->filters,
            'groups' => $this->groups,
            'model' => $model,
            'columns' => $this->columns,
            'actions' => $this->actions,
        ]);
	}

	public function edit(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$advert = \Besplatnee::adverts()->get($id, true);

		if(!$advert) { abort(404); }

		$bill = $advert->bills()->first();
		if($bill) {
			$bill = $bill->toArray();
		} else {
			$bill = [
				'id' => 0,
				'deleted_at' => null,
				'updated_at' => null,
				'status' => 1,
				'advert_template_id' => 0,
			];
		}

		$bills = $advert->allBills;

		if(empty($bill['deleted_at']) || empty($bill['updated_at'])) {
			$bill['period'] = 0;
		} else {
			$deleted_at = \Carbon\Carbon::parse($bill['deleted_at']);
			$updated_at = \Carbon\Carbon::parse($bill['updated_at']);
			$bill['period'] = $deleted_at->diffInWeeks($updated_at);

			if($bill['period'] < 0) { $bill['period'] = 0; }
		}

        $request->merge($advert->toArray());
        $request->merge([
            'photos' => $advert->medias()->where('type', 'image')->distinct()->pluck('path'),
            'videos' => $advert->medias()->where('type', 'video')->distinct()->pluck('path'),
            'city_ids' => $advert->cities()->pluck('id'),
            'region_ids' => $advert->regions()->pluck('id'),
            'country_ids' => $advert->countries()->pluck('id'),
            'user' => $advert->owner->toArray(),
            'user.fullname' => $advert->owner->fullname(),
            'visibile-phone' => !$advert->show_phone,
            'properties' => $advert->properties()->pluck('value', 'name'),
            'bill' => $bill,
            'bills' => $bills,
            'unpublished_on' => $advert->isArchive(),
        ]);

		return view('admin.edit.advert', [
            'version' => $this->version,
            'advert' => $advert,
            'model' => $model,
            'fields' => $this->fields,
            'bannerImage' => new \App\BannerImage,
            'errors' => $this->form_errors,
        ]);
	}

	public function remove(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$advert = \App\Advert::find($id);
        if(!$advert) { abort(404); }

        if(\Gate::denies('remove', $advert)) { abort(403); }

        $this->besplatnee->adverts()->_decreaseTotal($advert->geoObjects);

        $advert->delete();

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function create(Request $request, $model) {
		parent::create($request, $model);

		$bills = [];

		$request->merge([
            'bills' => $bills,
        ]);

        return view('admin.edit.advert', [
            'model' => $model,
            'version' => $this->version,
            'advert' => new \App\Advert,
            'errors' => $this->form_errors,
            'bannerImage' => new \App\BannerImage,
        ]);
	}

	public function save(Request $request, $model, $id) {

        $request->merge([
            'show_phone' => $request->input('visible-phone', 0) == 0 ? 1 : 0,
        ]);
        
		$data = $request->all();


        if(isset($data['id']) && intval($data['id']) > 0) {
            $advert = \Besplatnee::adverts()->get($id, true);
            if(\Gate::denies('edit', $advert)) { abort(403); }

            unset($data['creator_id']);
        } else {
            $advert = new \App\Advert;
        }

        if(\Gate::denies('give', $advert)) {
            unset($data['owner_id']);
        }

        if(\Gate::denies('approved', $advert)) {
            unset($data['active']);
            unset($data['blocked']);
            unset($data['approved']);
        }

        if(\Gate::denies('remove', $advert)) {
            unset($data['status']);
        }

        if(isset($data['unpublished_on'])) {
        	if($data['unpublished_on']) {
        		$data['unpublished_on'] = date('Y-m-d H:i:s');
        	} else {
        		$data['unpublished_on'] = null;
        	}
        }

        $validator = $this->besplatnee->adverts()->validate($data);
        $this->form_errors = $validator->messages();

        if(!$validator->fails()) {

        	$owner = \App\User::where('phone', $request->input('user.phone'))->first();
        	if(!$owner) { 
        		$userData = $request->input('user', []);
        		$userData['password'] = uniqid();
        		$owner = \Besplatnee::users()->add($userData);
        	}

        	if(!$owner) {
        		$this->form_errors->add('user.email', 'Этот e-mail привязан к другому номеру телефона');
        	}

        	$data['owner_id'] = $owner->id;

        	if (isset($data['bills'])) {
	        	foreach ($data['bills'] as $index => $bill) {

	        		if (isset($bill['mega']) && $bill['mega']) {

		    	    	$data['type'] = 3;
		    	    	$data['bills'][$index]['type'] = 'mega';
		    	    	$data['mega_text_top'] = $bill['mega_text_top'];
		    	    	$data['mega_text_bottom'] = $bill['mega_text_bottom'];

		    	    } elseif (isset($bill['advert_template_id']) && $bill['advert_template_id']) {
                        $data['type'] = 4;
                        $data['accented'] = 1;
                        $data['bills'][$index]['type'] = 'accent';

                    } elseif ($bill['type'] = "vip") {

			    		$data['type'] = 4;

		    	    }

		    	    if (isset($bill['advert_template_id'])) {
                        $data['template_id'] = $bill['advert_template_id'];
                    }

	        	}
        	}

            if(isset($data['id']) && intval($data['id']) > 0) {
                $advert = \Besplatnee::adverts()->update($data);

                if($advert) {
	                if($request->input('actions.deleted', 0)) {
	                	$advert->delete();
	                } else {
	                	$advert->restore();
	                }
	            }

            	return $this->edit($request, $model, $id);
            } else {
                $advert = \Besplatnee::adverts()->add($data);
                $id = $advert->id;

                return redirect()->route('admin.edit', ['model' => $model, 'id' => $id]);
            }


        } else {
            if(isset($data['id']) && intval($data['id']) > 0) {
                return $this->edit($request, $model, $id);
            } else {
                return $this->create($request, $model);
            }
        }
	}

	public function removeAll(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$adverts = \App\Advert::whereIn('id', $ids)->get();
	        if(!$adverts) { abort(404); }

	        foreach($adverts as $advert) {
	        	if(\Gate::denies('remove', $adverts)) { 
	        		$advert->delete();
	        	}
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function approvedAll(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$adverts = \App\Advert::whereIn('id', $ids)->get();
	        if(!$adverts) { abort(404); }

	        foreach($adverts as $advert) {
	        	$advert->approved = true;
	        	$advert->save();
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function publishedAll(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$adverts = \App\Advert::whereIn('id', $ids)->get();
	        if(!$adverts) { abort(404); }

	        foreach($adverts as $advert) {
	        	$advert->active = true;
	        	$advert->blocked = false;
	        	$advert->deleted_at = nul;
	        	$advert->save();
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function cloneAll(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$adverts = \App\Advert::whereIn('id', $ids)->get();
	        if(!$adverts) { abort(404); }

	        foreach($adverts as $advert) {
	        	$new_advert = $advert->replicate();

	        	$user_id = \Auth::user()->id;

	        	$def_phone = \App\Setting::getOption('phone_for_clone', '9397126315');

	        	$owner = \App\User::where('phone', '+7'.$def_phone)->first();
	        	if(!$owner) { 
	        		$userData = $request->input('user', []);
	        		$userData['phone'] = '+7'.$def_phone;
	        		$userData['password'] = uniqid();
	        		$owner = \Besplatnee::users()->add($userData);
	        	}

	        	$owner_id = $owner->id;

	        	$new_advert->fill([
	        		'owner_id'		=>	$owner_id,
	        		'creator_id'	=>	$user_id,
	        	]);

	        	$new_advert->push();

        		$new_advert->cities()->sync([\Config::get('area')->id]);
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function cloneAllByRegion(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$adverts = \App\Advert::whereIn('id', $ids)->get();
	        if(!$adverts) { abort(404); }

	        foreach($adverts as $advert) {
	        	$new_advert = $advert->replicate();

	        	$user_id = \Auth::user()->id;

	        	$def_phone = \App\Setting::getOption('phone_for_clone', '9397126315');

	        	$owner = \App\User::where('phone', '+7'.$def_phone)->first();
	        	if(!$owner) { 
	        		$userData = $request->input('user', []);
	        		$userData['phone'] = '+7'.$def_phone;
	        		$userData['password'] = uniqid();
	        		$owner = \Besplatnee::users()->add($userData);
	        	}

	        	$owner_id = $owner->id;

	        	$new_advert->fill([
	        		'owner_id'		=>	$owner_id,
	        		'creator_id'	=>	$user_id,
	        	]);

	        	$new_advert->push();

        		$new_advert->regions()->sync([\Config::get('area')->region->id]);
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function cloneAllByCountry(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$adverts = \App\Advert::whereIn('id', $ids)->get();
	        if(!$adverts) { abort(404); }

	        foreach($adverts as $advert) {
	        	$new_advert = $advert->replicate();

	        	$user_id = \Auth::user()->id;

	        	$def_phone = \App\Setting::getOption('phone_for_clone', '9397126315');

	        	$owner = \App\User::where('phone', '+7'.$def_phone)->first();
	        	if(!$owner) { 
	        		$userData = $request->input('user', []);
	        		$userData['phone'] = '+7'.$def_phone;
	        		$userData['password'] = uniqid();
	        		$owner = \Besplatnee::users()->add($userData);
	        	}

	        	$owner_id = $owner->id;

	        	$new_advert->fill([
	        		'owner_id'		=>	$owner_id,
	        		'creator_id'	=>	$user_id,
	        	]);

	        	$new_advert->push();

        		$new_advert->countries()->sync([\Config::get('area')->country->id]);
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

}