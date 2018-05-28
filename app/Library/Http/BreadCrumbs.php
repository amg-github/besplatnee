<?php 
namespace App\Library\Http;

trait BreadCrumbs {
	protected $bread_crumbs = [];

	public function addBreadCrumb($label, $url, $title = '') {
		$this->bread_crumbs[] = [
			'label' => $label,
			'url' => $url,
			'title' => $title,
		];
	}

	public function getBreadCrumbs() {
		return collect($this->bread_crumbs);
	}

	public function breadcrumbs($label = null, $url = null, $title = '') {
		if(empty($label)) {
			return $this->getBreadCrumbs();
		} else {
			$this->addBreadCrumb($label, $url, $title);
		}
	}
}