<div class="post-item-wrapper" data-id="{{ $advert->id }}">
    <div class="post-item-wrap clearfix">
        <div class="post-item-status block-left" style="width: 8%">
            <ul class="smart-settings-post-user">
            @if($advert->approved)
                <li class="user-is-auth"><a href="" title="Подтвержден, нажмите чтобы отменить подтверждение"><i class="fa fa-check" aria-hidden="true" style="font-size: 18px; color: green"></i></a></li>
            @else
                <li class="user-is-auth"><a href="" title="Не подтвержден, нажмите чтобы подтвердить"><i class="fa fa-times" aria-hidden="true" style="font-size: 18px; color: red"></i></a></li>
            @endif
            </ul>
        </div>
        <div class="post-item-params block-left" style="width: 8%">
            <ul class="smart-settings-post-user">
            @can('edit', $advert)
                <li class="user-is-auth"><a href="{{ route('admin.edit', ['id' => $advert->id, 'model' => 'adverts']) }}" title="Редактировать"><img src="img/post-params/5.png" alt=""></a></li>
            @endcan
            @can('remove', $advert)
                <li class="user-is-auth"><a href="{{ route('admin.remove', ['id' => $advert->id, 'model' => 'adverts']) }}" title="Удалить"><img src="img/post-params/3.png" alt=""></a></li>
            @endcan
                <li class="user-is-auth"><a href="{{ route('admin.edit', ['id' => $advert->id, 'model' => 'megaadverts']) }}" title="Выделить"><img src="img/post-params/1.png" alt=""></a></li>
            </ul>
        </div>

        <div class="post-item-content block-left @if(!Auth::check()) c-advert-info-full @endif" style="width: 44%">
            <div class="post-item-title block-left site-advert-field" data-field="name" data-type="input">{{ $advert->name }}
            </div>
            <div class="post-item-price block-right"><span class="site-advert-field" data-field="price" data-type="input">{{ number_format($advert->price, 0, ',', ' ') }}</span> руб.</div>

            <div class="clearfix"></div>
            <div class="post-item-phone block-left">Тел.:
                <span>{{ $advert->owner->phone }}</span></div>
            <div class="post-item-desc-wrapper block-right">
                <div class="post-item-subtitle site-advert-field" data-field="main_phrase" data-type="input">{!! $advert->main_phrase !!}</div>
            </div>
            <div class="clearfix"></div>


            <div class="post-item-params">
                <div class="post-item-date">{{ Carbon\Carbon::parse($advert->created_at)->timezone('Europe/Samara')->format('d.m.Y') }}</div>
            </div>

            <div class="post-item-desc-wrapper">
                <div class="post-item-desc site-advert-field" data-field="content" data-type="textarea">{!! $advert->content !!}</div>
            </div>

            <div class="post-item-thumb-wrapper block-left">
                @foreach($advert->medias()->where('type', 'preview_180x160')->get() as $media)
                    <div class="item block-left"><img src="{{ asset($media->path) }}" alt="" style="height: 33px; width: auto;"></div>
                @endforeach
            </div>

            <div class="clearfix"></div>
        </div>
        <div class="post-item-comment post-item-information block-left site-advert-field" style="width: 20%" data-field="extend_content" data-type="input">{{ $advert->extend_content }}</div>
        <div class="post-item-comment post-item-cities block-left" style="width: 10%">
            @if($advert->diblicate_in_all_city)
                Размещено во всех городах
            @else
                @foreach($advert->cities()->get() as $city)
                    {{ $city->getName() }}<br>
                @endforeach
            @endif
        </div>
        <div class="post-item-comment post-item-date block-left" style="width: 10%">
            {{ Carbon\Carbon::parse($advert->created_at)->timezone('Europe/Samara')->format('d.m.Y') }}
        </div>
    </div>
</div>