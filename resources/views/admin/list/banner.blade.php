<div class="post-item-wrapper" data-id="{{ $banner->id }}">
    <div class="post-item-wrap clearfix">
        <div class="post-item-status block-left" style="width: 10%">
            <ul class="smart-settings-post-user">
            @if($banner->active)
                <li class="user-is-auth"><a href="" title="Активен, нажмите чтобы отключить"><i class="fa fa-check" aria-hidden="true" style="font-size: 18px; color: green"></i></a></li>
            @else
                <li class="user-is-auth"><a href="" title="Не активен, нажмите чтобы включить"><i class="fa fa-times" aria-hidden="true" style="font-size: 18px; color: red"></i></a></li>
            @endif
            </ul>
        </div>
        <div class="post-item-params block-left" style="width: 10%">
            <ul class="smart-settings-post-user">
            @can('edit', $banner)
                <li class="user-is-auth"><a href="{{ route('admin.edit', ['id' => $banner->id, 'model' => 'banners']) }}" title="Редактировать"><img src="img/post-params/5.png" alt=""></a></li>
            @endcan
            @can('remove', $banner)
                <li class="user-is-auth"><a href="{{ route('admin.remove', ['id' => $banner->id, 'model' => 'banners']) }}" title="Удалить"><img src="img/post-params/3.png" alt=""></a></li>
            @endcan
            </ul>
        </div>

        <div class="post-item-content block-left @if(!Auth::check()) c-advert-info-full @endif" style="width: 20%">
            <p>Статус рекламы: Бесплатные</p>
            <p>Позиция: 
                @if($banner->position == 'top')
                Сверху
                @elseif($banner->position == 'advert')
                Между объявлениями
                @elseif($banner->position == 'header')
                В шапке
                @elseif($banner->position == 'footer')
                В футере
                @elseif($banner->position == 'left')
                Слева
                @elseif($banner->position == 'right')
                Справа
                @endif
            </p>
            <div class="clearfix"></div>
        </div>
        <div class="post-item-comment post-item-cities block-left" style="width: 50%">
            <img src="{{ $banner->image }}" style="width: auto; max-width: 100%">
            <p>Ссылка:&nbsp;<a href="{{ $banner->url }}">{{ $banner->url }}</a></p>
            <p>Текст при наведении:&nbsp;{{ $banner->hover_text }}</p>
        </div>
    </div>
</div>