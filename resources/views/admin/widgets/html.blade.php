@if(is_callable($content))
	{!! $content($item) !!}
@else
	{!! $content !!}
@endif