@extends('layouts.app')

@section('bodyclass', 'version-b')

@section('content')
	<div class="block-left b-content-wrapper">

	    <!-- Banner outer page -->

	    <div class="banner-wrapper">

	        <div class="banner-two-item clearfix">

	            <div class="block-left col-xs-12">

	                <div class="row">

	                    <a href="#">

	                        <img src="img/banner-list/14.jpg" alt="">

	                    </a>

	                </div>

	            </div>

	            <div class="block-left col-xs-12">

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



	            <!--<div class="sorting-wrapper-params">

	                <span>Сортировать</span>

	                <form action="" class="sorting-form">

	                    <label class="css-label" for="sort-date">

	                        <input id="sort-date" class="checkbox hidden" type="checkbox">

	                        <span class="checkbox-custom hidden"></span>

	                        <span>Дата</span>

	                    </label>

	                    <label class="css-label" for="sort-price">

	                        <input id="sort-price" class="checkbox hidden" type="checkbox">

	                        <span class="checkbox-custom hidden"></span>

	                        <span>Цена</span>

	                    </label>

	                    <label class="css-label" for="sort-year">

	                        <input id="sort-year" class="checkbox hidden" type="checkbox">

	                        <span class="checkbox-custom hidden"></span>

	                        <span>Год выпуска</span>

	                    </label>

	                    <label class="css-label" for="sort-mileage">

	                        <input id="sort-mileage" class="checkbox hidden" type="checkbox">

	                        <span class="checkbox-custom hidden"></span>

	                        <span>Пробег</span>

	                    </label>

	                </form>

	            </div>-->

	            <div class="post-list-wrapper clearfix">
	        	@foreach ($adverts as $advert)

	                <div class="post-item-wrapper">

	                    <div class="post-item-thumb">

	                        <div class="post-item-count-wrapper">2</div>

	                        <div class="owl-posts owl-carousel owl-theme">

	                        	@forelse($advert->medias()->where('type', 'image')->get() as $media)
	                        		<div class="item"><img src="{{ asset($media->path) }}" alt=""></div>
	                        	@empty
	                        		<div class="item"><img src="img/no_image.png" alt=""></div>
	                        	@endforelse

	                        </div>

	                    </div>

	                    <div class="post-item-content">

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

	                            <div class="post-item-date">{{ Carbon\Carbon::parse($advert->created_at)->format('d-m-Y') }}</div>

	                        </div>



	                        <div class="post-item-desc-wrapper">

	                            <div class="post-item-desc">{!! str_limit($advert->content, 100, '...') !!}</div>

	                        </div>



	                        <div class="clearfix"></div>

	                        <div class="post-item-params block-left">

	                            <ul class="smart-settings-post-user block-left">

	                                <li><a href=""><img src="img/post-params/1.png" alt=""></a></li>

	                                <li><a href=""><img src="img/post-params/2.png" alt=""></a></li>

	                                <li><a href=""><img src="img/post-params/3.png" alt=""></a></li>

	                                <li><a href=""><img src="img/post-params/4.png" alt=""></a></li>

	                            </ul>

	                        </div>

	                        <div class="post-item-desc-wrapper block-right">

	                            <div class="post-item-tags">

	                                <a href="{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->name }}</a>, <a href="{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->main_phrase }}</a>

	                            </div>

	                        </div>



	                        <div class="clearfix"></div>

	                        <div class="post-item-link block-right">

	                            <a href="{{ route('advert', ['id' => $advert->id]) }}">Подробнее</a><i class="fa fa-angle-right"

	                                                        aria-hidden="true"></i>

	                        </div>

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
@endsection

@section('leftsidebar')
	@include('layouts.leftsidebar')
@endsection

@section('rightsidebar')
	@include('layouts.rightsidebar')
@endsection