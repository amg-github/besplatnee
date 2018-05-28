<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends AdminController {
	public function groups () {
		return [];
	}

	public function columns () {
		return [
			[
				'name' => 'actions',
				'title' => __('admin.groups.actions'),
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
                'name' => 'name',
                'title' => __('admin.groups.name'),
                'width' => '30%',
                'content' => [
                    [
                        'widget' => 'field',
                        'field' => 'name',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'name' => 'description',
                'title' => __('admin.groups.description'),
                'width' => '40%',
                'content' => [
                    [
                        'widget' => 'field',
                        'field' => 'description',
                        'type' => 'text',
                    ],
                ],
            ],
			[
				'name' => 'created_at',
				'title' => __('admin.groups.created_at'),
				'width' => '20%',
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

	public function filters () {
		return [];
	}

	public function list(Request $request, $model) {
		parent::list($request, $model);

		$groups = \App\Group::paginate(50);

		return view('admin.list.items', [
            'version' => $this->version,
            'items' => $groups,
            'filters' => $this->filters,
            'groups' => $this->groups,
            'model' => $model,
            'columns' => $this->columns,
		]);
	}

	public function edit(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$group = \App\Group::find($id);

		if(!$group) { abort(404); }

        $request->merge($group->toArray());

		return view('admin.edit.group', [
            'version' => $this->version,
            'group' => $group,
            'model' => $model,
            'fields' => $this->fields,
            'errors' => $this->form_errors,
            'complete_messages' => $this->complete_messages,
        ]);
	}

	public function create(Request $request, $model) {

	}

	public function save(Request $request, $model, $id) {
		$isNew = intval($id) <= 0;

		$group = $isNew ? new \App\Group : \App\Group::find($id);

		if(!$group) { abort(404); }

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'description' => 'nullable|max:300',
			'sudo' => 'in:0,1',
			'permissions' => 'nullable|array',
			'permissions.*' => 'exists:permissions,id',
		]);

		if($validator->fails()) {
			$this->form_errors = $validator->messages();

			$this->complete_messages['danger'][] = 'Возникли ошибки при сохранении.';
            return $isNew ? $this->create($request, $model) : $this->edit($request, $model, $id);
		}

		$group->fill($request->all());
		$group->save();

		$group->permissions()->sync($request->input('permissions', []));

		if($isNew) {
			$this->complete_messages['success'][] = 'Группа пользователей успешно создана!';
		} else {
			$this->complete_messages['success'][] = 'Группа пользователей успешно сохранена!';
		}

		\Session::flash('complete_messages', $this->complete_messages);

		return redirect()->route('admin.edit', ['model' => $model, 'id' => $group->id]);
	}

	public function remove(Request $request, $model, $id) {

	}
}