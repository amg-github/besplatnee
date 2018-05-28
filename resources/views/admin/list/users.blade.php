@extends('admin.dashboard')

@section('admincontent')
<ul class="admin-submenu">
	<li class="{{ $filter == 'admins' ? 'active' : '' }}"><a href="{{ route('admin.list', ['model' => 'users', 'filter' => 'admins']) }}" class="{{ $filter == 'admins' ? 'active' : '' }}">Руководство</a></li>
	<li class="{{$filter == 'all' ? 'active' : '' }}"><a href="{{ route('admin.list', ['model' => 'users', 'filter' => 'all']) }}" class="{{ $filter == 'all' ? 'active' : '' }}">Все пользователи</a></li>
</ul>

<div class="post-list-wrapper clearfix">
	<div class="post-list-head-title text-center clearfix">
        <div class="block-left" style="width: 10%">Действия</div>
        <div class="block-left" style="width: 50%">Ф.И.О</div>
        <div class="block-left" style="width: 20%">Доступные города</div>
        <div class="block-left" style="width: 20%">Прочее</div>
    </div>
	@forelse($users as $user) 
        @include('admin.list.user')
    @empty
        <p><center>Не найдено пользователей</center></p>
    @endforelse

    {{ $users->links('elements.pagination') }}
</div>

@endsection