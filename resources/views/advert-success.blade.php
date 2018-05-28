@extends('layouts.page')

@section('pagecontent')
    <p>Ваше объявление успешно добавлено. <a href="{{ $advert->getHeadingUrl() }}">Показать расположение в газете</a></p>
@endsection