<?php

use SleepingOwl\Admin\Navigation\Page;

// Default check access logic
// AdminNavigation::setAccessLogic(function(Page $page) {
// 	   return auth()->user()->isSuperAdmin();
// });
//
// AdminNavigation::addPage(\App\User::class)->setTitle('test')->setPages(function(Page $page) {
// 	  $page
//		  ->addPage()
//	  	  ->setTitle('Dashboard')
//		  ->setUrl(route('admin.dashboard'))
//		  ->setPriority(100);
//
//	  $page->addPage(\App\User::class);
// });
//
// // or
//
// AdminSection::addMenuPage(\App\User::class)

return [

    [
        'title' => 'Учет посетителей',
        'icon' => 'img/admin/parameters/icon-1.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Учет посетителей')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
            (new Page(\App\Advert::class))
                ->setIcon('fa fa-group')
                ->setPriority(100)
                ->setTitle('Подано объявлений')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Учет посетителей',
        'icon' => 'img/admin/parameters/icon-2.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\Advert::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Скачать файл с объявлениями')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Объявления',
        'icon' => 'img/admin/parameters/icon-3.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\Advert::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Объявления'),
            (new Page(\App\Heading::class))
                ->setIcon('fa fa-group')
                ->setPriority(100)
                ->setTitle('Список категорий')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
            (new Page(\App\Property::class))
                ->setIcon('fa fa-group')
                ->setPriority(200)
                ->setTitle('Список фильтров')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Мега объявления',
        'icon' => 'img/admin/parameters/icon-4.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\Advert::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Мега объявления')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],



    [
        'title' => 'Поиск объявлений',
        'icon' => 'img/admin/parameters/icon-5.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\Advert::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Поиск объявлений')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Размещенные баннеры',
        'icon' => 'img/admin/parameters/icon-6.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\Advert::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Размещенные баннеры')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Черный список номеров',
        'icon' => 'img/admin/parameters/icon-7.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\Phone::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Черный список номеров')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Статистика пользователей',
        'icon' => 'img/admin/parameters/icon-8.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Статистика пользователей')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Пользователи',
        'icon' => 'img/admin/parameters/icon-9.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setPriority(0)
                ->setTitle('Пользователи')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
            (new Page(\App\Group::class))
                ->setPriority(100)
                ->setTitle('Группы пользователей')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Обучающие Видео-Ролики',
        'icon' => 'img/admin/parameters/icon-10.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Обучающие Видео-Ролики')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Счета для Объявлений',
        'icon' => 'img/admin/parameters/icon-11.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Счета для Объявлений')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Счета для Баннеров',
        'icon' => 'img/admin/parameters/icon-12.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Счета для Баннеров')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Заявки на удаление объявлений',
        'icon' => 'img/admin/parameters/icon-13.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Заявки на удаление объявлений')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Настройки сайта',
        'icon' => 'img/admin/parameters/icon-14.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Настройки сайта')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
             (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Редактирование поисковых запросов')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Недвижимость',
        'icon' => 'img/admin/parameters/icon-15.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Недвижимость')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Бизнес')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Работа')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    [
        'title' => 'Настройки сайта',
        'icon' => 'img/admin/parameters/icon-16.png',
        'priority' =>'10000',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Страницы партнеров')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
             (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Магазины')
                ->setAccessLogic(function () {
                    return Auth::user()->isSuperAdmin();
                }),
        ]
    ],

    // Examples
    // [
    //    'title' => 'Content',
    //    'pages' => [
    //
    //        \App\User::class,
    //
    //        // or
    //
    //        (new Page(\App\User::class))
    //            ->setPriority(100)
    //            ->setIcon('fa fa-user')
    //            ->setUrl('users')
    //            ->setAccessLogic(function (Page $page) {
    //                return auth()->user()->isSuperAdmin();
    //            }),
    //
    //        // or
    //
    //        new Page([
    //            'title'    => 'News',
    //            'priority' => 200,
    //            'model'    => \App\News::class
    //        ]),
    //
    //        // or
    //        (new Page(/* ... */))->setPages(function (Page $page) {
    //            $page->addPage([
    //                'title'    => 'Blog',
    //                'priority' => 100,
    //                'model'    => \App\Blog::class
	//		      ));
    //
	//		      $page->addPage(\App\Blog::class);
    //	      }),
    //
    //        // or
    //
    //        [
    //            'title'       => 'News',
    //            'priority'    => 300,
    //            'accessLogic' => function ($page) {
    //                return $page->isActive();
    //		      },
    //            'pages'       => [
    //
    //                // ...
    //
    //            ]
    //        ]
    //    ]
    // ]
];