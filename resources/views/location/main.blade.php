@extends('layouts.app')

@section('leftsidebar')

@endsection



@section('content')
<div class="col-xs-12">
	<div class="row">
		<div class="country-flags owl-carousel owl-theme">
			@foreach(\App\GeoObject::countries()->get() as $country)
			<a class="country-flag" href="{{ $country->id }}">
				<img src="{{ asset('img/countries/' . $country->getProps()['flag_image']) }}" alt="{{ $country->nominative_local }}" title="{{ $country->nominative_local }}">
			</a>
			@endforeach
		</div>
	</div>

	<div class="row">
		<div class="country-firstletters owl-carousel owl-theme">
			
		</div>
	</div>

	<div class="country-cities row">
			
	</div>
</div>
@endsection