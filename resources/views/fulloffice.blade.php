@extends('layouts.app')

@section('bodyclass', 'ad-post')

@section('content')
<div class="c-content-wrapper">

    <!-- Post list -->
    <div class="posts-wrapper">
        <!-- Page title  -->
        <h1 class="page-title">Административная часть</h1>
        <!-- End page title -->

        <!-- Render result module -->
        <div class="result-wrapper">

            <div class="personal-account-wrapper">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="account-welcome">Здравствуйте, <a href="#">{{ Auth::user()->name }}</a>!</div>
                    </div>
                    <div class="col-xs-9">
                        <ul class="account-settings-one-tabs">
                            <li><a href="#">На главную страницу Админ-Центра</a></li>
                            <li><a href="{{ route('home') }}">На главную страницу сайта</a></li>
                            <li><a href="#">Сменить города</a></li>
                            <li><a href="{{ route('home') }}">Выйти</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="account-parameters-wrapper clearfix">
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-1.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Учет посетителей</a></li>
                            <li><a href="#">Подано объявлений</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-2.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Скачать файл с объявлениями</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-3.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="{{ route('office', ['model' => 'adverts']) }}">Объявления</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-4.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Мега Объявления</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-5.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Поиск объявлений</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-6.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Размещенные Баннеры</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-7.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="{{ route('office', ['model' => 'phones', 'action' => 'list', 'filter' => 'blacklist']) }}">Черный Список Номеров</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-8.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Статистика пользователей</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-9.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="{{ route('office', ['model' => 'users']) }}">Пользователи</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-10.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Обучающие Видео-Ролики</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-11.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Счета для Объявлений</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-12.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Счета для Баннеров</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-13.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Заявки на удаление объявлений</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
                    <div class="parameters-thumb"><img src="img/admin/parameters/icon-14.png" alt=""></div>
                    <div class="parameters-list-wrapper">
                        <ul>
                            <li><a href="#">Настройки сайта</a></li>
                            <li><a href="#">Редактирование поисковых запросов</a></li>
                        </ul>
                    </div>
                </div>
                <div class="parameters-item block-left">
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
                            <li><a href="#">Страницы партнеров</a></li>
                            <li><a href="#">Магазины</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <!-- End render result module -->
    </div>
    <!-- End post list -->

    @if($action == 'list') 
        @include('admin.list')
    @endif

    @if($action == 'edit') 
        @include('admin.edit')
    @endif

    @if($action == 'create') 
        @include('admin.create')
    @endif

</div>
@endsection

@section('leftsidebar')
    
@endsection

@section('rightsidebar')
    
@endsection