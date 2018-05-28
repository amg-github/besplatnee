<div class="widget-switcher" data-state="{{ $item->{$content['subject']} ? '1' : '0' }}" data-field="{{ $content['subject'] }}">
	<div class="on">
		<a href="" title="{{ $content['on']['alt'] }}">{!! $content['on']['icon'] !!}{{ $content['on']['title'] }}</a>
	</div>
	<div class="off">
		<a href="" title="{{ $content['off']['alt'] }}">{!! $content['off']['icon'] !!}{{ $content['off']['title'] }}</a>
	</div>
</div>