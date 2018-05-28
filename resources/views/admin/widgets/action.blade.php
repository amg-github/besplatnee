@can($content['action'], $item)
	@if(in_array($content['action'], ['view', 'edit', 'remove']))
    	<a class="action" href="{{ route('admin.' . $content['action'], ['id' => $item->id, 'model' => $model]) }}" title="@lang('admin.' . $content['action'])" data-action="{{ $content['action'] }}">{!! $content['icon'] !!}</a>
    @else 
    	<a class="action" href="{{ route('admin.inline' , ['id' => $item->id, 'model' => $model, 'action' => $content['action']]) }}" title="@lang('admin.' . $content['action'])" data-action="{{ $content['action'] }}">{!! $content['icon'] !!}</a>
    @endif
@endcan