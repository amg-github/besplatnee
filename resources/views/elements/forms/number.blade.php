@php
	if(isset($name_index) && $name_index != '') {
		$inputname = $name . '[' . $name_index . ']';
		$name = $name . '.' . $name_index;
	} else {
		$inputname = $name;
	}
@endphp
<input type="number" class="col-xs-12" placeholder="{{ $help }}" name="{{ $inputname }}" value="{{ request()->input($name) }}{{ (isset($value) ? $value : '')}}">