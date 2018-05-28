<?php 
namespace App\Library\Http;

interface IViewModes {
	const ADAPTIVE = 'adaptive';
	const GALLERY = 'gallery';
	const MAP = 'map';
	const TABLE = 'table';
	const TEXT = 'text';
}

trait ViewModes {
	protected $currentViewMode = IViewMode::ADAPTIVE;
	protected $session_key = 'site_view_mode';
	protected $request_key = 'view-mode';

	public function viewModes() {
		return [
			IViewVersions::ADAPTIVE,
			IViewVersions::GALLERY,
			IViewVersions::MAP,
			IViewVersions::TABLE,
			IViewVersions::TEXT,
		];
	}

	public function setViewMode($version) {
		if(collect($this->viewVersions())->)
		$this->currentViewMode = $version
	}

	public function getViewMode() {
		return $this->currentViewMode;
	}

	public function viewMode($version = null) {
		if($version === null) {
			return $this->getViewMode();
		} else {
			$this->setViewMode($version);
		}
	}

	public function initializeViewMode() {
		if(request()->has($this->request_key)) {
			session()->put(
				$this->session_key, 
				request()->input($this->request_key)
			);
		}

		$this->setViewMode(session()->get($this->session_key));
	}
}