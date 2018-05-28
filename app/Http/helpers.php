<?php 

if (!function_exists('service')) {

	function service($alias) {
		return app(collect(explode('.', 'app.services.' . $alias))->map(function ($item, $key) {
			Str::ucfirst($item);
		})->implode('\\'))->run();
	}

}

if (!function_exists('point_to_bracket')) {

	function point_to_bracket($name) {
		$chunks = explode('.', $name);
		$result = $chunks[0];

		for($i = 1; $i < count($chunks); $i++) {
			$result .= '[' . ($chunks[$i] == '*' ? '' : $chunks[$i]) . ']';
		}

		return $result;
	}

}

if (!function_exists('bracket_to_point')) {

	function bracket_to_point($name) {
		$chunks = array_map(function ($item) { 
			$item = trim($item, ']');
			return $item ?: '*'; 
		}, explode('[', $name));

		return implode('.', $chunks);
	}

}