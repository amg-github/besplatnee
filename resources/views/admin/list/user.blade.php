<div class="post-item-wrapper" data-id="{{ $user->id }}">
    <div class="post-item-wrap clearfix">
        <div class="post-item-params block-left" style="width: 10%">
            <ul class="smart-settings-post-user">
            @can('edit', $user)
                <li class="user-is-auth"><a href="{{ route('admin.edit', ['id' => $user->id, 'model' => 'users']) }}" title="Редактировать"><img src="img/post-params/5.png" alt=""></a></li>
            @endcan
            @can('remove', $user)
                <li class="user-is-auth"><a href="{{ route('admin.remove', ['id' => $user->id, 'model' => 'users']) }}" title="Удалить"><img src="img/post-params/3.png" alt=""></a></li>
            @endcan
            </ul>
        </div>

        <div class="post-item-content block-left @if(!Auth::check()) c-advert-info-full @endif" style="width: 50%">
            <p>Основной телефон:&nbsp;{{ $user->phone }}</p>
            @if($user->phone()->first()->verify)
            <p style="color: green">Телефон проверен</p>
            @else
            <p style="color: red">Телефон не проверен</p>
            @endif
            <div class="clearfix"></div>
        </div>
        <div class="post-item-comment post-item-cities block-left" style="width: 20%">
            @if($user->isSuperAdmin())
                Доступ ко всем городам
            @else
                @if($user->admin_cities)
                    @foreach($user->admin_cities as $city) 
                        {{ $city }}<br>
                    @endforeach
                @endif
            @endif
        </div>
        <div class="post-item-comment post-item-date block-left" style="width: 20%">
            <p>Дата регистрации:&nbsp;{{ Carbon\Carbon::parse($user->created_at)->format('d.m.Y') }}</p>
        </div>
    </div>
</div>