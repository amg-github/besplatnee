@php
	if(isset($name_index) && $name_index != '') {
		$inputname = $name . '[' . $name_index . ']';
		$name = $name . '.' . $name_index;
	} else {
		$inputname = $name;
	}
@endphp
<input type="text" {{ (isset($disabled) && $disabled == true) ? 'disabled' : ''}} class="col-xs-12 {{ isset($max_length) ? 'area-counter' : '' }}" placeholder="{{ $help }}" name="{{ $inputname }}" value="{{ isset($value) ? $value : request()->input($name) }}" data-max="{{ isset($max_length) ? $max_length : '999999999' }}">
@if(isset($max_length)) 
<div class="textarea-count">Осталось символов: <span>{{ $max_length - Str::length(request()->input($name, '')) }}</span></div>
@endif