<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Besplatnee;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Artisan;

class GeoObjectsController extends AdminController {

	private $lang_prefix = 'headings.';

	public function groups () {
		return [];
	}


	public function createTree(&$list, $parent){
	    $tree = array();
	    foreach ($parent as $k=>$l){
	        if(isset($list[$l['id']])){
	            $l['children'] = $this->createTree($list, $list[$l['id']]);
	        }
	        $tree[] = $l;
	    } 
	    return $tree;
	}

	public function columns () {
		return [
			[
				'name' => 'actions',
				'title' => __('admin.headings.actions'),
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
                'title' => __('admin.headings.name'),
                'width' => '10%',
                'content' => [
                    [
                        'widget' => 'child',
                        'field' => 'name',
                        'type' => 'text',
                    ],
                ],
            ],
			[
				'name' => 'created_at',
				'title' => __('admin.headings.created_at'),
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

	public function filters () {
		return [];
	}

	public function list(Request $request, $model) {
		parent::list($request, $model);

		$parent = $request->parent;
		$name = $request->name;

		if ($parent) {

			$gobjects = \App\GeoObject::
				where('parent_id', $parent)
				->where('active', '1')
				->paginate(10);

			$gobjects->appends(request()->query());

		} elseif ($name) {

			$gobjects = \App\GeoObject::
				where('name', 'like', '%'.$name.'%')
				->where('active', '1')
				->paginate(10);

		} else {

			$gobjects = \App\GeoObject::
				where('type', 'country')
				->where('active', '1')
				->paginate(10);
		}

		return view('admin.list.gobjects', [
            'version' 	=> 	$this->version,
            'model' 	=> 	$model,
            'groups'	=>	$this->groups,
            'filters'	=>	$this->filters,
            'columns'	=>	$this->columns,
            'items'		=>	$gobjects,
        ]);
	}

	public function edit(Request $request, $model, $id) {
		Artisan::call('cache:clear');

		parent::edit($request, $model, $id);

		$geoobject = \App\GeoObject::find($id);

		if(\Gate::denies('edit', $geoobject)) { abort(403); }

		if(!$geoobject) { abort(404); }

		$countries = [];
        foreach(\App\GeoObject::where('type', 'country')->get() as $c) {
            $countries[] = [
                'title' => $c->name,
                'value' => $c->id,
            ];
        }


        $chain = $geoobject->makeChain();

		$regions = [];
        if (isset($chain[1])) {
	        foreach(\App\GeoObject::where('parent_id', $chain[1]->parent_id)->get() as $r) {
	            $regions[] = [
	                'title' => $r->name,
	                'value' => $r->id,
	            ];
	        }
        }

        $request->merge($geoobject->toArray());

		return view('admin.edit.gobject', [
            'version' => $this->version,
            'gobject' => $geoobject,
            'model' => $model,
            'fields' => $this->fields,
            'errors' => $this->form_errors,
            'complete_messages' => $this->complete_messages,
            'countries'	=>	$countries,
            'regions'	=>	$regions,
            'chain'	=>	$chain,
        ]);
	}

	public function create(Request $request, $model) {
		parent::create($request, $model);

		$geoobject = new \App\GeoObject;

		if(\Gate::denies('create', $geoobject)) { abort(403); }

		if(!$geoobject) { abort(404); }

		$countries = [];
        foreach(\App\GeoObject::where('type', 'country')->get() as $c) {
            $countries[] = [
                'title' => $c->name,
                'value' => $c->id,
            ];
        }

        if (isset($request->parent)) {
        	$parent = \App\GeoObject::find($request->parent);
        }

        $request->merge($geoobject->toArray());

		return view('admin.create.gobject', [
            'version' => $this->version,
            'gobject' => $geoobject,
            'model' => $model,
            'fields' => $this->fields,
            'errors' => $this->form_errors,
            'complete_messages' => $this->complete_messages,
            'regions'	=> [],
            'countries'	=>	$countries,
            'parent'	=> isset($parent) ? $parent : null,
        ]);
	}

	public function save(Request $request, $model, $id) {
		$isNew = intval($id) <= 0;

		$gobject = $isNew ? new \App\GeoObject : \App\GeoObject::find($id);

		if(\Gate::denies('create', $gobject)) { abort(403); }
		if(\Gate::denies('edit', $gobject)) { abort(403); }

		if(!$gobject) { abort(404); }

		$validator = Validator::make($request->all(), [
			'name'					=> 'required',
			'parent_id.*' 			=> 'nullable|not_in:'.$gobject->id.'|exists:geo_objects,id',
		]);

		if($validator->fails()) {
			$this->form_errors = $validator->messages();

			$this->complete_messages['danger'][] = 'Возникли ошибки при сохранении.';
            return $isNew ? $this->create($request, $model) : $this->edit($request, $model, $id);
		}

		$gobject->fill($request->all());

		$parent = '';
		$fullname = $request->name;
		$prid = null;
		$type = 'country';

		if (is_array($request->parent_id)) {
			foreach ($request->parent_id as $key => $id) {
				if (!empty($id))
					$prid = $id;
			}
		} else {
			$prid = $request->parent_id;
		}

		if (!empty($prid)) {
			$parent = \App\GeoObject::find($prid);
			$fullname = $gobject->generateFullName($parent->name);

			switch ($parent->type) {
				case 'country':
					$type = 'region';
					break;

				case 'region':
					$type = 'city';
					break;

				default:
					$type = 'country';
					break;
			}
		}
		
		$gobject->fill([
			'parent_id' => $prid,
			'fullname'	=> $fullname,
			'type'		=> $type,
		]);
		$gobject->save();

		if($isNew) {
			$this->complete_messages['success'][] = 'Геообъект успешно создан!';
		} else {
			$this->complete_messages['success'][] = 'Геообъект успешно сохранен!';
		}

		\Session::flash('complete_messages', $this->complete_messages);
		Artisan::call('cache:clear');

		
		return redirect()->route('admin.edit', ['model' => $model, 'id' => $gobject->id]);
	}

	public function remove(Request $request, $model, $id) {
		$gobject = \App\GeoObject::find($id);
        if(!$gobject) { abort(404); }

		if(\Gate::denies('remove', $gobject)) { abort(403); }

        $gobject->fill([
        	'active'	=>	0,
        ])->save();

        return redirect()->route('admin.list', ['model' => $model]);
	}
}