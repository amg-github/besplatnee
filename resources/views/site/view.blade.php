<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ request()->route()->controller->title() }}</title>

    <base href="/">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="{{ request()->route()->controller->description() }}">
    <meta name="keywords" content="{{ request()->route()->controller->keywords() }}">
    <meta property="og:image" content="path/to/image.jpg">
    <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png">

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
</head>
<body class="version-b personal-site @yield('bodyclass')">
<div id="header">
    <div class="header-wrapper">
        <div class="fw-container">
            <div class="row">
                <div class="">
                    <div class="logotype-company col-xs-4">
                        <a href="{{ route('home') }}"><img src="{{ $site->logo }}" alt="Besplatnee.net"></a>
                    </div>
                    <div class="site-menu col-xs-8">
                        <ul>
                            @foreach($site->pages()->get() as $page)
                            <li><a href="{{ route('partnerpage', ['alias' =>  $site->allias, 'id' => $page->id]) }}">{{ $page->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="header-wrapper">
        <div class="fw-container">
            <div class="row">
                <div class="block-left">
                    <div class="logotype-company">
                        <div class="sub-category-title">Объявления</div>
                        <a href="/"><img src="img/logo.png" alt="Besplatnee.net"></a>
                    </div>
                </div>
                <div class="block-left">
                    <div class="main-user-zone-wrapper">
                        <div class="main-user-zone-top">
                            <div class="navigation-category-wrapper">
                                <div class="navigation-top clearfix">
                                    <div class="nav">
                                        <ul id="nav-top-navigation">
                                            @foreach($site->pages()->orderBy('sort')->get() as $page)
                                            <li><a href="#" class="active">{{ $page->name }}</a></li>
                                            @endforeach
                                            <li><a href="#">Новости</a></li>
                                            <li><a href="#">Скидки</a></li>
                                            <li><a href="#">Депозит</a></li>
                                            <li><a href="#">Бизнес</a></li>
                                            <li class="sub-nav more-links dropdown">
                                                <a id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Еще...</a>
                                                <div class="dropdown-menu" aria-labelledby="dLabel">
                                                    <div class="more-links-wrapper clearfix">
                                                        <div class="header-categories-all js-header-more-content">
                                                            <div class="header-categories-all-all"><a href="#">Все
                                                                категории</a></div>
                                                            <div class="header-categories-all-column-wrapper clearfix">
                                                                <div class="header-categories-all-column">
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Транспорт</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Автомобили</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Мотоциклы
                                                                                и мототехника</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Грузовики
                                                                                и спецтехника</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Водный
                                                                                транспорт</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Запчасти
                                                                                и аксессуары</a></li>
                                                                    </ul>
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Для
                                                                                дома и дачи</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Бытовая
                                                                                техника</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Мебель
                                                                                и интерьер</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Посуда
                                                                                и товары для кухни</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Продукты
                                                                                питания</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Ремонт
                                                                                и строительство</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Растения</a>
                                                                        </li>
                                                                    </ul>
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Для
                                                                                бизнеса</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Готовый
                                                                                бизнес</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Оборудование
                                                                                для бизнеса</a></li>
                                                                    </ul>
                                                                </div>
                                                                <div class="header-categories-all-column">
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Недвижимость</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Квартиры</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Комнаты</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Дома,
                                                                                дачи, коттеджи</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Земельные
                                                                                участки</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Гаражи
                                                                                и машиноместа</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Коммерческая
                                                                                недвижимость</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Недвижимость
                                                                                за рубежом</a></li>
                                                                    </ul>
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Бытовая
                                                                                электроника</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Аудио
                                                                                и видео</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Игры,
                                                                                приставки и программы</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Настольные
                                                                                компьютеры</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Ноутбуки</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Оргтехника
                                                                                и расходники</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Планшеты
                                                                                и электронные книги</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Телефоны</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Товары
                                                                                для компьютера</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Фототехника</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="header-categories-all-column">
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Работа</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Вакансии</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Резюме</a>
                                                                        </li>
                                                                    </ul>
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Услуги</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Предложение
                                                                                услуг</a></li>
                                                                    </ul>
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Хобби
                                                                                и отдых</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Билеты
                                                                                и путешествия</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Велосипеды</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Книги
                                                                                и журналы</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Коллекционирование</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Музыкальные
                                                                                инструменты</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Охота
                                                                                и рыбалка</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Спорт
                                                                                и отдых</a></li>
                                                                    </ul>
                                                                </div>
                                                                <div class="header-categories-all-column">
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Личные
                                                                                вещи</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Одежда,
                                                                                обувь, аксессуары</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Детская
                                                                                одежда и обувь</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Товары
                                                                                для детей и игрушки</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Часы
                                                                                и украшения</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Красота
                                                                                и здоровье</a></li>
                                                                    </ul>
                                                                    <ul class="header-categories-all-list">
                                                                        <li class="header-categories-all-item header-categories-all-item_parent">
                                                                            <a class="header-categories-all-link" href="#">Животные</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Собаки</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Кошки</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Птицы</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Аквариум</a>
                                                                        </li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Другие
                                                                                животные</a></li>
                                                                        <li class="header-categories-all-item ">
                                                                            <a class="header-categories-all-link" href="#">Товары
                                                                                для животных</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="nav-user-login-manipulation">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <a href="#">Вход</a>
                                        <span class="separation">/</span>
                                        <a href="#">Регистрация</a>
                                    </div>
                                    <div class="nav-location-wrapper">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        <a href="#">г.Петропавловск-Камчатский</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="main-user-zone-middle clearfix">
                            <div class="main-search-engine-wrapper">
                                <form action="" id="search-engine">
                                    <label for="input-searching">
                                        <input id="input-searching" placeholder="Поиск по объявлениям">
                                    </label>
                                    <label for="options-category">
                                        <select id="options-category">
                                            <option value="" disabled="" selected="">Все объявления</option>
                                            <option value="">Category 1</option>
                                            <option value="">Category 2</option>
                                            <option value="">Category 3</option>
                                            <option value="">Category 4</option>
                                        </select>
                                    </label>
                                    <label for="options-country">
                                        <select id="options-country">
                                            <option value="" disabled="" selected="">Территория поиска</option>
                                            <option value="">Территория 1</option>
                                            <option value="">Территория 2</option>
                                            <option value="">Территория 3</option>
                                            <option value="">Территория 4</option>
                                        </select>
                                    </label>
                                    <button id="search-engine-go">Найти</button>
                                </form>
                                <a href="#" class="power-search-engine">Расширенный поиск</a>
                            </div>
                        </div>
                        <div class="main-user-zone-bottom block-left">
                            <div class="navigation-category-wrapper clearfix">
                                <div class="nav">
                                    <ul id="nav-bottom-navigation">
                                        <li>
                                            <a href="#">Работа</a>
                                            <ul class="sub-nav-bottom-navigation">
                                                <li><a href="#">Автомобили ваз</a></li>
                                                <li><a href="#">Другие отечественные</a></li>
                                                <li><a href="#">Audi</a></li>
                                                <li><a href="#">BMW</a></li>
                                                <li><a href="#">Ford</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">Авто</a>
                                            <ul class="sub-nav-bottom-navigation">
                                                <li><a href="#">Автомобили ваз</a></li>
                                                <li><a href="#">Другие отечественные</a></li>
                                                <li><a href="#">Audi</a></li>
                                                <li><a href="#">BMW</a></li>
                                                <li><a href="#">Ford</a></li>
                                                <li><a href="#">Hyundai</a></li>
                                                <li><a href="#">Mazda</a></li>
                                                <li><a href="#">Mercedes</a></li>
                                                <li><a href="#">Mitsubishi</a></li>
                                                <li><a href="#">Nissan</a></li>
                                                <li><a href="#">Opel</a></li>
                                                <li><a href="#">Toyota</a></li>
                                                <li><a href="#">Volkswagen</a></li>
                                                <li><a href="#">Другие иномарки</a></li>
                                                <li><a href="#">Грузовые и автобусы</a></li>
                                                <li><a href="#">Спецтехника</a></li>
                                                <li><a href="#">Мототехника и другое</a></li>
                                                <li><a href="#">Куплю авто</a></li>
                                                <li><a href="#">Продам запчасти</a></li>
                                                <li><a href="#">Куплю запчасти</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Недвижимость</a></li>
                                        <li><a href="#">Услуги</a></li>
                                        <li class="sub-nav more-links dropdown">
                                            <a id="dLabell" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Еще...</a>
                                            <div class="dropdown-menu" aria-labelledby="dLabell">
                                                <div class="more-links-wrapper clearfix">
                                                    <div class="header-categories-all js-header-more-content">
                                                        <div class="header-categories-all-all"><a href="#">Все
                                                            категории</a></div>
                                                        <div class="header-categories-all-column-wrapper clearfix">
                                                            <div class="header-categories-all-column">
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Транспорт</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Автомобили</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Мотоциклы
                                                                            и мототехника</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Грузовики
                                                                            и спецтехника</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Водный
                                                                            транспорт</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Запчасти
                                                                            и аксессуары</a></li>
                                                                </ul>
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Для
                                                                            дома и дачи</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Бытовая
                                                                            техника</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Мебель
                                                                            и интерьер</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Посуда
                                                                            и товары для кухни</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Продукты
                                                                            питания</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Ремонт
                                                                            и строительство</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Растения</a>
                                                                    </li>
                                                                </ul>
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Для
                                                                            бизнеса</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Готовый
                                                                            бизнес</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Оборудование
                                                                            для бизнеса</a></li>
                                                                </ul>
                                                            </div>
                                                            <div class="header-categories-all-column">
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Недвижимость</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Квартиры</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Комнаты</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Дома,
                                                                            дачи, коттеджи</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Земельные
                                                                            участки</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Гаражи
                                                                            и машиноместа</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Коммерческая
                                                                            недвижимость</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Недвижимость
                                                                            за рубежом</a></li>
                                                                </ul>
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Бытовая
                                                                            электроника</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Аудио
                                                                            и видео</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Игры,
                                                                            приставки и программы</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Настольные
                                                                            компьютеры</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Ноутбуки</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Оргтехника
                                                                            и расходники</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Планшеты
                                                                            и электронные книги</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Телефоны</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Товары
                                                                            для компьютера</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Фототехника</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="header-categories-all-column">
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Работа</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Вакансии</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Резюме</a>
                                                                    </li>
                                                                </ul>
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Услуги</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Предложение
                                                                            услуг</a></li>
                                                                </ul>
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Хобби
                                                                            и отдых</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Билеты
                                                                            и путешествия</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Велосипеды</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Книги
                                                                            и журналы</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Коллекционирование</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Музыкальные
                                                                            инструменты</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Охота
                                                                            и рыбалка</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Спорт
                                                                            и отдых</a></li>
                                                                </ul>
                                                            </div>
                                                            <div class="header-categories-all-column">
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Личные
                                                                            вещи</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Одежда,
                                                                            обувь, аксессуары</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Детская
                                                                            одежда и обувь</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Товары
                                                                            для детей и игрушки</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Часы
                                                                            и украшения</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Красота
                                                                            и здоровье</a></li>
                                                                </ul>
                                                                <ul class="header-categories-all-list">
                                                                    <li class="header-categories-all-item header-categories-all-item_parent">
                                                                        <a class="header-categories-all-link" href="#">Животные</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Собаки</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Кошки</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Птицы</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Аквариум</a>
                                                                    </li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Другие
                                                                            животные</a></li>
                                                                    <li class="header-categories-all-item ">
                                                                        <a class="header-categories-all-link" href="#">Товары
                                                                            для животных</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="add-listing-wrapper block-right">
                            <a href="#" class="add-ad">
                                <div class="add-listing-item">
                                    <div class="add-thumbnail-img"><img src="img/icon-add-ad.png" alt=""></div>
                                    <div class="add-title">Подать объявление</div>
                                </div>
                            </a>
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

                        <h1>
                            @section('pagetitle')

                            @endsection
                        </h1>

                        @section('content')
                            @forelse(\App\Advert::where('active', true)->where('context_id', $site->id)->get() as $advert)
                                @include('advert.preview.text')
                            @empty
                                <p>Объявления не найдены</p>
                            @endforelse
                        @endsection

                        <!-- End render content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="footer" class="clearfix">
    <div class="footer-wrapper">
        <div class="footer-top">
            <div class="fw-container">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-content clearfix">
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <a href="#" class="logotype-company-footer">
                                <img src="{{ $site->logo }}" alt="">
                            </a>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-9">
                            <div class="row">
                                <div class="footer-company-desc">
                                    Текст для футера
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
                        <div class="copyright">(c) 2016. Копирайт.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/scripts.min.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>

</body>
</html>
