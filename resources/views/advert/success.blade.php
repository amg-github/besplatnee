@extends('layouts.page')

@section('pagecontent')
    <p>@lang('adverts.create.success') <a href="{{ $advert->getHeadingUrl() }}">@lang('adverts.view-in-wall')</a></p>
@endsection