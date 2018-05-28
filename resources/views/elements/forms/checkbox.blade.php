<label for="{{ $name }}">
	<input type="hidden" name="{{ $name }}" value="0">
	<input id="{{ $name }}" type="checkbox" name="{{ $name }}" value="1" {{ request()->input($name, 0) == 1 ? 'checked' : '' }} {{ isset($checked) && $checked == 'true' ? 'checked' : '' }} />
	<span>{{ $title }}</span>
</label>