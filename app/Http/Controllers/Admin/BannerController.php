<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BannerController extends AdminController {

	public function groups() {
		return [];
	}

	public function filters() {
		$positions = ['header', 'left', 'right', 'footer', 'advert'];
		$values = [];
		foreach($positions as $position) {
			$values[] = [
				'value' => $position,
				'isroot' => false,
				'title' => __('admin.banners.positions.' . $position),
				'active' => collect(request()->input('position', []))->contains($position),
			];
		}

		return [
			[
				'name' => 'position',
				'title' => __('admin.adverts.position'),
				'default' => [],
				'multiple' => true,
				'type' => 'select',
				'values' => $values,
			],
		];
	}

	public function columns() {
		return [
			[
				'name' => 'status',
				'title' => __('admin.adverts.status'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'switcher',
						'subject' => 'active',
						'on' => [
							'title' => '',
							'icon' => \Besplatnee::forms()->icons()->fontAwesome('check', 'green', '18px'),
							'font-size' => '18px',
							'color' => 'green',
							'alt' => __('admin.banners.active.check.title'),
						],
						'off' => [
							'title' => '',
							'icon' => \Besplatnee::forms()->icons()->fontAwesome('times', 'red', '18px'),
							'font-size' => '18px',
							'color' => 'red',
							'alt' => __('admin.banners.active.uncheck.title'),
						],
					],
				],
			],
			[
				'name' => 'actions',
				'title' => __('admin.banners.actions'),
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
				'name' => 'position',
				'title' => __('admin.banners.content'),
				'width' => '20%',
				'content' => [
					[
						'widget' => 'banner-status',
					],
				],
			],
			[
				'name' => 'content',
				'title' => __('admin.banners.content'),
				'width' => '50%',
				'content' => [
					[
						'widget' => 'banner',
					],
				],
			],
		];
	}

	public function list(Request $request, $model) {
		parent::list($request, $model);

        $banners = \Besplatnee::banners()->getList(array_merge([
        	'limit' => 50,
			'paginate' => true,
			'active' => null,
			'type' => 'normal',
			'sort' => ['id' => 'ASC'],
        ], $this->getGroup()['wheres'], $this->getFilterWheres()));

        return view('admin.list.items', [
            'version' => $this->version,
            'items' => $banners,
            'filters' => $this->filters,
            'groups' => $this->groups,
            'model' => $model,
            'columns' => $this->columns,
        ]);
	}

	public function edit(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$banner = \App\Banner::find($id);
        if(!$banner) { abort(404); }

        if(\Gate::denies('edit', $banner)) { abort(403); }

        request()->merge($banner->toArray());
        request()->merge([
        	'period' => $banner->getPeriod(),
        	'city_ids' => $banner->cities()->pluck('id'),
        	'region_ids' => $banner->regions()->pluck('id'),
        	'country_ids' => $banner->countries()->pluck('id'),
        ]);

        return view('admin.edit.banner', [
            'version' => $this->version,
            'banner' => $banner,
            'errors' => $this->form_errors,
            'positions' => $this->filters()[0]['values'],
            'complete_messages' => $this->complete_messages,
        ]);
	}

	public function remove(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$banner = \App\Banner::find($id);
        if(!$banner) { abort(404); }

        if(\Gate::denies('remove', $banner)) { abort(403); }

        $banner->delete();

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function create(Request $request, $model) {
		parent::create($request, $model);

        return view('admin.edit.banner', [
            'version' => $this->version,
            'banner' => new \App\Banner,
            'errors' => $this->form_errors,
            'positions' => $this->filters()[0]['values'],
            'complete_messages' => $this->complete_messages,
        ]);
	}

	public function save(Request $request, $model, $id) {
		$data = $request->all();
		$isNew = !isset($data['id']) || intval($data['id']) <= 0;

        if(!$isNew) {
        
            $banner = \App\Banner::find($id);
            if(\Gate::denies('edit', $banner)) { abort(403); }

            unset($data['creator_id']);
        } else {
            $banner = new \App\Banner;
        }

        $validator = \Besplatnee::banners()->validate($data);

        $this->form_errors = $validator->messages();

        if(!$validator->fails()) {


	        if(in_array($data['position'], ['header', 'footer'])) { 
	            $data['block_number'] = null;
	        }

            if(!$isNew) {
                \Besplatnee::banners()->update($data);
                $this->complete_messages['success'][] = 'Баннер успешно сохранен!';
            } else {
                $banner = \Besplatnee::banners()->add($data);
                $id = $banner->id;
                $this->complete_messages['success'][] = 'Баннер успешно создан!';
            }

            $banner->setDeletedAt($data['period']);
            $banner->save();


			$blankAreas = $banner->placement(
				isset($data['duplicate_in_all_cities']) && boolval($data['duplicate_in_all_cities']), 
				isset($data['country_ids']) ? $data['country_ids'] : [], 
				isset($data['region_ids']) ? $data['region_ids'] : [], 
				isset($data['city_ids']) ? $data['city_ids'] : []
			);

			foreach($blankAreas as $area) {
				switch($area['type']) {
					case 'world':
							$this->complete_messages['warning'][] = 'Не удалось разместить баннер на весь мир. Место занято.';
						break;
					case 'country':
							$this->complete_messages['warning'][] = 'Не удалось разместить баннер в ' . \App\Country::find($area['id'])->getName() . '. Место занято.';
						break;
					case 'region':
							$this->complete_messages['warning'][] = 'Не удалось разместить баннер в ' . \App\Region::find($area['id'])->getName() . '. Место занято.';
						break;
					case 'city':
							$this->complete_messages['warning'][] = 'Не удалось разместить баннер в ' . \App\City::find($area['id'])->getName() . '. Место занято.';
						break;
				}
			}

			if($isNew) {
				\Session::flash('complete_messages', $this->complete_messages);
				return redirect()->route('admin.edit', ['model' => $model, 'id' => $id]);
			} else {
            	return $this->edit($request, $model, $id);
			}



        } else {
            $this->complete_messages['danger'][] = 'Возникли ошибки при сохранении.';
            return $banner->id > 0 ? $this->edit($request, $model, $id) : $this->create($request, $model);
        }
    }

}