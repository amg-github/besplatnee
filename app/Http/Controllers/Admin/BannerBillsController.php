<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BannerBillsController extends AdminController {

	public function groups() {
		return [];
	}

	public function filters() {
		return [];
	}

	public function columns() {
		return [
			[
				'name' => 'status',
				'title' => __('admin.banners.status'),
				'width' => '5%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($banner) {
							switch($banner->status) { 
								case 0: $iconName = 'grey'; break;
								case 3: $iconName = 'green'; break;
								case 2: $iconName = 'yellow'; break;
								case 4: 
								case 5: 
										$iconName = 'green'; 
									break;
							}
							return !empty($iconName) 
								? \Besplatnee::forms()->icons()->image(
									asset('img/statuses/' . $iconName . '.png'),
									__('banners.status.' . $banner->status),
									[
										'style' => 'width:16px;margin:0 auto;',
									]
								) 
								: '';
						}
					],
				],
			],
			[
				'name' => 'actions',
				'title' => __('admin.banners.actions'),
				'width' => '5%',
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
				'name' => 'cities',
				'title' => __('admin.banners.cities'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($banner) {
							return $banner->dublicate_in_all_cities || !$banner->city
								? __('banners.in_all_cities') 
								: $banner->city->getName();
						},
					],
				],
			],
			[
				'name' => 'user',
				'title' => __('admin.banners.content'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($banner) {
							return $banner->creator->phone;
						},
					],
				],
			],
			[
				'name' => 'content',
				'title' => __('admin.banners.content'),
				'width' => '20%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($banner) {
							return $banner->getImageTemplate();
						},
					],
				],
			],
			[
				'name' => 'position',
				'title' => __('admin.adverts.position'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($banner) {
							return __('admin.banners.positions.' . $banner->position);
						},
					],
				],
			],
			[
				'name' => 'price',
				'title' => __('admin.banners.price'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($banner) {
							return number_format($banner->price, 2, ',', ' ') . '&nbsp;Р';
						}
					],
				],
			],
			[
				'name' => 'period',
				'title' => __('admin.banner.period'),
				'width' => '20%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($banner) {
							return 'c ' . $banner->updated_at . '<br>по ' . $banner->deleted_at;
						}
					],
				],
			],
			[
				'name' => 'created',
				'title' => __('admin.banners.created'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'field',
						'field' => 'created_at',
						'type' => 'date',
					],
				],
			],
		];
	}

	public function list(Request $request, $model) {
		parent::list($request, $model);

        $banners = \Besplatnee::banners()->getList(array_merge([
        	'active' => null,
			'exclude' => [],
			'paginate' => true,
			'maxLimit' => 0,
			'limit' => 50,
			'offset' => 0,
			'return' => 'model', // model, array
			'type' => 'all', // normal, mega, all
			'sort' => ['created_at' => 'DESC'],
			'statuses' => [],
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

		$banner = \Besplatnee::banners()->get($id);

		if(!$banner) { abort(404); }

		return redirect()->route('admin.edit', ['model' => 'banners', 'id' => $id]);

	}

}