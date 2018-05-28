@extends('layouts.app')

@section('top.address', 'г.&nbsp;' . config('city')->name)
@section('top.logo.url', route('city', ['city_alias' => config('city')->alias]))
@section('adverts.url', route('city', ['city_alias' => config('city')->alias]))

@section('topbanners')
<div id="banners">
    <div class="banners-top clearfix">
        <div class="fw-container">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="banner-line">
                    @lang('site.desription.top', [
                        'city' => config('city')->name,
                        'for_city' => config('city')->genitive_name,
                        'in_city' =>  config('city')->accusative_name,
                    ])
                </div>
                @include('elements.banners.header')
            </div>
        </div>
    </div>
</div>
@endsection