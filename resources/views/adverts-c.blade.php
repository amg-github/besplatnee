@extends('layouts.app')

@section('bodyclass', 'ad-post')

@section('content')
	<div class="c-content-wrapper">
        <!-- Banner outer page -->
        <div class="banner-wrapper">
            <div class="banner-two-item clearfix">
                <div class="block-left col-xs-6">
                    <div class="row">
                        <a href="#">
                            <img src="img/banner-list/14.jpg" alt="">
                        </a>
                    </div>
                </div>
                <div class="block-left col-xs-6">
                    <div class="row">
                        <a href="#">
                            <img src="img/banner-list/13.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End banner outer page -->


        @include('layouts.versions')

        <!-- Post list -->
        <div class="posts-wrapper">
            <!-- Page title  -->
            <h1 class="page-title">{{ $pagetitle }}</h1>
            <!-- End page title -->

            <!-- Render result module -->
            <div class="result-wrapper">

                <div class="post-list-wrapper clearfix">

                    <div class="post-list-head-title text-center clearfix">
                        <div class="block-left">Действия</div>
                        <div class="block-left">Объявления</div>
                        <div class="block-left">Комментарии<br><span>(Данную информацию видите только вы)</span>
                        </div>
                    </div>

                    @foreach($adverts as $advert)
                    <div class="post-item-wrapper">
                        <div class="post-item-wrap clearfix">
                            <div class="post-item-params block-left">
                                <ul class="smart-settings-post-user">
                                    <li class="user-is-auth"><a href=""><img src="img/post-params/5.png" alt=""></a></li>
                                    <li><a href=""><img src="img/post-params/1.png" alt=""></a></li>
                                    <li><a href=""><img src="img/post-params/2.png" alt=""></a></li>
                                    <li><a href=""><img src="img/post-params/3.png" alt=""></a></li>
                                    <li><a href=""><img src="img/post-params/4.png" alt=""></a></li>
                                </ul>
                            </div>

                            <div class="post-item-content block-left">
                                <div class="post-item-title block-left"><a href="{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->name }}</a>
                                </div>
                                <div class="post-item-price block-right">{{ number_format($advert->getProperty('price', 0), 0, ',', ' ') }} руб.</div>

                                <div class="clearfix"></div>
                                <div class="post-item-phone block-left">Тел.:
                                    <span>{{ $advert->phone->phone }}</span></div>
                                <div class="post-item-desc-wrapper block-right">
                                    <div class="post-item-subtitle">{!! $advert->main_phrase !!}</div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="post-item-params">
                                    <div class="post-item-date">{{ Carbon\Carbon::parse($advert->created_at)->format('d.m.Y') }}</div>
                                </div>

                                <div class="post-item-desc-wrapper">
                                    <div class="post-item-desc">{!! str_limit($advert->content, 100, '...') !!}</div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="post-item-thumb-wrapper block-left">
                                	@foreach($advert->medias()->where('type', 'image')->get() as $media)
                                        <div class="item block-left"><img src="{{ asset($media->path) }}" alt="" style="height: 33px; width: auto;"></div>
                                    @endforeach
                                </div>
                                <div class="post-item-desc-wrapper block-right col-xs-7 row">
                                    <div class="post-item-tags text-right">
                                        <a href="{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->name }}</a>, <a href="{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->main_phrase }}</a>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="post-item-link block-right">
                                    <a href="{{ route('advert', ['id' => $advert->id]) }}">Подробнее</a><i class="fa fa-angle-right"
                                                                aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="post-item-comment block-left">...</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @include('layouts.pagination')

            </div>

            <!-- End render result module -->

        </div>
        <!-- End post list -->
    </div>

    <!-- End render content -->
@endsection

@section('leftsidebar')
    
@endsection

@section('rightsidebar')
   
@endsection