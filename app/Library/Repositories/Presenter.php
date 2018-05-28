<?php 
namespace App\Library\Repositories;

class Presenter {
	protected $data;
	protected $present;

	public function present($data) {
		$this->data = $data;
		$this->present = new \stdClass;

		return $this->present;
	}
}