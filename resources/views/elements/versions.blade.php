<div class="version-sites">
    <span>Вид</span>
    <a href="{{ isset($category) ? $category->getUrl() : route('city', ['city_alias' => \Config::get('area')->alias]) }}?view-mode=a"{!! $version == 'a' ? ' class="active"' : '' !!}><i class="fa fa-mobile" aria-hidden="true"></i><span>Адаптивный</span></a>
    <a href="{{ isset($category) ? $category->getUrl() : route('city', ['city_alias' => \Config::get('area')->alias]) }}?view-mode=b"{!! $version == 'b' ? ' class="active"' : '' !!}><i class="fa fa-newspaper-o" aria-hidden="true"></i><span>Текстовый</span></a>
    <a href="{{ isset($category) ? $category->getUrl() : route('city', ['city_alias' => \Config::get('area')->alias]) }}?view-mode=c"{!! $version == 'c' ? ' class="active"' : '' !!}><i class="fa fa-table" aria-hidden="true"></i><span>Таблица</span></a>
    <a href="{{ isset($category) ? $category->getUrl() : route('city', ['city_alias' => \Config::get('area')->alias]) }}?view-mode=d"{!! $version == 'd' ? ' class="active"' : '' !!}><i class="fa fa-map-o" aria-hidden="true"></i><span>Карта</span></a>
    <a href="{{ isset($category) ? $category->getUrl() : route('city', ['city_alias' => \Config::get('area')->alias]) }}?view-mode=e"{!! $version == 'e' ? ' class="active"' : '' !!}><i class="fa fa-file-image-o" aria-hidden="true"></i><span>Картинки</span></a>
</div>