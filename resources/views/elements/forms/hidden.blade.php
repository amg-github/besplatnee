<?php 
	$nameChunks = explode('.', $name);
	$inputname = $nameChunks[0];
	for($i = 1; $i < count($nameChunks); $i++) {
		$inputname .= '[' . $nameChunks[$i] . ']';
	}
?>
<p>{{ empty($value) ? request()->input($name) : $value }}</p>
<input type="hidden" name="{{ $inputname }}" value="{{ empty($value) ? request()->input($name) : $value }}">