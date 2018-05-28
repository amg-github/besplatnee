@php
	if(isset($name_index) && $name_index != '') {
		$inputname = $name . '[' . $name_index . ']';
		$name = $name . '.' . $name_index;
	} else {
		$inputname = $name;
	}
@endphp
<input type="color" style="background: #fff; border: none;padding: 0;" class="col-xs-12" placeholder="{{ $help }}" name="{{ $inputname }}" value="{{ request()->input($name) }}{{ (isset($value) ? $value : '')}}">