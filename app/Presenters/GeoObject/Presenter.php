<?php 
namespace App\Presenters\GeoObject;

use App\Library\Repositories\Presenter as BasePresenter;

class Presenter extends BasePresenter {

	public function present($data) {
		parent::present($data);

		$this->presentDefaultFields();
		$this->presentNames();
		$this->presentAliases();
		$this->presentLocation();

		return $this->present;
	}

	public function presentDefaultFields() {
		$this->present->id = $this->data->id;
		$this->present->active = $this->data->active;
		$this->present->created_at = $this->data->created_at;
		$this->present->updated_at = $this->data->updated_at;
	}

	public function presentNames() {
		$this->present->names = new \stdClass;
		$this->present->names->name = $this->data->name;
		$this->present->names->fullname = $this->data->fullname;
		$this->present->names->genitive_name = $this->data->genitive_name;
		$this->present->names->accusative_name = $this->data->accusative_name;
		$this->present->names->dative_name = $this->data->dative_name;
		$this->present->names->ergative_name = $this->data->ergative_name;
	}

	public function presentAliases() {
		$this->present->aliases = new \stdClass;
		$this->present->aliases->alias = $this->data->name;
		$this->present->aliases->genitive_alias = $this->data->genitive_name;
		$this->present->aliases->accusative_alias = $this->data->accusative_name;
		$this->present->aliases->dative_alias = $this->data->dative_name;
		$this->present->aliases->ergative_alias = $this->data->ergative_name;
	}

	public function presentLocation() {
		$this->present->location = new \stdClass;
		$this->present->location->latitude = $this->data->latitude;
		$this->present->location->longitude = $this->data->longitude;
		$this->present->location->northeast = new \stdClass;
		$this->present->location->northeast->latitude = $this->data->latitude;
		$this->present->location->northeast->longitude = $this->data->longitude;
		$this->present->location->southwest = new \stdClass;
		$this->present->location->southwest->latitude = $this->data->latitude;
		$this->present->location->southwest->longitude = $this->data->longitude;
	}
}