@if($item->isSuperAdmin())
    Доступ ко всем городам
@else
    @if($item->admin_cities)
        @foreach($item->admin_cities as $city) 
            {{ $city }}<br>
        @endforeach
    @endif
@endif