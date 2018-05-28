<div class="personal-account-wrapper">
    <div class="row">
        <div class="col-xs-3">
            <div class="account-welcome">@lang('auth.welcome'), <a href="#">{{ Auth::user()->fullname() }}</a>!</div>
        </div>
        <div class="col-xs-9">
            <ul class="account-settings-one-tabs">
                <li><a href="{{ route('office.dashboard') }}">@lang('auth.office.home')</a></li>
                <li><a href="{{ route('home') }}">@lang('auth.office.site')</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="account-parameters-wrapper clearfix">
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-3.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('office.adverts') }}">@lang('auth.adverts.my')</a></li>
                <li><a href="{{ route('advert-create') }}">@lang('auth.adverts.add')</a></li>
            </ul>
        </div>
    </div>
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-4.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('office.mega') }}">@lang('auth.adverts.mega.my')</a></li>
                <li><a href="#">@lang('adverts.mega.add')</a></li>
            </ul>
        </div>
    </div>
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-16.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('office.sites') }}">@lang('auth.sites.my')</a></li>
                <li><a href="{{ route('office.shops') }}">@lang('auth.shops.my')</a></li>
                <li><a href="{{ route('site.create') }}">@lang('auth.site.create')</a></li>
            </ul>
        </div>
    </div>
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-3.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('office.favorites') }}">@lang('auth.office.favorite')</a></li>
            </ul>
        </div>
    </div>
</div>