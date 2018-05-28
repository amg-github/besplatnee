<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlackListController extends AdminController {

	public function groups() {
		return [];
	}

	public function filters() {
		return [];
	}

	public function columns() {
		return [
			[
				'name' => 'actions',
				'title' => __('admin.blacklist.actions'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'action',
						'action' => 'remove',
						'icon' => \Besplatnee::forms()->icons()->image(asset('img/post-params/3.png')),
					],
				],
			],
            [
                'name' => 'phone',
                'title' => __('admin.users.phone'),
                'width' => '20%',
                'content' => [
                    [
                        'widget' => 'field',
                        'field' => 'phone',
                        'type' => 'text',
                    ],
                ],
            ],
			[
				'name' => 'blocked_at',
				'title' => __('admin.blacklist.blocked_at'),
				'width' => '20%',
				'content' => [
					[
						'widget' => 'field',
						'field' => 'blocked_at',
						'type' => 'date',
					],
				],
			],
		];
	}

	public function list(Request $request, $model) {
		parent::list($request, $model);

        $users = \Besplatnee::users()->getList(array_merge([
        	'limit' => 50,
			'paginate' => true,
			'active' => null,
			'blocked' => true,
			'groups' => [],
			'sort' => ['id' => 'ASC'],
        ], $this->getGroup()['wheres'], $this->getFilterWheres()));

        return view('admin.list.items', [
            'version' => $this->version,
            'items' => $users,
            'filters' => $this->filters,
            'groups' => $this->groups,
            'model' => $model,
            'columns' => $this->columns,
            'fastcreate' => [
                'phone' => [
                    'title' => 'Добавить номер в список',
                    'type' => 'text',
                ],
            ],
        ]);
	}

	public function edit(Request $request, $model, $id) {
		abort(404);
	}

	public function remove(Request $request, $model, $id) {
       \Besplatnee::users()->unBlocked($id);

       return redirect()->route('admin.list', ['model' => $model]);
	}

	public function create(Request $request, $model) {
		parent::create($request, $model);

        abort(404);
	}

	public function save(Request $request, $model, $id) {
	   \Besplatnee::users()->blockedPhone($request->input('phone', ''));

       return redirect()->route('admin.list', ['model' => $model]);
	}

}