<?php 
namespace App\Repositories;

use App\Advert;

class AdvertRepository extends BaseRepository {

	public function model() {
		return Advert::class;
	}

}