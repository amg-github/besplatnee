@extends('admin.dashboard')





@section('admincontent')
<ul class="admin-submenu">
    @foreach($groups as $group)
    @if(!isset($group['policies']) || Auth::user()->checkPolicies($group['policies']))
    <li class="{{ $group['active'] ? 'active' : '' }}">
        <a href="{{ route('admin.list', ['model' => $model, 'group' => $group['name']]) }}" class="{{ $group['active'] ? 'active' : '' }}">{{ $group['title'] }}</a>
    </li>
    @endif
    @endforeach
</ul>

@if(isset($fastcreate))
<form action="{{ route('admin.create', ['model' => $model]) }}" method="post" class="admin-filters row">
    {{ csrf_field() }}
    @foreach($fastcreate as $field => $data)
    <div class="col-xs-4" style="height: 25px;font-size: 17px;line-height: 20px;">
        {{ $data['title'] }}
        <input type="{{ $data['type'] }}" name="{{ $field }}" style="display: inline-block;">
    </div>
    @endforeach
    <button class="col-xs-2 ymap-search">Добавить</button>&nbsp;
</form>
@endif

@if(count($filters) > 0)
<form action="" method="get" class="admin-filters row">
    @foreach($filters as $filter)
    <div id="admin-filter-{{ $model }}-{{ $filter['name'] }}" class="col-xs-{{ isset($filter['size']) ? $filter['size'] : 3 }} admin-filter-{{ $filter['name'] }}">
        @include('admin.filters.' . $filter['type'], $filter)
    </div>
    @endforeach
    <button class="col-xs-2 ymap-search">Фильтровать</button>&nbsp;
    <a class="col-xs-2" href="{{ route('admin.list', ['model' => $model]) }}"><button class="col-xs-12 ymap-search" type="button">Сбросить</button></a>
</form>
@endif
<div class="admin-model-list" data-model="{{ $model }}">
	<div class="admin-model-list-header">
        @foreach($columns as $column)
        <div class="admin-model-list-header-column" style="width: {{ $column['width'] }}">{!! $column['title'] !!}</div>
        @endforeach
    </div>
    @include('test', ['categories' => $parents, 'model' => $model])
</div>

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function () {
        $('.admin-model-list-item.parent').on('click', function() {
            $('.admin-model-list-item.child[data-id="'+$(this).data('parent-id')+'"]').toggle('fade');
        });
    });

</script>

@if(isset($actions) && count($actions) > 0)
<div class="col-xs-4 col-xs-offset-8">
    <form action="{{ route('admin.inline', ['action' => '#ACTION#', 'model' => $model, 'id' => 0]) }}" method="post" class="admin-actions row">
        {{ csrf_field() }}
        <select class="col-xs-8" style="height: 25px;font-size: 17px;line-height: 20px;" name="action">
            @foreach($actions as $action) 
            <option value="{{ $action['action'] }}">{{ $action['title'] }}</option> 
            @endforeach
        </select> 
        <div class="ids"></div>
        <button type="submit" class="col-xs-4 ymap-search">Выполнить</button>
    </form>
</div>

<script type="text/javascript">


    document.addEventListener("DOMContentLoaded", function () {
        $('.admin-actions button').on('click', function () {
            var $form = $(this).parent();
            var $contain = $form.find('.ids');
            var action = $form.find('[name="action"]').val();
            $contain.html('');

            $('.admin-model-list-item [name="ids[]"]:checked').each(function () {
                $contain.append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
                $form.attr('action', $form.attr('action').replace('#ACTION#', action));
            });
        });
    });


    

    
</script>
@endif




@endsection