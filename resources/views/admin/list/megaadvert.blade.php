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
                <li class="user-is-auth"><a href="{{ route('admin.edit', ['id' => $advert->id, 'model' => 'megaadverts']) }}" title="Редактировать"><img src="img/post-params/5.png" alt=""></a></li>
            @endcan
            @can('remove', $advert)
                <li class="user-is-auth"><a href="{{ route('admin.remove', ['id' => $advert->id, 'model' => 'megaadverts']) }}" title="Удалить"><img src="img/post-params/3.png" alt=""></a></li>
            @endcan
            </ul>
        </div>
        <div class="post-item-params block-left" style="width: 14%">
        </div>

        <div class="post-item-content block-left @if(!Auth::check()) c-advert-info-full @endif" style="width: 70%">
            
            @include('advert.mega')
            <div class="clearfix"></div>
        </div>
    </div>
</div>