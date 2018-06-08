<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class AdvertController extends Controller
{
    protected $category;
    protected $cache_minutes = 10;
    protected $sortby;
    protected $sortdir;
    protected $adverts;
    public    $sorts = [];
    protected $filters = [];
    protected $defaultSorts = [
    	'sorts.fakeupdated_at.alias' => [
    		'name' => 'fakeupdated_at',
    		'caption' => 'sorts.fakeupdated_at',
    		'direction' => 'desc',
	    	'active' => false,
    	],
    	'sorts.price.alias' => [
    		'name' => 'price',
    		'caption' => 'sorts.price',
    		'direction' => 'asc',
	    	'active' => false,
    	],
    	'sorts.name.alias' => [
    		'name' => 'name',
    		'caption' => 'sorts.name',
    		'direction' => 'asc',
	    	'active' => false,
    	],
    ];

    public function getAdverts($category_id = null, Request $request = null) {
    	$this->buildSorts();

        $adverts = Cache::remember('adverts_city_'.\Config::get('area')->id.'_category_'.$category_id.'_page_'.$request->page, $this->cache_minutes, function () use ($category_id) {

            if($category_id == null) {
                return $this->besplatnee->adverts()->getListForCity(\Config::get('area')->id);
            } else {
                return $this->besplatnee->adverts()->getListForCityByHeading($category_id, \Config::get('area')->id);
            }

        });

        $this->besplatnee->adverts()->touch($adverts);
        return $adverts;
    }

    public function category(Request $request, $alias, $city, $sort) {
        $this->sortby = $sort;

        $this->category = $this->besplatnee->headings()->getByAlias($alias);

        if(!$this->category) { abort(404); }
        $this->filters = $this->besplatnee->headings()->getFiltersByAlias($alias);

        $this->adverts = $this->getAdverts($this->category->id, $request);

        $areaName = config('area')->country()->name . ', ' . config('area')->name;
        $this->breadcrumbs($areaName, route('city', ['city_alias' => config('area')->alias]), $areaName);

        foreach($this->besplatnee->headings()->getPathWithProperties($this->category, $this->filters) as $category) {
            $this->breadcrumbs(__($category['caption']), $category['url'], __($category['caption']));
        }

        $path = [];
        foreach($this->breadcrumbs() as $breadcrumb) {
            $path[] = $breadcrumb['title'];
        }

        $this->header(implode(', ', $path));
        $this->title(implode(', ', $path) . ' в ' . \Config::get('area')->dative_name);
        $this->description(implode(', ', $path));
        $this->keywords(\Str::lower(implode(', ', $path)));

        $this->parentFilterId = count($this->filters) > 0 ? array_keys($this->filters)[0] : null;
        $this->parentFilterValue = count($this->filters) > 0 ? $this->filters[$this->parentFilterId] : null;
        return view('advert.list', [
            'adverts' => $this->adverts,
            'version' => $this->version,
            'city' => \Config::get('area'),
            'category' => $this->category,
            'filters' => $this->filters,
            'parent_filter_id' => $this->parentFilterId,
            'parent_filter_value' => $this->parentFilterValue,
        ]);
    }

    public function index(Request $request, $city_alias) {
        $this->adverts = $this->getAdverts(null, $request);

        $areaName = config('area')->country()->name . ', ' . config('area')->name;
        $this->breadcrumbs($areaName, route('city', ['city_alias' => config('area')->alias]), $areaName);
        $this->breadcrumbs('Главная страница', '/', 'Главная страница');

        $path = [];

        $this->header('Ещё БЕСПЛАТНЕЕ - интернет газета бесплатных объявлений и рекламы' . ' в ' . \Config::get('area')->dative_name);
        $this->title('Ещё БЕСПЛАТНЕЕ - интернет газета бесплатных объявлений и рекламы' . ' в ' . \Config::get('area')->dative_name);
        $this->description('Ещё БЕСПЛАТНЕЕ - интернет газета бесплатных объявлений и рекламы' . ' в ' . \Config::get('area')->dative_name);
        $this->keywords(\Str::lower(implode(', ', $path)));

        return view('home', [
            'adverts' => $this->adverts,
            'version' => $this->version,
            'city' => \Config::get('area'),
            'category' => $this->category,
            'filters' => $this->filters,
        ]);
    }


    public function advert(Request $request, $alias_local, $city_local, $alias_international, $city_international, $id) {
        $advert = $this->besplatnee->adverts()->get($id);

        if (!$advert->alias_local) {
            $advert->makeUrl();
        }

        if(!$advert || $advert->getUrl() != request()->url()) {
            abort(404);
        }

        $this->version = 'b';
        $advertTitle = \Str::words($advert->content, 5, '') . ' в ' . \Config::get('area')->dative_name;
        $this->header($advert->name . ' в ' . \Config::get('area')->dative_name);
        $this->title($advertTitle);
        $this->description($advertTitle);

        $path = $this->besplatnee->adverts()->getCategoryPath($advert->id);

        foreach($path as $item) {
        	$this->breadcrumbs($item['title'], $item['url'], $item['title']);
        }

        $keywords = [];
        foreach($this->breadcrumbs() as $breadcrumb) {
            $keywords[] = \Str::lower($breadcrumb['title']);
        }

        $this->keywords(implode(',', $keywords) . ',' . str_replace(' ', ',', \Str::words($advert->content, 5, '')) . ',в ' . \Config::get('area')->dative_name);

        $advert->clickcount++;
        $advert->save();
        
        return view('advert.detail', [
            'version' => $this->version,
            'city' => \Config::get('area'),
            'advert' => $advert,
            'show_save_controlls' => false,
        ]);
    }

    public function create() {
        $this->header(__('adverts.create.seo.header', ['city' => \Config::get('area')->dative_name]));
        $this->title(__('adverts.create.seo.title', ['city' => \Config::get('area')->dative_name]));
        $this->description(__('adverts.create.seo.description', ['city' => \Config::get('area')->dative_name]));
        $this->keywords(__('adverts.create.seo.keywords', ['city' => \Config::get('area')->dative_name]));

    	return view('advert.create', [
            'version' => $this->version,
            'city' => \Config::get('area'),
            'errors' => $this->form_errors,
        ]);
    }

    public function save(Request $request) {
        $this->header(__('adverts.save.seo.header', ['city' => \Config::get('area')->dative_name]));
        $this->title(__('adverts.save.seo.title', ['city' => \Config::get('area')->dative_name]));
        $this->description(__('adverts.save.seo.description', ['city' => \Config::get('area')->dative_name]));
        $this->keywords(__('adverts.save.seo.keywords', ['city' => \Config::get('area')->dative_name]));

        $request->merge([
            'show_phone'    => $request->input('visible-phone', 0) == 0 ? 1 : 0,
            'accented'      =>  0,
        ]);

        $validator = $this->besplatnee->adverts()->validate($request->all());

        $this->form_errors = $validator->messages();

        if($validator->fails()) {
            return $this->create();
        }

        $user = $this->getUser($request->input('user', []));

        if(!$user) {
            $this->form_errors->add('user.email', 'Этот e-mail привязан к другому номеру телефона');
            return $this->create();
        }

        if($user->blocked || $user->phone()->first()->blocked) {
            $this->form_errors->add('user.phone', 'Номер заблокирован');
            return $this->create();
        }

        $data = $request->all();

        $editableAdverts = \Session::get('user.adverts.editable', collect());

        $data['duplicate_in_all_cities'] = false;
        $data['blocked'] = false;
        $data['send_to_print'] = false;
        $data['approved'] = false;

        $doubled = \App\Advert::where('name', $data['name'])->whereHas('owner', function($q) use ($data) {
            $q->where('phone', $data['user']['phone']);
        })->first();

        if(isset($data['id']) && $data['id'] > 0) {
            if(Auth::check() || $editableAdverts->contains($data['id'])) {
                unset($data['owner_id']);
                unset($data['creator_id']);

                $advert = $this->besplatnee->adverts()->update($data);
            } else {
                abort(404);
            }
        } else {
            if ($doubled) {
                $this->form_errors->add('user.phone', 'Такое объявление уже существует');
                return $this->create();
            }

            $data['owner_id'] = $data['creator_id'] = $user->id;

            $data['active'] = true;

            $advert = $this->besplatnee->adverts()->add($data);
            if(Auth::check()) {

            } else {
                //Auth::loginUsingId($advert->owner_id);
                $editableAdverts->push($advert->id);
                \Session::put('user.adverts.editable', $editableAdverts);
            }
        }

        switch ($data['advert_type']) {
            case 'mega':
                $advert->fill([
                    'type'          =>  3,
                ]);
            break;

            case 'accented':
                $advert->fill([
                    'type'          =>  4,
                    'accented'      =>  1,
                ]);
            break;
            
            default:
                $advert->fill([
                    'type'          =>  4,
                    'template_id'   =>  null,
                ]);
            break;
        }

        $advert->save();

        $advert->makeUrl();

        return redirect()->route('advert-detail', ['id' => $advert->id,]);
    }

    public function detail(Request $request, $id) {
        $this->header(__('adverts.detail.seo.header', ['city' => \Config::get('area')->dative_name]));
        $this->title(__('adverts.detail.seo.title', ['city' => \Config::get('area')->dative_name]));
        $this->description(__('adverts.detail.seo.description', ['city' => \Config::get('area')->dative_name]));
        $this->keywords(__('adverts.detail.seo.keywords', ['city' => \Config::get('area')->dative_name]));

        $advert = $this->besplatnee->adverts()->get($id);

        $editableAdverts = \Session::get('user.adverts.editable', collect());

        $isAdvertSelf = Auth::check() && Auth::user()->id == $advert->owner_id || $editableAdverts->contains($id);

        if(!$advert || !$isAdvertSelf) {
            abort(404);
        }

        $this->version = 'b';
        $this->header($advert->name);
        
        return view('advert.detail', [
            'version' => $this->version,
            'city' => \Config::get('area'),
            'advert' => $advert,
            'show_save_controlls' => true,
        ]);
    }

    public function preview(Request $request, $id) {
        $this->header(__('adverts.preview.seo.header', ['city' => \Config::get('area')->dative_name]));
        $this->title(__('adverts.preview.seo.title', ['city' => \Config::get('area')->dative_name]));
        $this->description(__('adverts.preview.seo.description', ['city' => \Config::get('area')->dative_name]));
        $this->keywords(__('adverts.preview.seo.keywords', ['city' => \Config::get('area')->dative_name]));

        $advert = $this->besplatnee->adverts()->get($id);

        $editableAdverts = \Session::get('user.adverts.editable', collect());

        $isAdvertSelf = Auth::check() && Auth::user()->id == $advert->owner_id || $editableAdverts->contains($id);

        if(!$advert || !$isAdvertSelf) {
            abort(404);
        }

        $this->version = 'e';
        $this->header($advert->name);
        
        return view('advert.preview', [
            'version' => $this->version,
            'city' => \Config::get('area'),
            'advert' => $advert,
        ]);
    }

    public function edit(Request $request, $id) {
        $this->header(__('adverts.edit.seo.header', ['city' => \Config::get('area')->dative_name]));
        $this->title(__('adverts.edit.seo.title', ['city' => \Config::get('area')->dative_name]));
        $this->description(__('adverts.edit.seo.description', ['city' => \Config::get('area')->dative_name]));
        $this->keywords(__('adverts.edit.seo.keywords', ['city' => \Config::get('area')->dative_name]));

        $advert = $this->besplatnee->adverts()->get($id);

        $editableAdverts = \Session::get('user.adverts.editable', collect());

        $isAdvertSelf = Auth::check() && Auth::user()->id == $advert->owner_id || $editableAdverts->contains($id);

        if(!$advert || !$isAdvertSelf) {
            abort(404);
        }

        $this->version = 'b';
        $this->header('Редактировать объявление');

        request()->merge($advert->toArray());
        request()->merge([
            'photos' => $advert->medias()->where('type', 'image')->distinct()->pluck('path'),
            'videos' => $advert->medias()->where('type', 'video')->distinct()->pluck('path'),
            'city_ids' => $advert->cities->first() ? [$advert->cities->first()->id] : [],
            'user' => $advert->owner->toArray(),
            'visible-phone' => !$advert->show_phone ? 1 : 0,
            'properties' => $advert->properties()->pluck('value', 'name'),
        ]);
        
        return view('advert.create', [
            'version' => $this->version,
            'city' => \Config::get('area'),
            'errors' => $this->form_errors,
        ]);
    }

    public function getUser(array $data) {
        if(Auth::check()) {
            return Auth::user();
        }

        $user = \App\User::where('phone', isset($data['phone']) ? $data['phone'] : '')->first();
        if($user) {
            //$user->password = '';
            return $user;
        }

        $data['password'] = uniqid();

        return \Besplatnee::users()->add($data);
    }

    public function success(Request $request, $id) {
        $this->header(__('adverts.success.seo.header', ['city' => \Config::get('area')->dative_name]));
        $this->title(__('adverts.success.seo.title', ['city' => \Config::get('area')->dative_name]));
        $this->description(__('adverts.success.seo.description', ['city' => \Config::get('area')->dative_name]));
        $this->keywords(__('adverts.success.seo.keywords', ['city' => \Config::get('area')->dative_name]));

        $advert = $this->besplatnee->adverts()->get($id);

        if(!$advert) { abort(404); }

        $editableAdverts = \Session::get('user.adverts.editable', collect());

        $isAdvertSelf = Auth::check() && Auth::user()->id == $advert->owner_id || $editableAdverts->contains($id);

        if(!$advert || !$isAdvertSelf) {
            abort(404);
        }

        $this->version = 'b';
        $this->header('');

        $this->besplatnee->adverts()->activate($id);

        return view('advert-success', [
            'version' => $this->version,
            'city' => \Config::get('area'),
            'advert' => $advert,
        ]);
    }

    public function search(Request $request) {
        $this->header('Результаты поиска');
        $this->version = 'b';

        $this->buildSorts();

        $query = preg_replace('~[^a-zA-Zа-яА-Я0-9]+~u', '_', mb_convert_case($request->input('search_query'), MB_CASE_LOWER, "UTF-8"));

        $search_area_id = $request->input('search_area_id', 'world');
        $heading = $request->input('search_category_id', null);

        $headings = $heading > 0 ? [$heading] : [];

        $gobjects = [];
        switch($search_area_id) {
            case 'world':
                    $gobjects = [];

                    $object_to_count = "world";
                break;
            case 'country':
                    $gobjects = array_merge(
                        \Config::get('area')->country()->city()->pluck('id')->toArray(),
                        \Config::get('area')->country()->region()->pluck('id')->toArray()
                    );

                    $object_name = mb_convert_case(\Config::get('area')->country()->ergative_name, MB_CASE_LOWER, "UTF-8");

                    $gobjects[] = \Config::get('area')->country()->id;

                    $object_to_count = [\Config::get('area')->country()->id];
                break;
            case 'region':
                    $gobjects = array_merge(
                        \Config::get('area')->region()->city()->pluck('id')->toArray()
                    );

                    $object_name = mb_convert_case(\Config::get('area')->region()->ergative_name, MB_CASE_LOWER, "UTF-8");

                    $gobjects[] = \Config::get('area')->region()->id;

                    $object_to_count = [\Config::get('area')->region()->id];
                break;
            default:
                    $object_name = mb_convert_case(\Config::get('area')->ergative_name, MB_CASE_LOWER, "UTF-8");

                    $gobjects = [$search_area_id];

                    $object_to_count = [\Config::get('area')->id];
                break;
        }

        $query_alias_international = str_replace('-', '_', app('slug')->make($query));

        if (!$query) {

            if(!$heading) {
                $adverts = $this->besplatnee->adverts()->getListForCity($gobjects);
            } else {
                $adverts = $this->besplatnee->adverts()->getListForCityByHeading($heading, $gobjects);
            }

            return view('search', [
                'adverts' => $adverts,
                'version' => $this->version,
                'city' => $gobjects,
                'query' => '',
                'searchFilters' => $this->filters,
                'empty' => [],
            ]);
        }

        $sended_data = [

            'alias' => $query,
            'alias_international' => $query_alias_international,

        ];

        $route_name = 'search_redirect_null';

        $object_name = '';
        if (!empty($gobjects) && count($gobjects) == 1) {
            $city_alias = \Config::get('area')->alias;
            $object_name = mb_convert_case(\Config::get('area')->name, MB_CASE_LOWER, "UTF-8");

            $sended_data['city'] = $city_alias;
            $sended_data['city_name'] = $object_name;
            $sended_data['city_name_international'] = app('slug')->make($object_name);
            $route_name = 'search_redirect';
        }

        


        return redirect()
            ->route($route_name, $sended_data)
            ->with([
                'object_name'       => $object_name,
                'object_name_international'       => app('slug')->make($object_name),
                'gobjects'          => $gobjects,
                'objects_to_count'  => $object_to_count,
                'heading'           => $heading,
                'properties'        => $request->input('properties', []),
                'search_query'      => $request->input('search_query'),
            ]);
    }

    public function searchRedirectNull (Request $request, $alias, $alias_international) {
        $object_name = session('object_name') ? session('object_name') : 'мире';
        $object_name_international = session('object_name_international') ? session('object_name_international') : 'mire';

        return $this->searchRedirect($request, null, $alias, $object_name, $alias_international, $object_name_international);
    }

    public function searchRedirect(Request $request, $region, $alias, $city_name, $alias_international, $city_name_international) {
        $this->header('Результаты поиска');
        $this->title('Поиск '.str_replace('_', ' ', $alias).' в '.\Str::ucfirst($city_name));
        $this->description('Поиск '.str_replace('_', ' ', $alias).' в '.\Str::ucfirst($city_name));
        $this->keywords('поиск, '.str_replace('_', ', ', $alias).', в, '.\Str::ucfirst($city_name));

        $this->version = 'b';

        $this->buildSorts();

        $gobjects = session('gobjects') ? session('gobjects') : [];
        $gobjects_to_count = session('objects_to_count') ? session('objects_to_count') : [\Config::get('area')->id];


        $heading = session('heading') ? session('heading') : [];
        $properties = session('properties') ? session('properties') : [];
        $search_query = session('search_query') ? session('search_query') : $alias;

        $headings = $heading > 0 ? [$heading] : [];

        foreach($properties as $name => $value) {
            if(!empty($value)) {
                $this->filters[$name] = $value;
            }
        }

        $query_data = new \App\AdvertSearchQuery;

        $query_data = $query_data->updateOrCreate([
                'alias'         =>  $alias,
            ],[
                'query'         =>  $search_query,
                'follows'       =>  \DB::raw('follows + 1'),
            ]);

        if ($gobjects_to_count != "world")
        foreach ($gobjects_to_count as $city) {
            $advert_search_query_city = new \App\AdvertSearchQueryCity;

            $advert_search_query_city->firstOrCreate([
                'advert_query_id'   =>  $query_data->id,
                'city_id'           =>  $city
            ]);
        }

        $this->adverts = Cache::remember('query_'.$query_data->id.'_city_'.(isset($gobjects[0]) && count($gobjects[0]) == 1 ? $gobjects[0] : $alias_international.$city_name_international).'_page_'.$request->page, $this->cache_minutes, function () use ($headings, $gobjects, $alias) {
            
            return $this->besplatnee->adverts()->getList([
                'limit' => 50,
                'paginate' => true,
                'headings' => $headings,
                'cities' => $gobjects,
                'level' => 9999,
                'not-ids' => \Auth::check() ? \Auth::user()->customAdverts()->wherePivot('hidden', true)->pluck('advert_id') : [],
                'sort' => [
                    $this->sortby => $this->sortdir,
                ],
                'search' => $alias,
                'filters' => $this->filters,
            ]);
        });

        if ($this->adverts->count() == 0) {
            $empty = Cache::remember('empty_search_results', $this->cache_minutes, function () use ($gobjects) {
            
                return $this->besplatnee->adverts()->getList([
                    'limit' => 50,
                    'maxLimit'  =>  300,
                    'paginate' => false,
                    'cities' => $gobjects,
                    'level' => 9999,
                    'not-ids' => \Auth::check() ? \Auth::user()->customAdverts()->wherePivot('hidden', true)->pluck('advert_id') : [],
                    'random'    =>  true,
                ]);
            });
        }

        $advert_ids = Cache::remember('query_'.$query_data->id.'_city_'.(isset($gobjects[0]) && count($gobjects[0]) == 1 ? $gobjects[0] : $alias_international.$city_name_international).'_ids', $this->cache_minutes, function () use ($headings, $gobjects, $alias) {

            return $this->besplatnee->adverts()->getList([
                'limit' => 0,
                'paginate' => false,
                'headings' => $headings,
                'cities' => $gobjects,
                'level' => 9999,
                'not-ids' => \Auth::check() ? \Auth::user()->customAdverts()->wherePivot('hidden', true)->pluck('advert_id') : [],
                'sort' => [
                    $this->sortby => $this->sortdir,
                ],
                'search' => $alias,
                'filters' => $this->filters,
            ])->pluck('id')->toJson();
        });

        if ($gobjects_to_count != "world")
        foreach ($gobjects_to_count as $city) {
            $results = new \App\AdvertSearchQueryResult;

            $results = $results->updateOrCreate([
                'search_id' =>  $query_data->id,
                'city_id'   =>  $city,
            ],
            [
                'count'     =>  $this->adverts->total(),
                'adverts'   =>  $advert_ids,
            ]);
        }

        $this->besplatnee->adverts()->touch($this->adverts);

        return view('search', [
            'adverts' => $this->adverts,
            'version' => $this->version,
            'city' => \Config::get('area'),
            'query' => $alias,
            'searchFilters' => $this->filters,
            'empty' => $this->adverts->count() == 0 ? $empty : [],
        ]);

    }

    public function buildSorts() {

    	$sorts = [];
    	foreach($this->defaultSorts as $alias => $sort) {
    		$sort['caption'] = __($sort['caption']);
    		$sorts[__($alias)] = $sort;
    	}

    	if($this->category) {
	    	foreach($this->category->filters()->get() as $sort) {
	    		$sorts[__($sort->alias)] = [
					'name' => $sort->property->name,
		    		'caption' => __($sort->caption),
		    		'direction' => $sort->direction,
		    		'active' => false,
	    		];
	    	}
	    }

    	$defaultSortBy = 'fakeupdated_at';
        if(empty($this->sortby)) {
            $this->sortby = $defaultSortBy;
        }

    	$this->sortdir = $defaultSortDir = 'desc';

    	if(isset($sorts[$this->sortby])) {
            $this->sortdir = $sorts[$this->sortby]['direction'];
            $sorts[$this->sortby]['active'] = true;
    		$this->sortby = $sorts[$this->sortby]['name'];
    	}

        $this->sorts = $sorts;
    }
}
