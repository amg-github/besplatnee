<option 
	{!! isset($option['childs']) && count($option['childs']) > 0 ? 'class="root" disabled' : '' !!}
	@if(isset($params['value']))
		{{ $params['value'] == $option['value'] ? 'selected' : '' }}
	@else
		{{ request()->input($name) == $option['value'] ? 'selected' : '' }}
	@endif
	value="{{ $option['value'] }}">@lang($option['title'])</option>
@if(isset($option['childs']))
	@foreach ($option['childs'] as $child) 
		@include('elements.forms.option', ['option' => $child])
	@endforeach
@endif