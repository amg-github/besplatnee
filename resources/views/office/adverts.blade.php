@extends('office.dashboard')

@section('officecontent')
	<div class="post-list-wrapper clearfix">
		<div class="post-list-head-title text-center clearfix">
            <div class="block-left">Действия</div>
            <div class="block-left">Объявления</div>
            <div class="block-left">Комментарии<br><span>(Данную информацию видите только вы)</span>
            </div>
        </div>
		@forelse($adverts as $advert) 
	        @include('advert.previews.table')
	    @empty
	        <p><center>У вас нет объявлений</center></p>
	    @endforelse
	</div>
@endsection
