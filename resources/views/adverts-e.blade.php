@extends('layouts.app')

@section('bodyclass', 'main-page')

@section('content')

@endsection

@section('leftsidebar')
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

    <!-- Post list -->
    <div class="posts-wrapper">
    	@include('layouts.versions')
        <!-- Page title  -->
        <h1 class="page-title">{{ $pagetitle }}</h1>
        <!-- End page title -->

        <!-- Render result module -->
        <div class="result-wrapper">
        	<div class="post-list-wrapper clearfix">
        		@foreach ($adverts as $advert)
        		<div class="post-item-wrapper">
                    <div class="post-item-thumb">
                        <div class="post-item-count-wrapper">{{ $advert->clickcount }}</div>
                        <div class="owl-posts owl-carousel owl-theme">
                        	@forelse($advert->medias()->where('type', 'image')->get() as $media)
                            	<div class="item"><img src="{{ asset($media->path) }}" alt=""></div>
                            @empty
                            	<div class="item"><img src="img/no_image.png" alt=""></div>
                            @endforelse
                        </div>
                    </div>
                    <div class="post-item-content">
                        <div class="post-item-params clearfix">
                            <ul class="smart-settings-post-user block-left">
                                <li><a href=""><img src="img/post-params/1.png" alt=""></a></li>
                                <li><a href=""><img src="img/post-params/2.png" alt=""></a></li>
                                <li><a href=""><img src="img/post-params/3.png" alt=""></a></li>
                                <li><a href=""><img src="img/post-params/4.png" alt=""></a></li>
                            </ul>
                            <div class="post-item-date block-right">{{ Carbon\Carbon::parse($advert->created_at)->format('d.m.Y') }}</div>
                        </div>
                        <div class="post-item-title"><a href="{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->name }}</a></div>
                        <div class="post-item-price">{{ number_format($advert->getProperty('price', 0), 0, ',', ' ') }} руб.</div>
                        <div class="post-item-desc-wrapper">
                            <div class="post-item-subtitle">{!! $advert->main_phrase !!}</div>
                            <div class="post-item-desc">{!! str_limit($advert->content, 100, '...') !!}</div>
                            <div class="post-item-phone">Тел.: <span>{{ $advert->phone->phone }}</span></div>
                            <div class="post-item-tags">
                                <a href="{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->name }}</a>, <a href="#{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->main_phrase }}</a>
                            </div>
                        </div>
                        <div class="post-item-link">
                            <a href="{{ route('advert', ['id' => $advert->id]) }}">Подробнее</a><i class="fa fa-angle-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                @endforeach
        	</div>
        	@include('layouts.pagination')
        </div>
    </div>
@endsection

@section('rightsidebar')
    
@endsection