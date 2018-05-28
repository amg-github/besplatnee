<div class="post-item-wrapper">
    <div class="post-item-wrap clearfix">
        <div class="post-item-main-wrapper clearfix">
            <div class="post-item-thumb">
                <div class="post-item-count-wrapper">{{ $advert->getImages()->count() }}</div>
                <div class="owl-posts owl-carousel owl-theme">
                @forelse($advert->getImages(180, 160) as $media)
                    <div class="item"><img src="{{ asset($media->path) }}" alt=""></div>
                @empty
                    <div class="item"></div>
                @endforelse
                </div>
            </div>
            <div class="post-item-content">
                <div class="post-item-title block-left"><a href="{{ $advert->getUrl() }}">{{ $advert->name }}</a>
                </div>
                <div class="post-item-price block-right">{{ number_format($advert->getProperty('price', 0), 0, ',', ' ') }} руб.</div>

                <div class="clearfix"></div>
                @if($advert->show_phone)
                <div class="post-item-phone block-left">Тел.:
                    <span>{{ $advert->owner->phone }}</span></div>
                @endif
                <div class="post-item-desc-wrapper block-right">
                    <!--<div class="post-item-subtitle">{!! $advert->main_phrase !!}</div>-->
                </div>
                <div class="clearfix"></div>

                <div class="post-item-desc-wrapper">
                    <div class="post-item-desc">
                        <a href="{{ $advert->getUrl() }}" title="Подробнее">
                            {!! str_limit($advert->content, 100, '...') !!}
                            @if($advert->cities->first())
                                г. {{ $advert->cities->first()->getName() }}
                            @endif
                        </a>
                    </div>
                    @if($video = $advert->getVideos()->first())
                    <div class="post-item-video">
                        <a href="{{ asset($video->path) }}"><img src="{{ asset('img/video-preview.png') }}"
                                                 alt="" style="max-width: 120px"></a>
                    </div>
                    @endif
                </div>
                <div class="post-item-desc-wrapper">
                    <div class="post-item-tags">
                        <!--<a href="{{ $advert->getUrl() }}">{{ $advert->name }}</a>, --><a
                            href="{{ $advert->getUrl() }}">{!! $advert->main_phrase !!}</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="post-item-params clearfix">
            <div class="post-item-date">{{ Carbon\Carbon::parse($advert->fakeupdated_at)->format('d-m-Y') }}</div>
            <div class="post-item-time">{{ Carbon\Carbon::parse($advert->fakeupdated_at)->format('h ч : i м : s с') }}</div>
            <div class="post-item-views-wrapper">
                <div class="post-item-views-title">Просмотров:</div>
                <!-- Просмотры / переходы -->
                <div class="post-item-views-link"><a href="{{ $advert->getUrl() }}">{{ round($advert->viewcount / 2) }} / {{ $advert->clickcount }}</a></div>
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