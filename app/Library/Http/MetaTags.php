<?php 
namespace App\Library\Http;

interface IMetaTagType {
	const TITLE = 'meta_title';
	const DESCRIPTION = 'meta_description';
	const KEYWORDS = 'meta_keywords';
	const PAGETITLE = 'meta_pagetitle';
}

trait MetaTags {
	protected $meta_tags = [];

	public function metadata($name, $value = null) {
		if($value === null) {
			return $this->getMetaTag($name);
		} else {
			$this->setMetaTag($name, $value);
		}
	}

	public function header($value = null) {
		if($value === null) {
			return $this->getPageTitle();
		} else {
			$this->setPageTitle($value);
		}
	}

	public function title($value = null) {
		if($value === null) {
			return $this->getMetaTitle();
		} else {
			$this->setMetaTitle($value);
		}
	}

	public function description($value = null) {
		if($value === null) {
			return $this->getMetaDescription();
		} else {
			$this->setMetaDescription($value);
		}
	}

	public function keywords($value = null) {
		if($value === null) {
			return $this->getMetaKeywords();
		} else {
			$this->setMetaKeywords($value);
		}
	}

	public function setMetaTag($name, $value) {
		$this->meta_tags[$name] = $value;
	}

	public function getMetaTag($name, $default = '') {
		return isset($this->meta_tags[$name]) ? $this->meta_tags[$name] : $default;
	}

	public function setMetaTitle($value) {
		$this->setMetaTag(IMetaTagType::TITLE, $value);
	}

	public function getMetaTitle($default = '') {
		return $this->getMetaTag(IMetaTagType::TITLE, $default);
	}

	public function setMetaDescription($value) {
		$this->setMetaTag(IMetaTagType::DESCRIPTION, $value);
	}

	public function getMetaDescription($default = '') {
		return $this->getMetaTag(IMetaTagType::DESCRIPTION, $default);
	}

	public function setMetaKeywords($value) {
		$this->setMetaTag(IMetaTagType::KEYWORDS, $value);
	}

	public function getMetaKeywords($default = '') {
		return $this->getMetaTag(IMetaTagType::KEYWORDS, $default);
	}

	public function setPageTitle($value) {
		$this->setMetaTag(IMetaTagType::PAGETITLE, $value);
	}

	public function getPageTitle($default = '') {
		return $this->getMetaTag(IMetaTagType::PAGETITLE, $default);
	}

	public function getMetaTagTypes() {
		return [
			IMetaTagType::TITLE,
			IMetaTagType::DESCRIPTION,
			IMetaTagType::KEYWORDS,
			IMetaTagType::PAGETITLE,
		];
	}
}