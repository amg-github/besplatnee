<div class="post-item-wrapper" data-id="{{ $advert->id }}">
    @if($advert->isArchive()) 
        <div class="archive-status">в архиве</div>
    @endif
    <div class="post-item-wrap clearfix">
        <div class="post-item-params block-left">
            <ul class="smart-settings-post-user">
                @can('edit', $advert)
                <li class="user-is-auth"><a href="{{ route('advert-edit', ['id' => $advert->id]) }}"><img src="img/post-params/5.png" alt=""></a></li>
                @endcan
                <li><a href="" class="site-action" alt="Добавить в избранное" data-action="follow"><img src="img/post-params/1.png" alt=""></a></li>
                <!--<li><a href=""><img src="img/post-params/2.png" alt=""></a></li>-->
                <li><a href="" alt="Не показывать мне" class="site-action" data-action="hide"><img src="img/post-params/3.png" alt=""></a></li>
                @can('edit', $advert)
                    <li><a href="" alt="Удалить из газеты" class="site-action" data-action="remove"><img src="img/post-params/4.png" alt=""></a></li>
                @endcan
            </ul>
        </div>

        <div class="post-item-content block-left @if(!Auth::check()) c-advert-info-full @endif">
            <div class="post-item-title block-left"><a target="__blank" href="{{ $advert->getUrl() }}" class="site-advert-field" data-field="name" data-type="input">{{ $advert->name }}</a>
            </div>
            @if($advert->price > 0) 
                <div class="post-item-price block-right"><span class="site-advert-field" data-field="price" data-type="input">{{ number_format($advert->price, 0, ',', ' ') }}</span> руб.</div>
            @endif

            <div class="clearfix"></div>
            @if($advert->show_phone)
                <div class="post-item-phone block-left">Тел.:
                    <span>{{ $advert->owner->phone }}</span>
                </div>
            @endif

            
                <div class="post-item-desc-wrapper block-right">
                    <div class="post-item-subtitle">@if(isset($advert->geoObjects[0]))
                            г. {{ $advert->geoObjects[0]->name }}
                        @else
                            г. {{ config('area')->name }}
                        @endif</div>
                </div>
            
            <div class="clearfix"></div>


            <div class="post-item-params">
                <div class="post-item-date">{{ Carbon\Carbon::parse($advert->fakeupdated_at)->format('d.m.Y') }}</div>
            </div>

            <div class="post-item-desc-wrapper">
                <div class="post-item-desc site-advert-field" data-field="content" data-type="textarea">{!! str_limit($advert->content, 300, '...') !!}

                    @if(isset($advert->geoObjects[0]))
                        г. {{ $advert->geoObjects[0]->name }}
                    @else
                        г. {{ config('area')->name }}
                    @endif
                            </div>
            </div>

            <div class="post-item-thumb-wrapper block-left">
                @foreach($advert->getImages(180, 160) as $media)
                    <div class="item block-left"><img src="{{ asset($media->path) }}" alt="" style="height: 33px; width: auto;"></div>
                @endforeach
            </div>

            <div class="post-item-desc-wrapper block-right col-xs-7 row">
                <div class="post-item-tags text-right">
                    <!--<a target="__blank" href="{{ $advert->getUrl() }}">{{ $advert->name }}</a>, --><a target="__blank" 
                        href="{{ $advert->getUrl() }}">{!! $advert->main_phrase !!}</a>
                </div>
            </div>
            <div class="clearfix"></div>

            @if($advert->contacts)
                <div class="post-item-desc-wrapper block-left">
                    <div class="post-item-tags" style="color: #9c9c9c; font-size: 12px">
                        Доп. контакты: {{$advert->contacts}}
                    </div>
                </div><br>
            @endif
            <div class="post-item-link block-left" style="color: #9c9c9c">
                @foreach($advert->getHeadingPath() as $heading)
                    @if($loop->index > 0)
                        /
                    @endif
                    <a target="__blank" href="{{ $heading['url'] }}" style="color: #9c9c9c">{{ $heading['title'] }}</a>
                @endforeach
            </div>

            <div class="post-item-link block-right">
                <a target="__blank" href="{{ $advert->getUrl() }}">Подробнее</a><i class="fa fa-angle-right"
                                            aria-hidden="true"></i>
            </div>
        </div>
        @if(Auth::check())
            <div class="post-item-comment block-left site-advert-field" data-field="comment" data-type="textarea">{{ Auth::user()->customAdverts()->where('id', $advert->id)->value('comment') ?? __('adverts.place.for-comments') }}</div>
        @endif
    </div>
</div>