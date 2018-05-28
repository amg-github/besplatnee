@foreach($categories as $category)
    @if(isset($category['children']))
        <div class="admin-model-list-item {{ $model }}-item parent {{ (empty($category['parent_id']) ? '' : 'child')}}" data-parent-id="{{ $category['id'] }}" data-id="{{ $category['parent_id'] }}" style="background-color: #ECEFF1; cursor: pointer; {{ (!empty($category['parent_id']) ? 'display: none;' : '')}}">
            <div class="admin-model-list-item-column headings-item-actions" style="width: 10%">
                    <a class="action" href="http://besplatnee.net/admin/headings/edit/{{$category['id']}}" title="Редактировать" data-action="edit"><img src="http://besplatnee.net/img/post-params/5.png" title=""></a>
                    <a class="action" href="http://besplatnee.net/admin/headings/remove/{{$category['id']}}" title="admin.remove" data-action="remove"><img src="http://besplatnee.net/img/post-params/3.png" title=""></a>
                </div>
                <div class="admin-model-list-item-column headings-item-name" style="width: 10%; text-align: center;">
                    <div class="">{{ $category['name'] }}</div>                            
                    <small class="">{{ (isset($category['children']) ? 'Подкатегорий: '.count($category['children']) : '') }}</small>                            
                </div>
                <div class="admin-model-list-item-column headings-item-created_at" style="width: 10%">
                    <div class="">{{ $category['created_at'] }}</div>                            
                </div>
                <div class="admin-model-list-item-column headings-item-sortindex" style="width: 5%">
                    <div class="">{{ $category['sortindex'] }}</div>                            
            </div>
        </div>
        @include('test', ['categories' => $category['children']])
    @else
        <div class="admin-model-list-item {{ $model }}-item child" data-id="{{ $category['parent_id'] }}" style="display: none;">
            <div class="admin-model-list-item-column headings-item-actions" style="width: 10%">
                    <a class="action" href="http://besplatnee.net/admin/headings/edit/{{$category['id']}}" title="Редактировать" data-action="edit"><img src="http://besplatnee.net/img/post-params/5.png" title=""></a>
                    <a class="action" href="http://besplatnee.net/admin/headings/remove/{{$category['id']}}" title="admin.remove" data-action="remove"><img src="http://besplatnee.net/img/post-params/3.png" title=""></a>
                </div>
                <div class="admin-model-list-item-column headings-item-name" style="width: 10%; text-align: center;">
                    <div class="">{{ $category['name'] }}</div>                            
                    <small class="">{{ (isset($category['children']) ? 'Подкатегорий: '.count($category['children']) : '') }}</small>                            
                </div>
                <div class="admin-model-list-item-column headings-item-created_at" style="width: 10%">
                    <div class="">{{ $category['created_at'] }}</div>                            
                </div>
                <div class="admin-model-list-item-column headings-item-sortindex" style="width: 5%">
                    <div class="">{{ $category['sortindex'] }}</div>                            
            </div>
        </div>
    @endif
@endforeach



