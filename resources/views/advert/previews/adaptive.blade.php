<div class="post-item-wrapper" data-id="{{ $advert->id }}">
    @if($advert->isArchive()) 
        <div class="archive-status">@lang('adverts.in-archive')</div>
    @endif
    <div class="post-item-wrap clearfix" style="{{ $advert->buildPickupStyles() }};cursor:pointer;" onclick="if($(event.target).parent().is(this)) { location.href='{{ $advert->getUrl() }}' }">
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
                @if(!$advert->megaAdvert)
                <div class="post-item-title block-left"><a target="__blank" href="{{ $advert->getUrl() }}">{{ $advert->name }}</a>
                </div>
                    @if($advert->price > 0)
                        <div class="post-item-price block-right">{{ number_format($advert->price, 0, ',', ' ') }} @lang('adverts.curency')</div>
                    @endif

                <div class="clearfix"></div>
                @if($advert->show_phone)
                <div class="post-item-phone block-left">Тел.:
                    <span>{{ $advert->owner->phone }}</span></div>
                @endif
                <div class="post-item-desc-wrapper block-right">
                    
                        <div class="post-item-subtitle">
                            @if(isset($advert->geoObjects[0]))
                                г. {{ $advert->geoObjects[0]->name }}
                            @else
                                г. {{ config('area')->name }}
                            @endif</div>
                    
                </div>
                <div class="clearfix"></div>
                <div class="post-item-params">

                    <div class="post-item-date">{{ Carbon\Carbon::parse($advert->fakeupdated_at)->format('d-m-Y') }}</div>

                </div>
                <div class="post-item-desc-wrapper {{ $advert->getVideos()->count() > 0 ? 'post-has-video' : '' }}">
                    <div class="post-item-desc">
                        <a target="__blank" href="{{ $advert->getUrl() }}" title="@lang('site.phrases.more')">
                            {!! str_limit($advert->content, 300, '...') !!}

                        </a>
                    </div>
                    @if($video = $advert->getVideos()->first())
                    <div class="post-item-video">
                        <a href="https://www.youtube.com/watch?v={{ $video->path }}"><img src="https://i1.ytimg.com/vi/{{ $video->path }}/default.jpg"
                                                 alt="" style="max-width: 120px"></a>
                    </div>
                    @endif
                </div>

                @else
                    @include('advert.mega')
                @endif
                <div class="clearfix"></div>
                @if($advert->contacts)
                    <div class="post-item-desc-wrapper">
                        <div class="post-item-tags" style="color: #9c9c9c; font-size: 12px">
                            Доп. контакты: {{$advert->contacts}}
                        </div>
                    </div><br>
                @endif
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

                <div class="post-item-desc-wrapper block-right">
                    <div class="post-item-tags">
                        <!--<a target="__blank" href="{{ $advert->getUrl() }}">{{ $advert->name }}</a>, --><a
                            target="__blank" href="{{ $advert->getUrl() }}">{!! $advert->main_phrase !!}</a>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="post-item-link block-right">

                    <a target="__blank" href="{{ $advert->getUrl() }}">@lang('site.phrases.more')</a><i class="fa fa-angle-right"

                                                aria-hidden="true"></i>

                </div>
            </div>


                <div class="post-item-params block-left">
                    <ul class="smart-settings-post-user block-left">
                        <li>
                            <a href="" class="site-action" alt="Добавить в избранное" data-action="follow">
                                <img src="img/post-params/1.png" alt="">
                                <span>@lang('adverts.favorite.add')</span>
                            </a>
                        </li>
                        <li>
                            <a href="" alt="Не показывать мне" class="site-action" data-action="hide">
                                <img src="img/post-params/3.png" alt="">
                                <span>@lang('adverts.hide-for-me')</span>
                            </a>
                        </li>
                        <li>
                            <a href="" alt="Удалить из газеты" class="site-action" data-action="remove">
                                <img src="img/post-params/4.png" alt="">
                                <span>@lang('adverts.remove.request')</span>
                            </a>
                        </li>
                    </ul>
                </div>
    </div>
</div>

