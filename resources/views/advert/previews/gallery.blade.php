<div class="post-item-wrapper">
    @if($advert->isArchive()) 
        <div class="archive-status">@lang('adverts.in-archive')</div>
    @endif
    <div class="post-item-thumb">
        <div class="post-item-count-wrapper">{{ $advert->getImages()->count() }}</div>
        <div class="owl-posts owl-carousel owl-theme">
            @forelse($advert->getImages(180, 160) as $media)
                <div class="item"><img src="{{ asset($media->path) }}" alt=""></div>
            @empty
                <div class="item"><img src="" alt=""></div>
            @endforelse
        </div>
    </div>
    <div class="post-item-content">
        <div class="post-item-params clearfix">
            <ul class="smart-settings-post-user block-left">
                <li><a href="" class="site-action" alt="Добавить в избранное" data-action="follow"><img src="img/post-params/1.png" alt=""></a></li>
                <!--<li><a href=""><img src="img/post-params/2.png" alt=""></a></li>-->
                <li><a href="" alt="Не показывать мне" class="site-action" data-action="hide"><img src="img/post-params/3.png" alt=""></a></li>
                <li><a href="" alt="Удалить из газеты" class="site-action" data-action="remove"><img src="img/post-params/4.png" alt=""></a></li>
            </ul>
            <div class="post-item-date block-right">{{ Carbon\Carbon::parse($advert->fakeupdated_at)->format('d.m.Y') }}</div>
        </div>
        <div class="post-item-title"><a target="__blank" href="{{ $advert->getUrl() }}">{{ $advert->name }}</a></div>
        @if($advert->price > 0)
            <div class="post-item-price">{{ number_format($advert->price, 0, ',', ' ') }} руб.</div>
        @endif
        <div class="post-item-desc-wrapper">
           
                <div class="post-item-subtitle">г. {{ config('area')->getName() }}</div>
            
            <div class="post-item-desc">{!! str_limit($advert->content, 300, '...') !!} 
            
                                г. {{ config('area')->getName() }}
                            </div>
            @if($advert->show_phone)
                <div class="post-item-phone">Тел.: <span>{{ $advert->owner->phone }}</span></div>
            @endif
            <div class="post-item-tags">
                <a target="__blank" href="{{ $advert->getUrl() }}">{{ $advert->main_phrase }}</a>
            </div>
        </div>
        <div class="post-item-link">
            <a target="__blank" href="{{ $advert->getUrl() }}">Подробнее</a><i class="fa fa-angle-right" aria-hidden="true"></i>
        </div>
    </div>
</div>