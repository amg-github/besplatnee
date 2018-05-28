@extends('admin.dashboard')

@section('admincontent')
<ul class="admin-submenu">
	<li class="{{ $filter == 'all' ? 'active' : '' }}"><a href="{{ route('admin.list', ['model' => 'banners', 'filter' => 'all']) }}" class="{{ $filter == 'all' ? 'active' : '' }}">Все</a></li>
</ul>

<div class="post-list-wrapper clearfix">
	<div class="post-list-head-title text-center clearfix">
        <div class="block-left" style="width: 10%">Статус</div>
        <div class="block-left" style="width: 10%">Действия</div>
        <div class="block-left" style="width: 20%">Реклама</div>
        <div class="block-left" style="width: 60%">Рекламный модуль</div>
    </div>
	@forelse($banners as $banner) 
        @include('admin.list.banner')
    @empty
        <p><center>Не найдено баннеров</center></p>
    @endforelse

    {{ $banners->links('elements.pagination') }}
</div>

@endsection