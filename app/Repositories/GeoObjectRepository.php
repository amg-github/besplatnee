<?php 
namespace App\Repositories;

use App\Library\Repositories\Repository;
use App\Models\GeoObject;

class GeoObjectRepository extends Repository {

	public function model() {
		return GeoObject::class;
	}

	public function findByAlias($alias) {
		$geo_objects = $this->findWhere([
			'alias' => $alias,
			'active' => true,
		]);

		return $geo_objects->count() > 0 ? $geo_objects->first() : null;
	}

	public function findCityByAlias($alias) {
		return $this->with(['parent', 'parent.parent'])->findWhere([
			'type' => 'city',
			'active' => true,
			'alias' => $alias,
		]);
	}

}