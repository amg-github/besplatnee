<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdvertSearchController extends AdminController {

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
				'width' => '20%',
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
				'width' => '10%',
				'content' => [
					[
						'widget' => 'relation',
						'relation' => 'cities',
						'view' => function ($city) {
							return $city->name;
						},
						'separator' => ',&nbsp;',
					],
				],
			],
			[
				'name' => 'created',
				'title' => __('admin.adverts.created'),
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

		if($request->isMethod('post') && !empty($request->input('search'))) {
			return $this->searchResult($request, $model);
		} else {
			return $this->searchForm($request, $model);
		}

		$this->version = 'a';

		//dd(request()->all());

        /*$adverts = \Besplatnee::adverts()->getList(array_merge([
        	'limit' => 50,
			'paginate' => true,
			'actived' => null,
			'blocked' => null,
			'deleted' => false,
			'approved' => null,
			'headings' => [],
			'cities' => [],
			'sort' => ['id' => 'ASC'],
			'level' => 999,
			'filters' => [],
        ], $this->getGroup()['wheres'], $this->getFilterWheres()));*/

        return view('admin.list.search', [
            'version' => $this->version,
            'model' => $model,
        ]);
	}

	public function searchForm(Request $request, $model) {
		$this->version = 'a';

		return view('admin.list.search', [
            'version' => $this->version,
            'model' => $model,
        ]);
	}

	public function searchResult(Request $request, $model) {
		$adverts = \Besplatnee::adverts()->getList(array_merge([
        	'limit' => 50,
			'paginate' => true,
			'actived' => null,
			'blocked' => null,
			'deleted' => false,
			'approved' => null,
			'headings' => [],
			'cities' => $request->input('cities', []),
			'sort' => ['id' => 'ASC'],
			'level' => 999,
			'filters' => [],
			'search' => $request->input('search', ''),
			'searchfields' => $request->input('searchfields', ['id']),
        ], $this->getGroup()['wheres'], $this->getFilterWheres()));

        return view('admin.list.items', [
            'version' => $this->version,
            'items' => $adverts,
            'filters' => $this->filters,
            'groups' => $this->groups,
            'model' => $model,
            'columns' => $this->columns,
        ]);
	}

	public function edit(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$advert = \Besplatnee::adverts()->get($id);

		if(!$advert) { abort(404); }

		return redirect()->route('admin.edit', ['model' => 'adverts', 'id' => $id]);

	}

}