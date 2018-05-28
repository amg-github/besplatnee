@extends('layouts.app')

@section('bodyclass', 'ad-post')

@section('content')
    <div class="block-left b-content-wrapper" style="margin-left: 19%">

        <div class="ad-post-wrapper">

            <!-- Page title  -->

            <h1 class="page-title block-left">{{ $advert->name }}</h1>

            <!-- End page title -->



            <div class="ad-post-content">
                <div class="add-post-main-preview">
                @if($advert->medias()->where('type', 'image')->count() > 0)


                    <div class="ad-post-main-thumb">

                        <a class="preview-ad-post-thumb" href="{{ asset($advert->medias()->where('type', 'image')->first()->path) }}">

                            <img src="{{ asset($advert->medias()->where('type', 'image')->first()->path) }}" alt="">

                        </a>

                    </div>

                    <div class="ad-post-gallery owl-carousel owl-theme">
                    @foreach($advert->medias()->where('type', 'image')->get() as $image)
                        <div class="item">
                            @php($thumb = $advert->medias()
                                ->where('name', $image->name)
                                ->where('type', 'preview_180x160')
                                ->first())
                            <a href="{{ asset($image->path) }}">
                                <img src="{{ asset($thumb->path) }}" alt="">
                            </a>
                        </div>
                    @endforeach
                    @foreach($advert->medias()->where('type', 'video')->get() as $video)
                        <div class="item post-item-video">
                            <a href="https://www.youtube.com/watch?v={{ $video->path }}">
                                <img src="https://i1.ytimg.com/vi/{{ $video->path }}/default.jpg" alt="">
                            </a>
                        </div>
                    @endforeach
                    </div>

                @endif
                </div>
                <div class="clearfix"></div>


                <div class="ad-post-desc-wrapper">
                    @if($advert->show_phone)
                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3">@lang('adverts.phone')</div>

                            <div class="col-xs-9 text-medium-blue">{{ $advert->owner->phone }}</div>

                        </div>

                    </div>
                    @endif

                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3">@lang('adverts.heading')</div>

                            <div class="col-xs-9 text-medium-blue">
                                @if($advert->heading->parent)
                                <a style="display: inline" href="{{ $advert->heading->parent->getUrl() }}">@lang($advert->heading->parent->name)</a>&nbsp;/&nbsp;
                                @endif
                                <a style="display: inline" href="{{ $advert->heading->getUrl() }}">@lang($advert->heading->name)</a>
                            </div>

                        </div>

                    </div>

                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-offset-3 col-xs-9 ad-post-slogan">{{ $advert->main_phrase }}</div>

                            <!--<div class="col-xs-offset-3 col-xs-9 text-medium-blue">Машина в отличном состоянии, с документами</div>-->

                        </div>

                    </div>

                    @if($advert->cities->first())
                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3">@lang('adverts.city')</div>

                                <div class="col-xs-9 text-medium-blue"><a href="#">{{ $advert->cities->first()->getName() }}</a></div>

                        </div>

                    </div>
                    @endif

                    @foreach($advert->properties as $property)
                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3">@lang($property->title)</div>

                            <div class="col-xs-9 text-medium-blue">

                                @if($defaultValue = $property->values()->where('value', $property->pivot->value)->first()) 
                                    @lang($defaultValue->title)
                                @else
                                    @if($property->type == 'numeric' && $property->options['view'] == 'select')
                                        {{ trans_choice($property->options['select-value'], $property->pivot->value) }}
                                    @else
                                        {!! $property->pivot->value !!}
                                    @endif
                                @endif

                            </div>

                        </div>

                    </div>
                    @endforeach

                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3 text-small">@lang('adverts.information')</div>

                            <div class="col-xs-9">

                                {!! $advert->content !!}

                            </div>

                        </div>

                    </div>

                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3">@lang('adverts.price')</div>

                            <div class="col-xs-9 text-medium-blue">

                                {!! number_format($advert->price, 0, ',', ' ') !!}&nbsp;@lang('adverts.curency')

                            </div>

                        </div>

                    </div>

                    <div class="separation-inline-view"></div>

                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3">@lang('adverts.site')</div>

                            <div class="col-xs-9 text-medium-blue">{{ $advert->site_url ?? '-' }}</div>

                        </div>

                    </div>

                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3 text-small">@lang('adverts.extend_content')</div>

                            <div class="col-xs-9">

                                {!! $advert->extend_content !!}

                            </div>

                        </div>

                    </div>

                    <div class="ad-post-desc clearfix">

                        <div class="row">

                            <div class="col-xs-3">@lang('adverts.contact')</div>

                            <div class="col-xs-9 text-medium-blue">{{ $advert->fullname }}</div>

                        </div>

                    </div>

                </div>

                <div class="ad-post-location">
                    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
                    <script type="text/javascript">

                        window.myMap;

                        ymaps.ready(function () {

                            window.myMap = new ymaps.Map("ya-ad-location", {

                                center: [{{ $advert->latitude }}, {{ $advert->longitude }}],

                                zoom: 14

                            });

                            window.myMark = new ymaps.Placemark([{{ $advert->latitude }}, {{ $advert->longitude }}], {
                                iconContent: '{{ $advert->address }}'
                            }, {
                                preset: 'islands#nightStretchyIcon'
                            });

                            window.myMap.geoObjects.removeAll();
                            window.myMap.geoObjects.add(window.myMark);

                        });

                    </script>

                    <div id="ya-ad-location"></div>

                </div>

                <div class="add-post-params clearfix">

                    <div class="buttons block-right" style="margin-left: 12px;">

                        <a class="block-left add-create-next" href="{{ route('advert-success', ['id' => $advert->id]) }}"><span>@lang('site.phrases.complete')</span><i class="fa fa-angle-right" aria-hidden="true"></i></a>

                    </div>

                    <div class="buttons block-right">

                        <a class="block-left add-create-next" href="{{ route('advert-edit', ['id' => $advert->id]) }}"><i class="fa fa-angle-left" aria-hidden="true"></i>&nbsp;<span>@lang('adverts.edit')</span></a>

                    </div>

                </div>



            </div>



        </div>

    </div>
@endsection

@section('leftsidebar')
    @include('layouts.leftsidebar')
@endsection

@section('rightsidebar')
    @include('layouts.rightsidebar')
@endsection