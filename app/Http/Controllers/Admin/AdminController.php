<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends \App\Http\Controllers\Controller {

	public function groups() { return []; }
	public function filters() { return []; }
	public function columns() { return []; }
	public function fields() { return []; }
	public function actions() { return []; }

	public $groups = [];
	public $filters = [];
	public $columns = [];
	public $fields = [];
	public $actions = [];

    function __construct (\App\Facades\Besplatnee $besplatnee) {
    	parent::__construct($besplatnee);
    	$this->version = 'c';

    	$this->groups = $this->groups();
    	$this->filters = $this->filters();
    	$this->columns = $this->columns();
    	$this->fields = $this->fields();
    	$this->actions = $this->actions();

		$this->breadcrumbs('Управление', route('admin.dashboard'));
    }

	public function list(Request $request, $model) {
		$this->breadcrumbs(__('admin.headings.list.' . $model), route('admin.list', ['model' => $model]));

		$defaultGroup = $this->getDefaultGroup();
		$inputGroup = request()->input('group', $defaultGroup['name']);

		$group = $this->getGroup($inputGroup);
	}

	public function edit(Request $request, $model, $id) {
    	$this->version = 'a';
		$this->breadcrumbs(__('admin.headings.edit.' . $model), route('admin.edit', ['model' => $model, 'id' => $id]));
	}

	public function remove(Request $request, $model, $id) {}
	public function create(Request $request, $model) {
    	$this->version = 'a';
		$this->breadcrumbs(__('admin.headings.create.' . $model), route('admin.create', ['model' => $model]));
	}

	public function save(Request $request, $model, $id) {}

	public function getDefaultGroup() {
		foreach($this->groups as $group) {
			if($group['active']) {
				return $group;
			}
		}

		return count($this->groups) > 0 ? $this->groups[0] : [
			'name' => 'all',
			'title' => __('admin.groups.all'),
			'active' => true,
			'wheres' => [],
		];
	}

	public function getGroup($name = null) {
		if($name === null) {
			return $this->getDefaultGroup();
		}

		$result = [
			'name' => 'all',
			'title' => __('admin.groups.all'),
			'active' => true,
			'wheres' => [],
		];

		foreach($this->groups as $idx => $group) {
			if($group['name'] == $name) {
				$this->groups[$idx]['active'] = true;
				$result = $group;
			} else {
				$this->groups[$idx]['active'] = false;
			}
		}

		return $result;
	}

	public function getFilterWheres() {
		$wheres = [];

		foreach($this->filters as $filter) {
			$wheres[$filter['name']] = request()->input($filter['name'], isset($filter['default']) ? $filter['default'] : '');
		}

		return $wheres;
	}

	public function makeGroup($name, $title, $active = false, $wheres = [], $policies = []) {
		return [
			'name' => $name,
			'title' => __($title),
			'active' => $active,
			'wheres' => $wheres,
			'policies' => $policies,
		];
	}

}