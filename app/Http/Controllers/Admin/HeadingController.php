<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Besplatnee;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Artisan;

class HeadingController extends AdminController {

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
                        'widget' => 'field',
                        'field' => 'name',
                        'type' => 'text',
                    ],
                ],
            ],
            // [
            //     'name' => 'parent_head',
            //     'title' => __('admin.headings.parent'),
            //     'width' => '10%',
            //     'content' => [
            //         [
            //             'widget' => 'heading',
            //             'field' => 'parent_id',
            //             'type' => 'text',
            //         ],
            //     ],
            // ],
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
			[
				'name' => 'sortindex',
				'title' => __('admin.headings.sort'),
				'width' => '5%',
				'content' => [
					[
						'widget' => 'field',
						'field' => 'sortindex',
						'type' => 'text',
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

		$headings = \App\Heading::
			where('active', '!=', '0')
			->orderBy('sortindex', 'asc')
			->paginate(200);

		foreach ($headings as $key => &$head) {
			$head->name = __($head->name);

			if ($head->parent_id) {
				$head->parent = \App\Heading::find($head->parent_id);
			}
		}
		unset($head);

		$headings = $headings->toArray();

		$new = array();
		foreach ($headings['data'] as $a){
		    $new[$a['parent_id']][] = $a;
		}
		$tree = $this->createTree($new, $new['']); // changed

		return view('admin.list.headings', [
            'version' => $this->version,
            'items' => $headings,
            'filters' => $this->filters,
            'groups' => $this->groups,
            'model' => $model,
            'columns' => $this->columns,
            'parents'	=>	$tree,
		]);
	}

	public function edit(Request $request, $model, $id) {
		Artisan::call('cache:clear');

		parent::edit($request, $model, $id);

		$heading = \App\Heading::find($id);

		if(\Gate::denies('edit', $heading)) { abort(403); }

		$heading->code = str_replace($this->lang_prefix, '', $heading->name);

		$categories = [];
        foreach(\App\Heading::where('id', '!=', $heading->id)->get() as $cat) {
            $categories[] = [
                'title' => $cat->name,
                'value' => $cat->id,
            ];
        }

        $langs = [];
        foreach(\App\Language::get() as $lang) {
            $langs[$lang->code] =	$lang;
        }

        $names = $heading->getNames();

		if(!$heading) { abort(404); }

        $request->merge($heading->toArray());

        $aliases = $heading->aliases->keyBy('language');

		return view('admin.edit.heading', [
            'version' => $this->version,
            'heading' => $heading,
            'model' => $model,
            'fields' => $this->fields,
            'errors' => $this->form_errors,
            'complete_messages' => $this->complete_messages,
            'categories' => $categories,
            'aliases'	=>	$aliases,
            'langs'		=>	$langs,
            'names'		=>	$names,
        ]);
	}

	public function create(Request $request, $model) {
		parent::create($request, $model);

		$heading = new \App\Heading;

		if(\Gate::denies('create', $heading)) { abort(403); }

        $langs = [];
		foreach(\App\Language::get() as $lang) {
            $aliases[] = new \App\HeadingAlias([
            	'language' => $lang->code,
            ]);

            $langs[$lang->code] =	$lang;
        }

		$heading->fname = __($heading->name);

		$categories = [];
        foreach(\App\Heading::where('id', '!=', $heading->id)->get() as $cat) {
            $categories[] = [
                'title' => $cat->name,
                'value' => $cat->id,
            ];
        }


        return view('admin.create.heading', [
            'version' => $this->version,
            'user' => new \App\User,
            'heading' => $heading,
            'errors' => $this->form_errors,
            'categories' => $categories,
            'aliases'	=>	$aliases,
            'langs'		=>	$langs,
        ]);
	}

	public function save(Request $request, $model, $id) {
		$lang = Lang::get('headings');
        $lang_key = str_replace("headings_", "", str_replace(".", "_", $request->name));

        $request->merge(['name' => $this->lang_prefix.$request->name]);

		$isNew = intval($id) <= 0;

		if ($isNew) {
			$heading = new \App\Heading;
			$aliases = new \App\HeadingAlias;
		} else {
			$heading = \App\Heading::find($id);
			$aliases = $heading->aliases;
		}

		if(\Gate::denies('create', $heading)) { abort(403); }
		if(\Gate::denies('edit', $heading)) { abort(403); }

		if(!$heading) { abort(404); }

		$validator = Validator::make($request->all(), [
			'name'					=> 'required|unique:headings,name,'.$request->id,
			'lang_name.*'			=> 'required',
			'alias_international.*'	=> 'required',
			'alias_local.*'			=> 'required',
			'parent_id' 			=> 'nullable|not_in:'.$heading->id.'|exists:headings,id',
			'sortindex'				=> 'nullable|min:0|max:999',
		]);

		if($validator->fails()) {
			$this->form_errors = $validator->messages();

			$this->complete_messages['danger'][] = 'Возникли ошибки при сохранении.';
            return $isNew ? $this->create($request, $model) : $this->edit($request, $model, $id);
		}

		$heading->fill($request->all());

		$heading->save();

		$names = [];

		if ($isNew) {
			foreach(\App\Language::get() as $lang) {
				 $aliases->create([
				 	'alias_local' 			=>	$request->alias_local[$lang->code],
				 	'alias_international'	=>	$request->alias_international[$lang->code],
				 	'language'				=>	$lang->code,
				 	'heading_id'			=>	$heading->id,
				 ]);

				$names[$lang->code] = $request->lang_name[$lang->code];
			}

			$heading->setNames($names);
		}
		else {

			foreach ($aliases as $key => &$alias) {

				foreach ($request->alias_local as $lang => $local) {
					if ($lang == $alias->language)
						$alias->fill(['alias_local' => $local]);
				}
				foreach ($request->alias_international as $lang => $international) {
					if ($lang == $alias->language) 
						$alias->fill(['alias_international' => $international]);
				}

				foreach ($request->lang_name as $lang => $lang_name) {
					if ($lang == $alias->language) 
						$names[$lang] = $request->lang_name[$lang];
				}

				$heading->setNames($names);

				$alias->save();

			}
			unset($alias);

		}

		if($isNew) {
			$this->complete_messages['success'][] = 'Рубрика успешно создана!';
		} else {
			$this->complete_messages['success'][] = 'Рубрика успешно сохранена!';
		}

		\Session::flash('complete_messages', $this->complete_messages);
		Artisan::call('cache:clear');

		
		return redirect()->route('admin.edit', ['model' => $model, 'id' => $heading->id]);
	}

	public function remove(Request $request, $model, $id) {
		$heading = \App\Heading::find($id);
        if(!$heading) { abort(404); }

		if(\Gate::denies('remove', $heading)) { abort(403); }

        $heading->fill([
        	'active'	=>	0,
        ])->save();

        return redirect()->route('admin.list', ['model' => $model]);
	}
}