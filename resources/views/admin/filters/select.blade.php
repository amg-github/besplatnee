<select name="{{ $filter['name'] }}{{ $filter['multiple'] ? '[]' : '' }}" style="display: block;width: 100%">
	<option value="">{{ $filter['title'] }}</option>
	@foreach($filter['values'] as $value)
		<option class="{{ $value['isroot'] ? 'root' : '' }}" value="{{ $value['value'] }}" {{ $value['active'] ? 'selected' : '' }}>{{ $value['isroot'] ? '' : '--' }}{{ $value['title'] }}</option>
	@endforeach
</select>