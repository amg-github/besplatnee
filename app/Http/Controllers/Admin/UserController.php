<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends AdminController {

	public function groups() {
		return [
			[
				'name' => 'admins',
				'title' => __('admin.users.admins'),
				'active' => false,
				'wheres' => [
					'groups' => [2,3,4,5,6,7,8,9,10],
				],
			],
			[
				'name' => 'all',
				'title' => __('admin.users.all'),
				'active' => true,
				'wheres' => [],
			],
		];
	}

	public function filters() {
		return [
            [
                'type' => 'text',
                'name' => 'search',
                'title' => 'Поиск по номеру',
            ],
        ];
	}

	public function columns() {
		return [
			[
				'name' => 'actions',
				'title' => __('admin.users.actions'),
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
				'name' => 'content',
				'title' => __('admin.users.content'),
				'width' => '50%',
				'content' => [
					[
						'widget' => 'user',
					],
				],
			],
			[
				'name' => 'cities',
				'title' => __('admin.users.cities'),
				'width' => '20%',
				'content' => [
					[
						'widget' => 'user-cities',
					],
				],
			],
			[
				'name' => 'created',
				'title' => __('admin.adverts.created'),
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

	public function list(Request $request, $model) {
		parent::list($request, $model);

        $users = \Besplatnee::users()->getList(array_merge([
        	'limit' => 50,
			'paginate' => true,
			'active' => null,
			'blocked' => null,
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
        ]);
	}

	public function edit(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$user = \App\User::find($id);
        if(!$user) { abort(404); }

        if(\Gate::denies('edit', $user)) { abort(403); }

        request()->merge($user->toArray());
        request()->merge([
            'verify' => $user->phone()->first()->verify,
            'group_id' => $user->groups()->first()->id,
            'city_ids' => $user->cities()->pluck('id'),
            'region_ids' => $user->regions()->pluck('id'),
            'country_ids' => $user->countries()->pluck('id'),
        ]);

        $groups = [];
        foreach(\App\Group::all() as $group) {
            $groups[] = [
                'title' => $group->name,
                'value' => $group->id,
            ];
        }

        return view('admin.edit.user', [
            'version' => $this->version,
            'user' => $user,
            'groups' => $groups,
            'errors' => $this->form_errors,
        ]);
	}

	public function remove(Request $request, $model, $id) {
        parent::edit($request, $model, $id);

        $user = \App\User::find($id);
        if(!$user) { abort(404); }

        if(\Gate::denies('remove', $user)) { abort(403); }

        $user->delete();

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function create(Request $request, $model) {
		parent::create($request, $model);

        $groups = [];
        foreach(\App\Group::all() as $group) {
            $groups[] = [
                'title' => $group->name,
                'value' => $group->id,
            ];
        }

        return view('admin.create.user', [
            'version' => $this->version,
            'user' => new \App\User,
            'groups' => $groups,
            'errors' => $this->form_errors,
        ]);
	}

	public function save(Request $request, $model, $id) {
        $request->merge([
            'city_ids' => $request->input('city_ids', []),
            'region_ids' => $request->input('region_ids', []),
            'country_ids' => $request->input('country_ids', []),
        ]);

		$data = $request->all();
        $isNew = !isset($data['id']) || intval($data['id']) <= 0;

        if(!$isNew) {
            $user = \App\User::find($id);
            if(\Gate::denies('edit', $user)) { abort(403); }

            unset($data['creator_id']);

        } else {
            $user = new \App\User;

            if(\Gate::denies('create', $user)) { abort(403); }
        }

        if(!$request->has('new-password') && !$isNew) {
            unset($data['password']);
        }

        $matches = [
            'phone' => 'required|numeric|min:8|max:999999999999999',
            'verify' => 'required|in:0,1',
            'group_id' => 'required|in:' . \App\Group::all()->implode('id', ','),
        ];

        if($isNew) {
            $matches['password'] = 'required';
            $matches['phone'] .= '|unique:users,phone';
        }

        $validator = Validator::make($request->all(), $matches, [
            'required' => 'Это поле обязательно.',
            'max' => 'Длинна этого поля не должна превышать :max.',
            'min' => 'Длинна этого поля не должна быть больше :min.',
            'email' => 'Некорректный email',
            'phone' => 'Некорректный телефон',
            'price.regex' => 'Некорректная цена',
        ]);

        $this->form_errors = $validator->messages();

        if(!$validator->fails()) {

            if(!$isNew) {
                \Besplatnee::users()->update($data);
            } else {
                $user = \Besplatnee::users()->add($data);

                $id = $user->id;
            }

            if(isset($data['group_id'])) {
                $user->groups()->sync([$data['group_id']]);
            }

            if($isNew) {
            	return redirect()->route('admin.edit', ['model' => $model, 'id' => $id]);
            } else {
            	return $this->edit($request, $model, $id);
            }

        } else {
            if(!$isNew) {
                return $this->edit($request, $model, $id);
            } else {
                return $this->create($request, $model);
            }
        }
	}

}