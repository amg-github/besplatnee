<?php 
namespace App\Library\Repositories;

use Illuminate\Database\Eloquent\Model;

class Mapper {
	public function map($data) {
		return (new $this->model())->fill($data);
	}

	public function model() {
		return Model::class;
	}
}