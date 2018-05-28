<?php 
namespace App\Presenters\GeoObject;

class CityPresenter extends Presenter {

	public function present($data) {
		parent::present($data);

		$this->presentRegion();
		$this->presentCountry();

		return $this->present;
	}

	public function presentRegion() {
		$this->present->region = (new RegionPresenter)->present($this->data->parent);
	}

	public function presentCountry() {
		$this->present->country = $this->present->region->country;
	}
}