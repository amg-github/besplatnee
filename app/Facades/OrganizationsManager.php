<?php 
namespace App\Facades;

use App\Organization;
use Illuminate\Support\Facades\Auth;

class OrganizationsManager extends ModelsManager {
	public $model = Organization::class;

	public function setData($model, $data) {
		$model->fill($data);

		$model->creator_id = Auth::check() ? Auth::id() : 1;
		$model->manager_id = isset($data['manager_id']) ? $model['manager_id'] : $model->creator_id;
		$model->user_id = isset($data['user_id']) ? $model['user_id'] : $model->creator_id;

		$model->save();

		return $model;
	}

	public function getList($properties) {

	}

	public function getListForForms($current = 0) {
		$organizations = [];

		foreach(Organization::all() as $organization) {
			$organizations[] = [
				'value' => $organization->id,
				'title' => $organization->name,
				'isroot' => false,
				'active' => $organization->id == $current,
			];
		}

		return $organizations;
	}
}