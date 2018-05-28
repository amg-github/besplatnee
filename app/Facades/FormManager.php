<?php 
namespace App\Facades;

class FormManager {
	protected $icons = null;
	protected $singleTags = [
		'input',
		'br',
		'hr',
		'img',
	];

	function __construct() {
		$this->icons = new IconManager($this);
	}

	public function icons() {
		return $this->icons;
	}

	public function make($tag, $attributes = [], $content = '', $styles = []) {
		$result = '<' . $tag . $this->makeAttribtes($attributes, $styles) . '>';
		if(!in_array($tag, $this->singleTags)) {
			$result .= $content . '</' . $tag . '>';
		}

		return $result;
	}

	public function makeAttribtes(array $attributes, array $styles) {
		$_attributes = [];

		if(count($styles) > 0) {
			$_styles = [];
			foreach($styles as $name => $value) {
				$_styles[] = $name . ':' . $value;
			}

			$attributes['style'] = implode(';', $_styles);
		}

		foreach($attributes as $name => $value) {
			$_attributes[] = $name . '="' . $value . '"';
		}

		return count($_attributes) > 0 ? ' ' . implode(' ', $_attributes) : '';
	}
}

class FormManagerComponent {
	protected $formManager = null;

	function __construct(FormManager $formManager) {
		$this->formManager =& $formManager;
	}
}

class IconManager extends FormManagerComponent {
	public function fontAwesome($name, $color = 'black', $size = '16px') {
		return $this->formManager->make('i', [
			'class' => 'fa fa-' . $name,
			'aria-hidden' => 'true',
		], '', [
			'font-size' => $size,
			'color' => $color,
		]);
	}

	public function image($url, $title = '', $attributes = []) {
		return $this->formManager->make('img', array_merge([
			'src' => $url,
			'title' => $title,
		], $attributes));
	}
}