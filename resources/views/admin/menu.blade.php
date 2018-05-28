<div class="personal-account-wrapper">
    <div class="row">
        <div class="col-xs-3">
            <div class="account-welcome">Здравствуйте, <a href="#">{{ Auth::user()->fullname() }}</a>!<br><b>{{ Auth::user()->groups()->first()->name }}</b></div>
        </div>
        <div class="col-xs-9">
            <ul class="account-settings-one-tabs">
                <li><a href="{{ route('admin.dashboard') }}">На главную страницу личного кабинета</a></li>
                <li><a href="{{ route('home') }}">На главную страницу сайта</a></li>
                <li><a href="#">Сменить города</a></li>
                <li><a href="#">Выйти</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="account-parameters-wrapper clearfix">
    @if(Auth::user()->checkPolicy('visor_list') || Auth::user()->checkPolicy('advert_stat'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-1.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
            @if(Auth::user()->checkPolicy('visor_list'))
                <li><a href="#">Учет посетителей</a></li>
            @endif

            @if(Auth::user()->checkPolicy('advert_stat'))
                <li><a href="#">Подано объявлений</a></li>
            @endif
            </ul>
            
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('advert_import'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-2.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="#">Скачать файл с объявлениями</a></li>
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('heading_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-99.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                @if(Auth::user()->checkPolicy('heading_list'))
                    <li><a href="{{ route('admin.list', ['model' => 'headings']) }}">Список рубрик</a></li>
                @endif

                @if(Auth::user()->checkPolicy('heading_create'))
                    <li><a href="{{ route('admin.create', ['model' => 'headings']) }}">Создать</a></li>
                @endif


            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('geoobject_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-98.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                @if(Auth::user()->checkPolicy('geoobject_list'))
                    <li><a href="{{ route('admin.list', ['model' => 'geoobjects']) }}">Геообъекты</a></li>
                @endif

                @if(Auth::user()->checkPolicy('geoobject_create'))
                    <li><a href="{{ route('admin.create', ['model' => 'geoobjects']) }}">Создать</a></li>
                @endif


            </ul>
        </div>
    </div>
    @endif

    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-5.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('admin.list', ['model' => 'advertsearchqueries']) }}">Поисковые запросы</a></li>
            </ul>
        </div>
    </div>

    @if(Auth::user()->checkPolicy('advert_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-3.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
            @if(Auth::user()->checkPolicy('advert_list'))
                <li><a href="{{ route('admin.list', ['model' => 'adverts']) }}">Объявления</a></li>
            @endif

            @if(Auth::user()->checkPolicy('advert_create'))
                <li><a href="{{ route('admin.create', ['model' => 'adverts']) }}">Создать</a></li>
            @endif
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('megaadvert_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-4.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
            @if(Auth::user()->checkPolicy('megaadvert_list'))
                <li><a href="{{ route('admin.list', ['model' => 'megaadverts']) }}">Мега Объявления</a></li>
            @endif

            @if(Auth::user()->checkPolicy('megaadvert_create'))
                <li><a href="{{ route('admin.create', ['model' => 'megaadverts']) }}">Создать</a></li>
            @endif
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('advert_search'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-5.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('admin.list', ['model' => 'search']) }}">Поиск объявлений</a></li>
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('banner_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-6.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
            @if(Auth::user()->checkPolicy('banner_list'))
                <li><a href="{{ route('admin.list', ['model' => 'banners']) }}">Размещенные Баннеры</a></li>
            @endif

            @if(Auth::user()->checkPolicy('banner_create'))
                <li><a href="{{ route('admin.create', ['model' => 'banners']) }}">Создать</a></li>
            @endif
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('blacklist_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-7.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('admin.list', ['model' => 'blacklist']) }}">Черный Список Номеров</a></li>
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('user_stat'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-8.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="#">Статистика пользователей</a></li>
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('user_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-9.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
            @if(Auth::user()->checkPolicy('user_list'))
                <li><a href="{{ route('admin.list', ['model' => 'users']) }}">Пользователи</a></li>
            @endif

            @if(Auth::user()->checkPolicy('user_create'))
                <li><a href="{{ route('admin.create', ['model' => 'users']) }}">Создать</a></li>
            @endif
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('group_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-9.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
            @if(Auth::user()->checkPolicy('group_list'))
                <li><a href="{{ route('admin.list', ['model' => 'groups']) }}">Группы пользователей</a></li>
            @endif

            @if(Auth::user()->checkPolicy('user_create'))
                <li><a href="{{ route('admin.create', ['model' => 'groups']) }}">Создать</a></li>
            @endif
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('video_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-10.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="#">Обучающие Видео-Ролики</a></li>
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('advertbill_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-11.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('admin.list', ['model' => 'advertbills']) }}">Счета для Объявлений</a></li>
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('bannerbill_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-12.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('admin.list', ['model' => 'bannerbills']) }}">Счета для Баннеров</a></li>
            </ul>
        </div>
    </div>
    @endif

    @if(Auth::user()->checkPolicy('advertrequest_show'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-13.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="#">Заявки на удаление объявлений</a></li>
            </ul>
        </div>
    </div>
    @endif

    

    @if(Auth::user()->checkPolicy('setting_show') || Auth::user()->checkPolicy('setting_list'))
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-14.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
            @if(Auth::user()->checkPolicy('setting_show'))
                <li><a href="{{ route('admin.list', ['model' => 'settings']) }}">Настройки сайта</a></li>
            @endif

            @if(Auth::user()->checkPolicy('setting_list'))
                <li><a href="#">Редактирование поисковых запросов</a></li>
            @endif
            </ul>
        </div>
    </div>
    @endif


    
    <!--<div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-15.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="#">Недвижимость</a></li>
                <li><a href="#">Бизнес</a></li>
                <li><a href="#">Работа</a></li>
            </ul>
        </div>
    </div>
    <div class="parameters-item block-left">
        <div class="parameters-thumb"><img src="img/admin/parameters/icon-16.png" alt=""></div>
        <div class="parameters-list-wrapper">
            <ul>
                <li><a href="{{ route('admin.list', ['model' => 'sites', 'type' => 0]) }}">Страницы партнеров</a></li>
                <li><a href="{{ route('admin.list', ['model' => 'sites', 'type' => 1]) }}">Магазины</a></li>
            </ul>
        </div>
    </div>-->
</div>