@extends('office.dashboard')

@section('officecontent')
	@forelse($sites as $site)
		<div>
			<a href="{{ route('partner', ['allias' => $site->allias]) }}">{{ $site->mainpage->name }}</a>
		</div>
	@empty
		<p>Список пуст</p>
	@endforelse
@endsection