@extends('layouts.app')

@section('content')
<div class="block-left b-content-wrapper">
    <!-- Banner outer page -->
    <div class="banner-wrapper">
        <div class="banner-two-item clearfix">
        @foreach(Besplatnee::banners()->getPosition('top', isset($category) ? $category->id : 0) as $banner) 
            <div class="block-left col-xs-12">
                <div class="row">
                    <a href="{{ $banner->url }}">
                        <img src="{{ $banner->image }}" alt="{{ $banner->hover_text }}">
                    </a>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <!-- End banner outer page -->

    <!-- Post list -->
    <div class="posts-wrapper clearfix">

        <!-- Render result module -->
        <div class="result-wrapper">
            <div class="post-list-wrapper clearfix">
                @forelse($adverts as $advert) 
                <div class="post-item-wrapper">
                    <div class="post-item-wrap clearfix">
                        <div class="post-item-main-wrapper clearfix">
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

                                <div class="post-item-desc-wrapper">
                                    <div class="post-item-desc">
                                        <a href="{{ route('advert', ['id' => $advert->id]) }}" title="Подробнее">
                                            {!! str_limit($advert->content, 100, '...') !!}
                                        </a>
                                    </div>
                                    @if($video = $advert->medias()->where('type', 'video')->first())
                                    <div class="post-item-video">
                                        <a href="{{ asset($video->path) }}"><img src="{{ asset('img/video-preview.png') }}"
                                                                 alt="" style="max-width: 120px"></a>
                                    </div>
                                    @endif
                                </div>
                                <div class="post-item-desc-wrapper">
                                    <div class="post-item-tags">
                                        <a href="{{ route('advert', ['id' => $advert->id]) }}">{{ $advert->name }}</a>, <a
                                            href="{{ route('advert', ['id' => $advert->id]) }}">{!! $advert->main_phrase !!}</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="post-item-params clearfix">
                            <div class="post-item-date">{{ Carbon\Carbon::parse($advert->created_at)->format('d-m-Y') }}</div>
                            <div class="post-item-time">{{ Carbon\Carbon::parse($advert->created_at)->format('h ч : i м : s с') }}</div>
                            <div class="post-item-views-wrapper">
                                <div class="post-item-views-title">Просмотров:</div>
                                <!-- Просмотры / переходы -->
                                <div class="post-item-views-link"><a href="{{ route('advert', ['id' => $advert->id]) }}">{{ round($advert->viewcount / 2) }} / {{ $advert->clickcount }}</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="post-item-settings">
                        <ul class="smart-settings-post-user">
                            <li>
                                <a href="">
                                    <img src="img/post-params/1.png" alt="">
                                    <span>Добавить в избранное</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="img/post-params/3.png" alt="">
                                    <span>Не показывать мне</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="img/post-params/4.png" alt="">
                                    <span>Удалить из газеты</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                @empty
                    <p><center>Результатов не найдено</center></p>
                @endforelse
            </div>

            <!-- Banner outer page -->
            <div class="clearfix"></div>
            <div class="banner-wrapper view-line-gray">
                <div class="banner-one-item clearfix">
                    <div class="block-left col-xs-12">
                        <div class="row">
                            <a href="#">
                                <img src="img/banner-list/14.jpg" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End banner outer page -->

            <div class="post-list-wrapper clearfix">

            </div>

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