<?php 
namespace App\Library\Repositories;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Repository {

	protected $_visible = [];
	protected $_hidden = [];
	protected $_searchable = [];
	protected $_builder = null;
	protected $_presenter = null;
	protected $_mapper = null;
	protected $_skiped_presenter = true;

	function __construct() {
		$this->clearQueryBuilder();
		$this->initializePresenter();
		$this->visible($this->_visible);
		$this->hidden($this->_hidden);
		$this->searchable($this->_searchable);
	}

	public function callMethod(string $method, array $arguments = [], $clearQueryBuilder = true) {
		$result = call_user_func_array([$this->_builder(), $method], $arguments);

		if($clearQueryBuilder) {
			$this->clearQueryBuilder();
		} else {
			$this->_builder = $result;
		}

		return $result;
	}

	public function clearQueryBuilder() {
		$this->_builder = $this->model();
	}

	public function initializePresenter() {
		$this->setPresenter($this->presenter());
	}

	public function initializeMapper() {
		$this->setMapper($this->mapper());
	}

	protected function processingResult($result) {
		//$result = $result->except($this->_hidden)->only($this->_visible);
		return $this->_skiped_presenter ? $result : $this->getPresenter()->present($result);
	}

	protected function processingResults(Collection $results) {
		// foreach($results as $idx => $result) {
		// 	$results[$idx] = $result->except($this->_hidden)->only($this->_visible);
		// }

		return $this->_skiped_presenter ? $results : $results->map([$this->getPresenter(), 'present']);
	}

	public function all(array $columns = ['*']) {
		$result = $this->callMethod('all', [$columns]);

		return $this->processingResults($result);
	}

	public function first(array $columns = ['*']) {
		$result = $this->callMethod('first', [$columns]);

		return $this->processingResult($result);
	}

	public function paginate(int $pageSize, array $columns = ['*']) {
		return $this->callMethod('paginate', [$pageSize, $columns]);
	}


	public function find(int $id, array $columns = ['*']) {
		return $this->findWhere(['id' => $id], $columns);
	}

	public function findByField(string $name, string $value, array $columns = ['*']) {
		return $this->findWhere([$name => $value], $columns);
	}

	public function findWhere($where, array $columns = ['*']) {
		$result = $this->callMethod('where', [$where])->get($columns);

		return $this->processingResults($result);
	}

	public function findWhereIn(string $field, array $values = ['*'], array $columns = ['*']) {
		$result = $this->callMethod('whereIn', [$field, $values])->get($columns);

		return $this->processingResults($result);
	}

	public function findWhereNotIn(string $field, array $values = ['*'], array $columns = ['*']) {
		$result = call_user_func_array([$this->model(), 'whereNotIn'], [$field, $values])->get($columns);

		return $this->processingResults($result);
	}

	public function create(array $attributes, bool $save = true) {
		if($save) {
			$model = call_user_func_array([$this->model(), 'create'], [$attributes]);
		} else {
			$modelClassName = $this->model();
			$model = new $modelClassName;
			$model->fill($attributes);
		}

		return $model;
	}

	public function update(array $attributes, int $id) {
		return $this->updateWhere($attributes, ['id' => $id]);
	}

	public function updateWhere(array $attributes, $where) {
		return $this->callMethod('where', [$where])->update($attributes);
	}

	public function updateOrCreate(array $attributes, array $values = []) {
		return $this->callMethod('updateOrCreate', [$attributes, $values]);
	}

	public function delete(int $id) {
		return $this->callMethod('delete', [$id]);
	}

	public function deleteWhere($where) {
		return $this->callMethod('where', [$where])->delete();
	}

	public function orderBy(string $column, string $direction = 'asc') {
		$this->callMethod('orderBy', [$column, $direction], false);

		return $this;
	}

	public function with(array $relations) {
		$this->callMethod('with', [$relations], false);

		return $this;
	}

	public function has(string $relation) {
		$this->callMethod('has', [$relation], !$returnQuery);

		return $this;
	}

	public function whereHas(string $relation, Closure $closure) {
		$this->callMethod('whereHas', [$relation, $closure], !$returnQuery);

		return $this;
	}

	public function hidden(array $fields) {
		$this->_hidden = collect($fields);
		return $this;
	}

	public function setHidden(array $fields) {
		$this->hidden($fields);
	}

	public function visible(array $fields) {
		$this->_visible = collect($fields);
		return $this;
	}

	public function setVisible(array $fields) {
		$this->visible($fields);
	}

	public function searchable(array $fields) {
		$this->_searchable = collect($fields);
		return $this;
	}

	public function setSearchable(array $fields) {
		return $this->searchable($fields);
	}

	public function getFieldsSearchable() {
		return $this;
	}

	public function getPresenter() {
		return $this->_presenter;
	}

	public function setPresenter($presenter = null) {
		if($presenter) {
			if(is_string($presenter)) {
				$presenter = new $presenter();
			}

			$this->_presenter = $presenter;
			$this->_skiped_presenter = false;
		} else {
			$this->_presenter = null;
			$this->_skiped_presenter = true;
		}
	}

	public function skipPresenter() {
		$this->_skiped_presenter = true;
		return $this;
	}

	public function present($data) {
		return $this->getPresenter()->present($data);
	}

	public function presentCollection(Collection $data) {
		return $data->map([$this->getPresenter(), 'present']);
	}

	public function presenter() {
		return null;
	}

	public function getMapper() {
		return $this->_mapper;
	}

	public function setMapper($mapper) {
		if(is_string($mapper)) {
			$mapper = new $mapper();
		}
		
		$this->_mapper = $mapper;
	}

	public function mapper() {
		return Mapper::class;
	}

	public function model() {
		return Model::class;
	}

	protected function _builder() {
		return $this->_builder;
	}

}