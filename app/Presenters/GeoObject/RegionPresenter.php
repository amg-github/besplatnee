<?php 
namespace App\Presenters\GeoObject;

class RegionPresenter extends Presenter {

	public function present($data) {
		parent::present($data);

		$this->presentCountry();

		return $this->present;
	}

	public function presentCountry() {
		$this->present->country = (new CountryPresenter)->present($this->data->parent);
	}
}