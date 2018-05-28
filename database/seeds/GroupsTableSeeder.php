<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('groups')->insert([
        	'name' => 'Пользователь',
        	'policies' => json_encode([
                'heading_show', // просмотр списка объявлений
                'advert_show', // просмотр страницы с объявлением
                'advert_create', // создание объявлений от своего имени
                'advert_edit', // редактирование своих объявлений
                'cpanel_show', // переход в личный кабинет
                'advert_select', // выделение своих объявлений
                'site_create', // создание своего сайта
                'site_edit', // редактирование своего сайта
                'advert_favorite', // добавление и просмотр избраного
                'mega_create', // работа со своими мега-объявлениями
                'mega_edit', // работа со своими мега-объявлениями
                'site_show', // просмотр сайтов
            ]),
            'parent_id' => 0,
        ]);

        DB::table('groups')->insert([
        	'name' => 'Владелец',
        	'sudo' => '1', // все доступы
        	'policies' => json_encode([]),
            'parent_id' => 0,
        ]);

        DB::table('groups')->insert([
            'name' => 'Претендент на должность менеджера',
            'policies' => json_encode([
                'video_show', // просмотр обучающих роликов
                'admin_show', // переход в админку в разрешенных городах
            ]),
            'parent_id' => 1,
        ]);

        DB::table('groups')->insert([
            'name' => 'Секретарь',
            'sudo' => '1',
            'policies' => json_encode([
                'advert_bind', // указание во владельцах объявлений других пользователей в разрешенных городах
                'advert_approved', // одобрение чужих объявлений в разрешенных городах
                'advert_archive', // перемещение в архив чужих объявлений в разрешенных городах
                'advert_remove', // удаление чужих объявлений в разрешенных городах
                'user_blocked', // работа с черных списком
                'user_unblocked', // работа с черных списком
                'admin_import', // работа с выгрузкой
            ]),
            'parent_id' => 3,
        ]);

        DB::table('groups')->insert([
            'name' => 'Секретарь (платные объявления)',
            'sudo' => '1',
            'policies' => json_encode([
                'site_bind', // указывать во владельцах сайтов других пользователей
            ]),
            'parent_id' => 4,
        ]);

        DB::table('groups')->insert([
            'name' => 'Менеджер по городу',
            'sudo' => '1',
            'policies' => json_encode([
                'banner_create', // добавление рекламы в разрешенных городах
                'banner_edit', // редактирование своей рекламы
                'banner_disable', // отключение своей рекламы
            ]),
            'parent_id' => 5,
        ]);

        DB::table('groups')->insert([
            'name' => 'Старший менеджер по городу',
            'sudo' => '1',
            'policies' => json_encode([
                'mega_bind', // работа с мега-объявлениями в разрешенных городах
            ]),
            'parent_id' => 6,
        ]);

        DB::table('groups')->insert([
            'name' => 'Проверяющий',
            'sudo' => '1',
            'policies' => json_encode([]),
            'parent_id' => 1,
        ]);

        DB::table('groups')->insert([
            'name' => 'Помощник руководителя',
            'sudo' => '1',
            'policies' => json_encode([
                'user_create', // создание пользователей
                'user_edit', // Редактирование пользователей
                'user_remove', // удаление пользователей
                'admin_stats', // просмотр статистики и аналитики
            ]),
            'parent_id' => 7,
        ]);

        DB::table('groups')->insert([
            'name' => 'Руководитель',
            'sudo' => '1',
            'policies' => json_encode([
                'user_policies', // выставление прав доступа для пользователей и групп
                'banner_check', // счета на рекламу
                'advert_check', // счета на объявления
                'admin_search', // редактирование поисковых запросов
            ]),
            'parent_id' => 8,
        ]);

        DB::table('group_user')->insert([
        	'user_id' => '1',
        	'group_id' => '2',
        ]);

        DB::table('group_user')->insert([
            'user_id' => '2',
            'group_id' => '2',
        ]);
    }
}
