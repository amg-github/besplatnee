<?php 
namespace App\Facades;

use Illuminate\Support\Facades\Schema;

abstract class ModelsManager {
	public $model = App\Model::class;

	public function validate($data, $isValidate = true) {
		return false;
	}

	public function setData($model, $data) {
		$model->fill($data);
		$model->save();

		return $model;
	}

	public function add($data) {
		$model = new $this->model;
		return $this->setData($model, $data);
	}

	public function hasSoftDelete() {
		return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->model));
	}

	public function update($data) {
		$model = $this->get($data['id'], true);
		return $this->setData($model, $data);
	}

	public function remove($id) {
		if(is_object($id)) {
			$id->delete();
		} else {
			$this->model::destroy($id);
		}
	}

	public function get($id, $removed = false) {
		if($removed && $this->hasSoftDelete()) {
			return $this->model::withTrashed()->find($id);
		} else {
			return $this->model::find($id);
		}
	}

	abstract public function getList($properties);

	public function getEmpty() {
		$emptyModel = new $this->model;
		$columns = Schema::getColumnListing($emptyModel->getTable());
		return array_fill_keys($columns, null);
	}
}