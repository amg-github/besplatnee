@extends('admin.dashboard')

@section('admincontent')
<ul class="admin-submenu">
	<li class="{{ $filter == 'cities' ? 'active' : '' }}"><a href="{{ route('admin.list', ['model' => 'megaadverts', 'filter' => 'cities']) }}" class="{{ $filter == 'cities' ? 'active' : '' }}">Во всех городах</a></li>
	<li class="{{ $filter == 'city' ? 'active' : '' }}"><a href="{{ route('admin.list', ['model' => 'megaadverts', 'filter' => 'city']) }}" class="{{ $filter == 'city' ? 'active' : '' }}">Все в городе {{ \Config::get('area')->name }}</a></li>
</ul>

<form action="" method="get" class="admin-filters row">
    <div class="col-xs-3">
    @include('elements.forms.select', [
        'name' => 'heading_id',
        'title' => 'Фильтры',
        'input' => 'select',
        'help' => 'По рубрике',
        'desc' => '',
        'id' => 'categories',
        'options' => Besplatnee::headings()->getTreeArray(),
    ])
    </div>
    <button class="col-xs-2 ymap-search">Фильтровать</button>
</form>
<div class="post-list-wrapper clearfix">
	<div class="post-list-head-title text-center clearfix">
        <div class="block-left" style="width: 8%">Статус</div>
        <div class="block-left" style="width: 8%">Действия</div>
        <div class="block-left" style="width: 14%">Счет</div>
        <div class="block-left" style="width: 70%">Содержимое объявления</div>
    </div>
	@forelse($adverts as $advert) 
        @include('admin.list.megaadvert')
    @empty
        <p><center>Не найдено объявлений</center></p>
    @endforelse

    {{ $adverts->links('elements.pagination') }}
</div>

@endsection