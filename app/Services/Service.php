<?php
namespace App\Services;

use Auth;

abstract class Service {

	protected $permissions = [];

	protected $model = Illuminate\Database\Eloquent\Model::class;

	private $request;

	protected $errors;

	function __construct(Request $request) {
		$this->request = $request;
		$this->permissions = collect($this->permissions);
		$this->errors = new Illuminate\Support\MessageBag;
	}

	public function run() {
		if($this->permissions->isNotEmpty()) {
			if(!Auth::check() || !Auth::user()->checkPolicies($this->permissions)) {
				return null;
			}
		}

		$this->initialize();
		if($this->fails()) { return null; }

		return $this->process();
	}

	public function initialize() {

	}

	abstract function process();

	public function fails() {
		return $this->errors->isNotEmpty();
	}

}