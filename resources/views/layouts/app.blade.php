<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ request()->route()->controller->getMetaTitle() }}</title>

    <base href="{{ app()->make('url')->to('/') }}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="{{ request()->route()->controller->getMetaDescription() }}">
    <meta name="keywords" content="{{ request()->route()->controller->getMetaKeywords() }}">
    <meta property="og:image" content="{{ asset('path/to/image.jpg') }}">
    <meta name="yandex-verification" content="3dae2fafc3546d83">
    <link rel="shortcut icon" href="{{ asset('img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/favicon/apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/favicon/apple-touch-icon-114x114.png') }}">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#000">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#000">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#000">

    <style>body {
        opacity: 0;
        overflow-x: hidden;
    }

    html {
        background-color: #fff;
    }</style>

    <!-- Styles -->
    <link href="{{ asset('css/main.min.css') }}" rel="stylesheet">

    <script type="text/javascript">
        window.SITE = {};
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript">
    </script>
</head>
<body class="version-{{ $version ?? 'a' }} @yield('bodyclass')">
@section('topbanners')
    @include('elements.banners.header')
@show
<div id="header">
    <div class="header-wrapper">
        <div class="fw-container">
            <div class="row">
                <div class="">
                    <div class="logotype-company">
                        <div class="sub-category-title">@lang('site.adverts.main-title')</div>
                        <a href="@section('top.logo.url', route('home'))"><img src="img/logo.png" alt="Besplatnee.net"></a>
                        <div class="logotype-company-city">@yield('top.address', config('area')->getName())</div>
                        <div class="logotype-company-phone">(929)-717-58-58</div>
                    </div>

                    <div class="add-listing-wrapper block-right">
                        <a href="{{ route('advert-create') }}" class="add-ad">
                            <div class="add-listing-item">
                                <div class="add-thumbnail-img"><img src="img/icon-add-ad.png" alt=""></div>
                                <div class="add-title">@lang('adverts.add')</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="">
                    <div class="main-user-zone-wrapper">
                        <div class="main-user-zone-top">
                            <div class="navigation-category-wrapper">
                                <div class="navigation-top clearfix">
                                    <div class="nav">
                                        <ul id="nav-top-navigation">
                                            <li><a href="@section('adverts.url', route('home'))" class="active">@lang('site.phrases.adverts')</a></li>
                                            <li><a href="{{ route('page', ['alias' => 'news']) }}">@lang('site.phrases.news')</a></li>
                                            <li><a href="{{ route('page', ['alias' => 'sales']) }}">@lang('site.phrases.sales')</a></li>
                                            <li><a href="{{ route('page', ['alias' => 'deposit']) }}">@lang('site.phrases.deposit')</a></li>
                                            <li><a href="{{ route('page', ['alias' => 'business']) }}">@lang('site.phrases.business')</a></li>
                                            <li class="sub-nav more-links dropdown">
                                                <a id="dLabel" type="button" data-toggle="dropdown"
                                                   aria-haspopup="true" aria-expanded="false">@lang('site.phrases.more')</a>
                                            </li>
                                        </ul>
                                    </div>
                                    @guest
                                        <div class="nav-user-login-manipulation">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            <a href="{{ route('login') }}">@lang('auth.login')</a>
                                            <span class="separation">/</span>
                                            <a href="{{ route('register') }}">@lang('auth.registration')</a>
                                        </div>
                                    @endguest

                                    @auth
                                        <div class="nav-user-login-manipulation">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            <a href="{{ route('office.dashboard') }}">@lang('auth.office')</a>
                                            @permission('cpanel_access')
                                                <span class="separation">/</span>
                                                <a href="{{ route('admin.dashboard') }}">@lang('auth.manage')</a>
                                            @endpermission
                                            <span class="separation">/</span>
                                            <a href="{{ route('logout') }}" 
                                                onclick="event.preventDefault(); 
                                                         document.getElementById('logout-form').submit();">
                                                @lang('auth.logout')
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    @endauth
                                    <div class="nav-location-wrapper">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        <a href="{{ route('home') }}">@lang('site.phrases.city.change')</a>
                                        <div class="location-popup" data-city-id="moskva" style="display: none;">
                                            <p>Ваш город - Москва?</p>
                                            <button class="location-popup-button button-yes" type="button">Да</button>
                                            <button class="location-popup-button button-no" type="button">Нет</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="main-user-zone-middle clearfix">
                            <div class="main-search-engine-wrapper">
                                <form action="{{ route('search') }}" method="post" id="search-engine">
                                    {{ csrf_field() }}
                                    <label for="input-searching">
                                        <input id="input-searching" name="search_query" placeholder="@lang('site.phrases.adverts.search')" value="{{ request()->input('search_query') }}" onkeypress="if(event.charCode == 13) { $(this).parents('form').submit(); event.preventDefault(); }">
                                        <button id="input-searching-clear" onclick="$(this).prev().val('');return false">x</button>
                                    </label>
                                    <label for="options-category">
                                        <select id="options-category" name="search_category_id">
                                            <option value="0" {{ request()->input('search_category_id') == 0 ? 'selected': '' }}>@lang('site.phrases.all-adverts')</option>
                                            @foreach(\Besplatnee::headings()->getTree() as $heading)
                                            <option value="{{ $heading->id }}" class="root" {{ request()->input('search_category_id') == $heading->id ? 'selected': '' }}>@lang($heading->name)</option>
                                                @foreach(\Besplatnee::headings()->getChildrens($heading) as $children) 
                                                    <option value="{{ $children->id }}" class="sheet" {{ request()->input('search_category_id') == $children->id ? 'selected': '' }}>@lang($children->name)</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </label>
                                    <!-- country -->
                                    <label for="options-country">
                                        <select id="options-country" name="search_area_id">
                                            <option value="world">@lang('site.search.all-world')</option>
                                            <option value="country">@lang('site.search.all-country', ['country' => \Config::get('area')->country->getForName()])</option>
                                            <option value="region">@lang('site.search.all-region', ['region' => \Config::get('area')->region->getForName()])</option>
                                            @foreach(Besplatnee::cities()->getByRegionId(\Config::get('area')->region->id) as $city)
                                                <option value="{{ $city->id }}" 
                                                    {{ request()->route()->controller->getCityOfSearch() == $city->id ? ' selected' : ''}}
                                                >{{ $city->getName() }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <div style="display: none" id="search-engine-hidden-properties">
                                        @if(isset($searchFilters))
                                            @foreach($searchFilters as $name => $value)
                                                <input type="hidden" name="properties[{{ $name }}]" value="{{ $value }}">
                                            @endforeach
                                        @endif
                                    </div>

                                    <button id="search-engine-go" type="submit">@lang('site.search.go')</button>
                                </form>
                                <span class="power-search-engine">@lang('site.search.examble')</span>
                                <a href="#" class="power-search-engine" onclick="$('#extended-search-block').toggle(); return false;">@lang('site.search.extended')</a>
                                <span class="power-search-engine" style="    margin-left: 144px;">@lang('site.search.geo')</span>

                                <div id="extended-search-block" class="" style="{{ isset($searchFilters) && count($searchFilters) > 0 ? '' : 'display: none' }}">
                                    <form action="{{ route('search') }}" method="POST">
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="main-user-zone-bottom block-left">
                            <div style="position: absolute;top:0;right:0">
                                <select name="selected_language_id" style="width: 120px;font-size: 12px;" onchange="SITE.location.setLanguage($(this).val())">
                                    @foreach(\App\Language::all() as $lang)
                                    <option value="{{ $lang->id }}" {{ \Config::get('language')->id == $lang->id ? 'selected' : '' }}>{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="navigation-category-wrapper clearfix">
                                <div class="nav">
                                    <ul id="nav-bottom-navigation">
                                    @foreach(\Besplatnee::headings()->getMainList() as $heading)
                                        <li>
                                            <a href="{{ $heading->getUrl() }}">@lang($heading->name)</a>
                                            <ul class="sub-nav-bottom-navigation">
                                            @foreach(\Besplatnee::headings()->getChildrens($heading) as $children)
                                                <li><a href="{{ $children->getUrl() }}">@lang($children->name)</a></li>
                                            @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                        <li class="sub-nav more-links dropdown">
                                            <a id="dLabell" type="button" data-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false">@lang('site.phrases.more')</a>
                                            @include('elements.header.categories.wrapper', [
                                                'headings' => Besplatnee::headings()->generateTree(),
                                            ])
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="content">
    <div class="content-wrapper">
        <div class="fw-container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <!-- Start render content -->

                        @include('elements.breadcrumbs')

                        @yield('content')

                        @yield('rightsidebar')

                        <!-- End render content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('bottombanners')
<div id="pre-footer-banners">
    <div class="banners-bottom banners-top">
        <div class="fw-container">
            @include('elements.banners.footer')
        </div>
    </div>
</div>
@show
<div id="footer" class="clearfix">
    <div class="footer-wrapper">
        <div class="footer-top">
            <div class="fw-container">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-content clearfix">
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <a href="#" class="logotype-company-footer">
                                <img src="img/logo-bottom.png" alt="">
                            </a>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-9">
                            <div class="row">
                                <div class="footer-company-desc">
                                    @lang('site.description.bottom', [
                                        'city' => \Config::get('area')->getName(),
                                        'for_city' => \Config::get('area')->getForName(),
                                        'in_city' =>  \Config::get('area')->getInName(),
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="fw-container">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="text-center">
                        <div class="copyright">
                            @lang('site.description.copyright', [
                                'city' => \Config::get('area')->getName(),
                                'year' =>  date('Y'),
                            ])
                        </div>
                        <a href="/queries">Поисковые запросы</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/scripts.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>

<center>
<a href="http://metrika.yandex.ru/stat/?id=9721678&amp;from=informer"
target="_blank" rel="nofollow" style='display:none'><img src="//bs.yandex.ru/informer/9721678/3_1_FFC520FF_FFA500FF_0_pageviews"
style="width:88px; height:31px; border:0; display: inline-block;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:9721678,lang:'ru'});return false}catch(e){}"/></a>


<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<script type="text/javascript">
try { var yaCounter9721678 = new Ya.Metrika({id:9721678,
          webvisor:true,
          clickmap:true,
          trackLinks:true,
          accurateTrackBounce:true});
} catch(e) { }
</script>
<noscript><div style="display: none;"><img src="//mc.yandex.ru/watch/9721678" style="position:absolute; left:-9999px; width:auto;" alt="" /></div></noscript>

<script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click;besplatneenet' "+
"target=_blank style='display:none'><img src='//counter.yadro.ru/hit;besplatneenet?t12.6;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border='0' width='88' height='31' style='display:inline-block'><\/a>")
//--></script>
</center>

</body>
</html>
