<?php 

namespace App\Library\Repositories;

use Illuminate\Support\Facades\Validator as LaravelValidator;
use Illuminate\Http\Request;

class Validator {

	const CREATE_RULES = 'rules.create';
	const UPDATE_RULES = 'rules.update';

	public function getCreateRules() {
		return $this->rules()[Validator::CREATE_RULES];
	}

	public function getUpdateRules() {
		return $this->rules()[Validator::UPDATE_RULES];
	}

	public function getRulesByType($type) {
		return $this->rules()[$type];
	}

	public function getMessages() {
		return $this->messages();
	}

	public function rules() {
		return [
			Validator::CREATE_RULES => [],
			Validator::UPDATE_RULES => [],
		]
	}

	public function messages() {
		return [];
	}

	public function validateRequest(Request $request, $type = Validator::CREATE_RULES) {
		return $request->validate($this->getRulesByType($type), $this->getMessages());
	}

	public function validateData($data, $type = Validator::CREATE_RULES) {
		return LaravelValidator::make($data, $this->getRulesByType($type), $this->getMessages());
	}

	public function validate($data, $type = Validator::CREATE_RULES) {
		if($data instanceof Request) {
			return $this->validateRequest($data, $type);
		} else {
			return $this->validateData($data, $type);
		}
	}

}