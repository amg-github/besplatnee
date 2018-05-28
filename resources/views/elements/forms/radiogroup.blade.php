@foreach ($values as $value => $title)
	@include('elements.forms.radio', ['value' => $value, 'title' => $title, 'checked' => (isset($checked) && $checked == $value ? $checked : '') ])
@endforeach