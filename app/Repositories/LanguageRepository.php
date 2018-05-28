<?php 
namespace App\Repositories;

use App\Library\Repositories\Repository;
use App\Models\Language;

class LanguageRepository extends Repository {

	public $defaultLanguageId = 1;

	public function model() {
		return Language::class;
	}

	public function getFromSession() {
		$languageId = session()->get('settings.language_id', $this->defaultLanguageId);
		$language = $this->findWhere([
			'id' => $languageId,
			'active' => true,
		]);

		return $language->count() > 0 ? $language->first() : $this->defaultLangugeModel();
	}

	public function defaultLangugeModel() {
		return $language = new Language([
            'code' =>'en',
            'active' => true,
            'title' => 'English',
        ]);
	}

}