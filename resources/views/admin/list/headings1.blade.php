@extends('admin.dashboard')



<ul>
    @foreach($categories as $category)
        <li>{{ $category['name'] }}</li>
        @if(isset($category['children']))
            @include('viewname', $category['children'])
        @endif
    @endforeach
</ul>

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
    <?printParent($parents)?>
    <!-- @forelse($parents as $parent) -->
        <!-- <div class="admin-model-list-item {{ $model }}-item parent with-childs" data-id="{{ $parent->id }}" style="background-color: #ECEFF1; cursor: pointer;">
            <div class="admin-model-list-item-column headings-item-actions" style="width: 10%">
                <a class="action" href="http://besplatnee.net/admin/headings/edit/{{$parent->id}}" title="Редактировать" data-action="edit"><img src="http://besplatnee.net/img/post-params/5.png" title=""></a>
                <a class="action" href="http://besplatnee.net/admin/headings/remove/{{$parent->id}}" title="admin.remove" data-action="remove"><img src="http://besplatnee.net/img/post-params/3.png" title=""></a>
            </div>
            <div class="admin-model-list-item-column headings-item-name" style="width: 10%; text-align: center;">
                <div class="">{{ $parent->name }}</div>                            
                <small class="">{{ (isset($parents[$parent->id]) ? 'Подкатегорий: '.count($parents[$parent->id]) : '') }}</small>                            
            </div>
            <div class="admin-model-list-item-column headings-item-created_at" style="width: 10%">
                <div class="">{{ $parent->parent_id }}</div>                            
            </div>
            <div class="admin-model-list-item-column headings-item-sortindex" style="width: 5%">
                <div class="">{{ $parent->sortindex }}</div>                            
            </div>
        </div>
        @if(isset($parents[$parent->id]))
            @foreach($parents[$parent->id] as $pkey => $item)
                <div class="admin-model-list-item {{ $model }}-item child" style="display: none;" data-id="{{ $parent->id }}">
                    <div class="admin-model-list-item-column headings-item-actions" style="width: 10%">
                        <a class="action" href="http://besplatnee.net/admin/headings/edit/{{$item->id}}" title="Редактировать" data-action="edit"><img src="http://besplatnee.net/img/post-params/5.png" title=""></a>
                        <a class="action" href="http://besplatnee.net/admin/headings/remove/{{$item->id}}" title="admin.remove" data-action="remove"><img src="http://besplatnee.net/img/post-params/3.png" title=""></a>
                    </div>
                    <div class="admin-model-list-item-column headings-item-name" style="width: 10%; ">
                        <div class="">{{ $item->name }}</div>                            
                    </div>
                    <div class="admin-model-list-item-column headings-item-created_at" style="width: 10%">
                        <div class="">{{ $item->created_at }}</div>                            
                    </div>
                    <div class="admin-model-list-item-column headings-item-sortindex" style="width: 5%">
                        <div class="">{{ $item->sortindex }}</div>                            
                    </div>
                </div>
            @endforeach
        @endif -->
       <!--  @if (isset($items[$pkey]))

            <div class="admin-model-list-item {{ $model }}-item parent with-childs" data-id="{{ $pkey }}" style="background-color: #ECEFF1; cursor: pointer;">
                <div class="admin-model-list-item-column headings-item-actions" style="width: 10%">
                    <a class="action" href="http://besplatnee.net/admin/headings/edit/{{$pkey}}" title="Редактировать" data-action="edit"><img src="http://besplatnee.net/img/post-params/5.png" title=""></a>
                    <a class="action" href="http://besplatnee.net/admin/headings/remove/{{$pkey}}" title="admin.remove" data-action="remove"><img src="http://besplatnee.net/img/post-params/3.png" title=""></a>
                </div>
                <div class="admin-model-list-item-column headings-item-name" style="width: 10%; text-align: center;">
                    <div class="">{{ $items[$pkey]->name }}</div>                            
                    <small class="">Подкатегорий: {{ count($parent) }}</small>                            
                </div>
                <div class="admin-model-list-item-column headings-item-created_at" style="width: 10%">
                    <div class="">{{ $items[$pkey]->parent_id }}</div>                            
                </div>
                <div class="admin-model-list-item-column headings-item-sortindex" style="width: 5%">
                    <div class="">{{ $items[$pkey]->sortindex }}</div>                            
                </div>
            </div>
            @foreach($parent as $item)
                @if (!empty($item->parent_id))
                    <div class="admin-model-list-item {{ $model }}-item child" style="display: none;" data-id="{{ $pkey }}">
                        <div class="admin-model-list-item-column headings-item-actions" style="width: 10%">
                            <a class="action" href="http://besplatnee.net/admin/headings/edit/{{$item->id}}" title="Редактировать" data-action="edit"><img src="http://besplatnee.net/img/post-params/5.png" title=""></a>
                            <a class="action" href="http://besplatnee.net/admin/headings/remove/{{$item->id}}" title="admin.remove" data-action="remove"><img src="http://besplatnee.net/img/post-params/3.png" title=""></a>
                        </div>
                        <div class="admin-model-list-item-column headings-item-name" style="width: 10%; ">
                            <div class="">{{ $item->name }}</div>                            
                        </div>
                        <div class="admin-model-list-item-column headings-item-created_at" style="width: 10%">
                            <div class="">{{ $item->created_at }}</div>                            
                        </div>
                        <div class="admin-model-list-item-column headings-item-sortindex" style="width: 5%">
                            <div class="">{{ $item->sortindex }}</div>                            
                        </div>
                    </div>
                @endif
            @endforeach
        @elseif($pkey == "")
            @foreach($parent as $item)
                <div class="admin-model-list-item {{ $model }}-item parent" data-id="{{ $item->id }}" style="background-color: #ECEFF1;">
                    <div class="admin-model-list-item-column headings-item-actions" style="width: 10%">
                        <a class="action" href="http://besplatnee.net/admin/headings/edit/{{$item->id }}" title="Редактировать" data-action="edit"><img src="http://besplatnee.net/img/post-params/5.png" title=""></a>
                        <a class="action" href="http://besplatnee.net/admin/headings/remove/{{$item->id }}" title="admin.remove" data-action="remove"><img src="http://besplatnee.net/img/post-params/3.png" title=""></a>
                    </div>
                    <div class="admin-model-list-item-column headings-item-name" style="width: 10%; text-align: center;">
                        <div class="">{{ $item->name }}</div>                            
                    </div>
                    <div class="admin-model-list-item-column headings-item-created_at" style="width: 10%">
                        <div class="">{{ $item->created_at }}</div>                            
                    </div>
                    <div class="admin-model-list-item-column headings-item-sortindex" style="width: 5%">
                        <div class="">{{ $item->sortindex }}</div>                            
                    </div>
                </div>
            @endforeach
        @endif -->
    <!-- @empty
    <p><center>@lang('admin.items.empty')</center></p>
    @endforelse -->
</div>

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function () {
        $('.admin-model-list-item.parent.with-childs').on('click', function() {
            $('.admin-model-list-item.child[data-id="'+$(this).data('id')+'"]').toggle('fade');
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