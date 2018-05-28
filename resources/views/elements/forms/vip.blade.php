@php
	if(isset($name_index) && $name_index != '') {
		$inputname = $name . '[' . $name_index . ']';
		$name = $name . '.' . $name_index;
	} else {
		$inputname = $name;
	}
@endphp

{!! request()->has($name) ? '<span>Текущий номер: '.request()->$name.'</span>' : '' !!}	
<select class="col-xs-12"  id="{{ $id }}" name="{{ $inputname }}">

    <option {{ request()->has($name) ? '' : 'selected' }} value="">{{ $help }}</option>

    @foreach ($options as $option)
    	@include('elements.forms.option', ['option' => $option, 'params' => (isset($params) ? $params : '')])
    @endforeach

</select>