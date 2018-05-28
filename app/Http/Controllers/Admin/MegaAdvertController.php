<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MegaAdvertController extends AdminController {

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
				'title' => __('admin.megaadvert.content'),
				'width' => '50%',
				'content' => [
					[
						'widget' => 'megaadvert',
					],
				],
			],
			[
				'name' => 'created',
				'title' => __('admin.megaadvert.created'),
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

        $banners = \Besplatnee::banners()->getList(array_merge([
        	'limit' => 50,
			'paginate' => true,
            'type' => 'mega',
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

		$megaadvert = \App\Banner::find($id);
        if(!$megaadvert) { abort(404); }

        if(\Gate::denies('edit', $megaadvert)) { abort(403); }

        request()->merge($megaadvert->toArray());
        request()->merge([
            'bannerimage' => $megaadvert->image()->first()->toArray(),
        ]);

        return view('admin.edit.megaadvert', [
            'version' => $this->version,
            'megaadvert' => $megaadvert,
            'errors' => $this->form_errors,
            'model' => $model,
            'bannerImage' => $megaadvert->image()->first(),
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

        return view('admin.edit.megaadvert', [
            'version' => $this->version,
            'megaadvert' => new \App\Banner,
            'errors' => $this->form_errors,
            'model' => $model,
            'bannerImage' => new \App\BannerImage,
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

            if(\Gate::denies('create', $banner)) { abort(403); }
        }

        if(!$isNew) {
            \Besplatnee::banners()->update($data);
            return $this->edit($request, $model, $id);
        } else {
            $banner = \Besplatnee::banners()->add($data);

            $id = $banner->id;
            return redirect()->route('admin.edit', ['model' => $model, 'id' => $id]);
        }
	}

}