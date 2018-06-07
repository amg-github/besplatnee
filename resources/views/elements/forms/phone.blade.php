<?php 
	$nameChunks = explode('.', $name);
	$inputname = $nameChunks[0];
	for($i = 1; $i < count($nameChunks); $i++) {
		$inputname .= '[' . $nameChunks[$i] . ']';
	}

	$callbackName = implode('_', $nameChunks);
?>
<div class="col-xs-3" style="padding-left: 0; padding-right: 0; width: 75px">
	<div class="dropdown-list" data-default="{{ \Config::get('area')->country()->phone_code }}" data-name="prefix_{{ $callbackName}}" onchange="onChange_{{ $callbackName }}()">
		@foreach(\App\GeoObject::countries()->get() as $country)
		<div class="dropdown-item" data-value="{{ $country->getProps()['phone_code'] }}" data-image="{{ asset('img/countries/' . $country->getProps()['flag_image']) }}" {{ $country->id == \Config::get('area')->country()->id ? 'data-checked' : '' }}>
			{{ $country->getProps()['phone_code'] }}
		</div>
		@endforeach
	</div>
</div>
<input type="text" class="col-xs-9 input-numeric" placeholder="{{ $help }}" name="postfix_{{ $callbackName }}" oninput="onChange_{{ $callbackName }}()" style="width: calc(100% - 75px)">
<input type="hidden" name="{{ $inputname }}" value="{{ request()->input($name) }}" >

<script type="text/javascript">
	function onChange_{{ $callbackName }} () {
		var prefix = $('[name="prefix_{{ $callbackName }}"]').val();
		var postfix = SITE.utils.numberFormat($('[name="postfix_{{ $callbackName }}"]').val());
		
		$('[name="{{ $inputname }}"]').val(prefix + postfix);
	}

	@if(request()->has($name))
	document.addEventListener("DOMContentLoaded", function () {
		//var postfix = $('[name="prefix_{{ $inputname }}"]').val();
		//var prefix = "{{ request()->input($name) }}".replace(postfix, '');
		var val = "{{ request()->input($name) }}";
		var postfix = "{{ request()->input($name) }}".replace("{{ request()->input('prefix_' . $name, '+7') }}", '');
		$('[name="postfix_{{ $callbackName }}"]').val(postfix);
		$('[name="{{ $inputname }}"]').val(val);
	});
	@endif
</script>