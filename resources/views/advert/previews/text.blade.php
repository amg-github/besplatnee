<div class="post-item-wrapper" id="advert-{{ $advert->id }}">
    @if($advert->isArchive())
        <div class="archive-status">в архиве</div>
    @endif
    <div class="post-item-wrap clearfix">

        @switch ($advert->type)

            @case(3)
            <a target="__blank" href="{{ $advert->getUrl() }}">
                {!! $advert->getMegaImage(600, 140) !!}
            </a>

            @break

            @default

                <div class="post-item-main-wrapper clearfix" style="{{ $advert->template && $advert->accented ? $advert->template->buildStyles() : '' }}">

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
                            <div class="post-item-title block-left"><a target="__blank" href="{{ $advert->getUrl() }}">{{ $advert->name }}</a>
                            </div>
                            @if($advert->price > 0) 
                                <div class="post-item-price block-right">{{ number_format($advert->price, 0, ',', ' ') }} руб.</div>
                            @endif

                            <div class="clearfix"></div>
                            @if($advert->show_phone)
                                <div class="post-item-phone block-left">Тел.:
                                    <span>{{ $advert->owner->phone }}</span>
                                </div>
                            @endif


                            <div class="post-item-desc-wrapper block-right">
                                <div class="post-item-subtitle">г. {{ config('area')->getName() }}</div>
                            </div>
                        

                        
                        <div class="clearfix"></div>

                        <div class="post-item-desc-wrapper {{ $advert->getVideos()->count() > 0 ? 'post-has-video' : '' }}">
                            <div class="post-item-desc">
                                <a target="__blank" href="{{ $advert->getUrl() }}" title="Подробнее">
                                    {!! str_limit($advert->content, 300, '...') !!} 
                                    
                                        г. {{ config('area')->getName() }}
                                    
                                </a>
                            </div>
                            @if($video = $advert->getVideos()->first())
                            <div class="post-item-video">
                                <a href="https://www.youtube.com/watch?v={{ $video->path }}"><img src="https://i1.ytimg.com/vi/{{ $video->path }}/default.jpg"
                                                         alt="" style="max-width: 120px"></a>
                            </div>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <div class="post-item-desc-wrapper">
                            <div class="post-item-tags">
                                @foreach($advert->getHeadingPath() as $heading)
                                    @if($loop->index > 0)
                                        /
                                    @endif
                                    <a target="__blank" href="{{ $heading['url'] }}" style="color: #9c9c9c">{{ $heading['title'] }}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="post-item-desc-wrapper">
                            <div class="post-item-tags">
                                <!--<a target="__blank" href="{{ $advert->getUrl() }}">{{ $advert->name }}</a>, --><a target="__blank" 
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
                        <div class="post-item-views-title">@lang('adverts.view.count'):</div>
                        <!-- Просмотры / переходы -->
                        <div class="post-item-views-link"><a href="{{ $advert->getUrl() }}">{{ $advert->viewcount }} / {{ $advert->clickcount }}</a></div>
                    </div>
                </div>

            @break

        @endswitch
    </div>
    <div class="post-item-settings">
        <ul class="smart-settings-post-user">
            <li>
                <a href="" class="site-action" alt="Добавить в избранное" data-action="follow">
                    <img src="img/post-params/1.png" alt="">
                    <span>Добавить в избранное</span>
                </a>
            </li>
            <li>
                <a href="" alt="Не показывать мне" class="site-action" data-action="hide">
                    <img src="img/post-params/3.png" alt="">
                    <span>Не показывать мне</span>
                </a>
            </li>
            <li>
                <a href="" alt="Удалить из газеты" class="site-action" data-action="remove">
                    <img src="img/post-params/4.png" alt="">
                    <span>Удалить из газеты</span>
                </a>
            </li>
        </ul>
    </div>
</div>