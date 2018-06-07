<?php 
namespace App\Facades;

use App\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BannersManager extends ModelsManager {
	public $model = Banner::class;
	public $_banners = null;

	public function validate($data, $isValidate = false) {
		$rules = [
			'name' => 'nullable|string',
			'hover_text' => 'nullable|string',
			'contact_information' => 'nullable|string',
			'url' => 'nullable',
			'position' => 'required|in:header,footer,left,right,advert',
			'image' => 'required',
			'block_number' => 'required_if:position,left,right,advert|between:0,9',
			'banner_number' => 'required|between:0,5',
			'heading_id' => 'nullable|exists:headings,id',
			'city_ids' => 'array',
			'city_ids.*' => 'exists:cities,id',
			'region_ids' => 'array',
			'region_ids.*' => 'exists:regions,id',
			'country_ids' => 'array',
			'country_ids.*' => 'exists:countries,id',
			'duplicate_in_all_cities' => 'in:0,1',
			'organization_id' => 'nullable|exists:organizations,id',
		];

		$validator = Validator::make($data, $rules);

        return $isValidate ? ($validator->fails() ?? $validator->messages()) : $validator;
	}

	public function setData($model, $data) {
		$model->fill($data);

		$model->creator_id = Auth::check() ? Auth::id() : 1;
		$model->organization_id = isset($data['organization_id']) ? $model['organization_id'] : null;

		/*if($model->sortindex == 0) {
			$model->sortindex = Banner::where('position', $model->position)->max('sortindex');
			$model->sortindex++;
		}*/

		if(isset($data['bannerimage'])) {
			$image = $model->image()->first();
			if(!$image) {
				$image = new \App\BannerImage;
			}

			$image->fill($data['bannerimage']);
			$image->save();
			
			$model->image()->associate($image);
		}

		$model->save();

		return $model;
	}

	public function _getPosition($position, $heading_id = 0) {
		return Banner::where('active', true)
			->where('position', $position)
			->whereIn('heading_id', [0, $heading_id]);
	}

	public function loadBanners($position = null, $heading_id = 0, $offset = 0, $limit = 0, $touched = false) {
		if(!$this->_banners) {
			$this->_banners = collect();

			$banners = Banner::where('active', true)->orderBy('sortindex');

			if(\Auth::check()) {
	        	$hideIds = \Auth::user()->customBanners()->wherePivot('hidden', true)->pluck('id');
	        	$banners->whereNotIn('id', $hideIds);
	        }

	        $banners->update([
				'viewcount' => \DB::raw('viewcount + 1'),
			]);

	        foreach($banners->get() as $banner) {
	        	if(!$this->_banners->has($banner->position)) {
	        		$this->_banners->put($banner->position, collect());
	        	}

	        	$this->_banners->get($banner->position)->push($banner);
	        }
		}

		$banners = collect();

		if($this->_banners->has($position)) {
			$counter = 0;
			foreach($this->_banners->get($position) as $banner) {
				$counter++;

				$inHeading = $banner->heading_id == 0 || $banner->heading_id == $heading_id;

				if($counter > $offset && $inHeading) {
					$banners->push($banner);

					if($counter >= $offset + $limit && $limit > 0) { break; }
				}
			}
		}

		return $banners;
	}

	public function getPosition($position, $heading_id = 0, $offset = 0, $limit = 0) {
		/*$query = $this->_getPosition($position, $heading_id)
            ->orderBy('sortindex');

        if(\Auth::check()) {
        	$hideIds = \Auth::user()->customBanners()->wherePivot('hidden', true)->pluck('id');
        	$query->whereNotIn('id', $hideIds);
        }

        if($limit > 0) {
        	$count = $query->count();
        	$_offset = $offset % $count;
        	$limitterBanners = $query->skip($_offset)
        		->take($limit)->get();


        	$limit -= $count + $_offset;
        	$_offset = 0;
        	while($limit > 0) {
        		$limitterBanners = $limitterBanners->concat($query->skip(0)->take($limit)->get());
        		$limit -= $count;
        	}
        } else {
        	$limitterBanners = $query->get();
        }

        return $limitterBanners;*/
        return $this->loadBanners($position, $heading_id, $offset, $limit, true);
	}

	public function hideForMe($id) {
		/*if(\Auth::check()) {
			\Auth::user()->customBanners()->sync([$id => ['hidden' => true]], false);
		}*/

		$hiddenBanners = collect(\Session::get('userdata.banners.hidden.ids', []));

		if(!$hiddenBanners->contains($id)) {
			$hiddenBanners->push($id);
		}

		\Session::put('userdata.banners.hidden.ids', $hiddenBanners->toArray());
	}

	public function showForMe($id) {
		$hiddenBanners = collect(\Session::get('userdata.banners.hidden.ids', []));

		$hiddenBanners = $hiddenBanners->filter(function ($item) use ($id) {
			return $item != $id;
		});

		\Session::put('userdata.banners.hidden.ids', $hiddenBanners->toArray());
	}

	public function getList($properties) {
		$properties = array_merge([
			'active' => true,
			'exclude' => [],
			'paginate' => false,
			'maxLimit' => 0,
			'limit' => 0,
			'offset' => 0,
			'return' => 'model', // model, array
			'type' => 'normal', // normal, mega, all
			'sort' => ['sortindex' => 'ASC'],
			'statuses' => [],
		], $properties);

		if($properties['maxLimit'] > 0 && $properties['limit'] > $properties['maxLimit']) {
			$properties['limit'] = $properties['maxLimit'];
		}

		$banners = Banner::with('creator')
            ->with('heading')
            ->with('geoObjects');

//		$banners->whereHas('geoObjects', function($q) {
//            $q->where('id', \Config::get('area')->id);
//        });

		if($properties['active'] !== null) {
			$banners->where('active', $properties['active']);
		}

		if(count($properties['exclude']) > 0) {
			$banners->whereNotIn('id', $properties['exclude']);
		}

		switch($properties['type']) {
			case 'normal':
					$banners->doesntHave('image');
				break;
			case 'mega':
					$banners->has('image');
				break;
		}

		if($properties['maxLimit'] > 0) {
			$banners->take($properties['maxLimit']);
		}

		foreach($properties['sort'] as $sortby => $sortdir) {
			$banners->orderBy($sortby, $sortdir);
		}

		if($properties['paginate']) {
			$banners = $banners->paginate($properties['limit']);
		} else {
			if($properties['limit'] > 0) {
				$banners->take($properties['limit'])->skip($properties['offset']);
			}

			$banners = $banners->get();
		}

		switch($properties['return']) {
			case 'model': break;
			case 'array':
					$banners = $banners->toArray();
				break;
			default: break;
		}

		return $banners;
	}

	public function getBanners($position, $heading_id = null, $block_number = null) {
		$banners = Banner::where('active', true)
			->where('position', $position)
			->where(function ($query) use ($heading_id) {
				$query->where('heading_id', $heading_id)
					->orWhereNull('heading_id');
			})
			->where(function ($q) {
				$q->where('duplicate_in_all_cities', true)
                    ->orWhereExists(function ($query) {
                        $query->select(\DB::raw(1))
                            ->from('banner_geo_object')
                            ->where('geo_object_id', config('area')->id)
                            ->whereRaw('`banner_geo_object`.`banner_id` = `banners`.`id`');
                    });
			})
			->orderBy('banner_number')
            ->with('heading')
			->with('geoObjects');

		if($block_number === null) {
			$banners->where(function ($q) {
				$q->where('block_number', 0);
				$q->orWhereNull('block_number');
			});
		} else {
			$banners->where('block_number', $block_number);
		}

		$banners = $banners->get();

		$result = [];

		foreach($banners as $banner) {
			// если нет баннеров в этом месте, то размещаем
			if(!isset($result[$banner->banner_number])) {
				$result[$banner->banner_number] = $banner;
				continue ;
			}


			if($banner->heading_id == $heading_id) {
				// если баннер размещен в текущей категории и в текущем городе то размещаем
				if($banner->inObject(config('area')->id)) {
					$result[$banner->banner_number] = $banner;
					continue ;
				}

				$currentBannerInCity = $result[$banner->banner_number]->inObject(config('area')->id);
				$currentBannerInRegion = $result[$banner->banner_number]->inObject(config('area')->id);

				// если баннер размещен в текущей категории и в текущем регионе то размещаем
				if($banner->inObject(config('area')->region_id)) {
					if(!$currentBannerInCity) {
						$result[$banner->banner_number] = $banner;
						continue ;
					}
				}

				$currentBannerInCountry = $result[$banner->banner_number]->inObject(config('area')->id);

				if($banner->inObject(config('area')->country_id)) {
					if(!$currentBannerInCountry) {
						$result[$banner->banner_number] = $banner;
						continue ;
					}
				}
			} else {
				$currentBannerInHeading = $result[$banner->banner_number]->heading_id == $heading_id;
				$currentBannerInCity = $result[$banner->banner_number]->inObject(config('area')->id);
				// если баннер размещен в текущей категории и в текущем городе то размещаем
				if($banner->inObject(config('area')->id) && !($currentBannerInCity && $currentBannerInHeading)) {
					$result[$banner->banner_number] = $banner;
					continue ;
				}

				$currentBannerInRegion = $result[$banner->banner_number]->inObject(config('area')->id);

				// если баннер размещен в текущей категории и в текущем регионе то размещаем
				if($banner->inObject(config('area')->region_id) && !$currentBannerInCity && !($currentBannerInRegion && $currentBannerInHeading)) {
					$result[$banner->banner_number] = $banner;
					continue ;
				}

				$currentBannerInCountry = $result[$banner->banner_number]->inObject(config('area')->id);

				if($banner->inObject(config('area')->country_id) && !$currentBannerInCity && !$currentBannerInRegion && !($currentBannerInCountry && $currentBannerInHeading)) {
					$result[$banner->banner_number] = $banner;
					continue ;
				}
			}
		}

		return $result;
	}
}