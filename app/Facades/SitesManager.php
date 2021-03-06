<?php 
namespace App\Facades;

use App\Site;
use App\SitePage;
use Illuminate\Support\Str;

class SitesManager extends ModelsManager {
	public $model = Sites::class;

	public function setData($model, $data) {
		$model->fill($data);

		if(empty($model->allias)) {
			$model->allias = Str::lower(app('slug')->make($heading->name));
		}

		$model->save();

		$page = $this->addPage($model->id, []);
		$model->main_page = $page->id;
		$model->save();

		return $model;
	}

	public function get($id, $removed = false) {
		if($removed && $this->hasSoftDelete()) {
			return Site::withTrashed()->find($id);
		} else {
			return Site::find($id);
		}
	}

	public function addPage($id, $data) {
		$page = new SitePage($data);
		$page->site_id = $id;
		$page->save();

		return $page;
	}

	public function getList($properties) {

	}
}