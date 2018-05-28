<?php

use Illuminate\Database\Seeder;
use \App\Facades\Besplatnee;
use Illuminate\Database\Eloquent\Model;

class HeadingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

        $headings = require(dirname(__FILE__) . '/headings.php');

        foreach($headings as $heading) {
            Besplatnee::headings()->add($heading);
        }

        Model::unguard();
        /*$h = Besplatnee::headings()->add(['name' => 'Транспорт', 'show_in_top_menu' => true,]);
        Besplatnee::headings()->add(['name' => 'Автомобили', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Мотоциклы и мототехника', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Грузовики и спецтехника', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Водный транспорт', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Запчасти и аксессуары', 'parent_id' => $h->id]);

        $h->properties()->save(new \App\Property([
        	'name' => 'model',
        	'title' => 'Модель',
        	'type' => 'text',
        ]));

        $h->properties()->save(new \App\Property([
        	'name' => 'year_of_issue',
        	'title' => 'Год выпуска',
        	'type' => 'year',
        ]));

        $h->properties()->save(new \App\Property([
        	'name' => 'mileage',
        	'title' => 'Пробег',
        	'type' => 'text',
        ]));

        $h->properties()->save(new \App\Property([
        	'name' => 'color',
        	'title' => 'Цвет',
        	'type' => 'text',
        ]));

        $h->properties()->save(new \App\Property([
        	'name' => 'fuel_type',
        	'title' => 'Тип топлива',
        	'type' => 'select',
        	'default' => 'Бензин||Дизельное топливо||Смешнанный тип||Солнечные батареи||Газ'
        ]));

        $h->properties()->save(new \App\Property([
        	'name' => 'accidents',
        	'title' => 'Аварии',
        	'type' => 'boolean',
        ]));

        $h->properties()->save(new \App\Property([
        	'name' => 'number_vin',
        	'title' => '№ VIN',
        	'type' => 'text',
        ]));

        $h->properties()->save(new \App\Property([
        	'name' => 'registration_certificate',
        	'title' => 'Свидельство о регистрации',
        	'type' => 'boolean',
        ]));

        $h->properties()->save(new \App\Property([
        	'name' => 'number',
        	'title' => 'Номер',
        	'type' => 'text',
        ]));

        $h = Besplatnee::headings()->add(['name' => 'Для дома и дачи']);
        Besplatnee::headings()->add(['name' => 'Бытовая техника', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Мебель и интерьер', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Посуда и товары для кухни', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Продукты питания', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Ремонт и строительство', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Растения', 'parent_id' => $h->id]);

        $h = Besplatnee::headings()->add(['name' => 'Для бизнеса']);
        Besplatnee::headings()->add(['name' => 'Готовый бизнес', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Оборудование для бизнеса', 'parent_id' => $h->id]);

        $h = Besplatnee::headings()->add(['name' => 'Недвижимость', 'show_in_top_menu' => true,]);
        Besplatnee::headings()->add(['name' => 'Квартиры', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Комнаты', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Дома, дачи, коттеджи', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Земельные участки', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Гаражи и машиноместа', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Коммерческая недвижимость', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Недвижимость за рубежом', 'parent_id' => $h->id]);

        $h = Besplatnee::headings()->add(['name' => 'Бытовая электроника']);
        Besplatnee::headings()->add(['name' => 'Аудио и видео', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Игры, приставки и программы', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Настольные компьютеры', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Ноутбуки', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Оргтехника и расходники', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Планшеты и электронные книги', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Ноутбуки', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Телефоны', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Товары для компьютера', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Фототехника', 'parent_id' => $h->id]);

        $h = Besplatnee::headings()->add(['name' => 'Работа', 'show_in_top_menu' => true,]);
        Besplatnee::headings()->add(['name' => 'Вакансии', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Резюме', 'parent_id' => $h->id]);

        $h->properties()->save(new \App\Property([
            'name' => 'field_of_activity',
            'title' => 'property.field_of_activity',
            'type' => 'select',
            'default' => 'property.field_of_activity.urgently_required||'
        ]));

        $h = Besplatnee::headings()->add(['name' => 'Услуги', 'show_in_top_menu' => true,]);
        Besplatnee::headings()->add(['name' => 'Предложение услуг', 'parent_id' => $h->id]);

        $h = Besplatnee::headings()->add(['name' => 'Хобби и отдых']);
        Besplatnee::headings()->add(['name' => 'Билеты и путешествия', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Велосипеды', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Книги и журналы', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Коллекционирование', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Музыкальные инструменты', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Охота и рыбалка', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Спорт и отдых', 'parent_id' => $h->id]);

        $h = Besplatnee::headings()->add(['name' => 'Личные вещи']);
        Besplatnee::headings()->add(['name' => 'Одежда, обувь, аксессуары', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Детская одежда и обувь', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Товары для детей и игрушки', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Часы и украшения', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Красота и здоровье', 'parent_id' => $h->id]);
        
        $h = Besplatnee::headings()->add(['name' => 'Животные']);
        Besplatnee::headings()->add(['name' => 'Собаки', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Кошки', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Птицы', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Аквариум', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Другие животные', 'parent_id' => $h->id]);
        Besplatnee::headings()->add(['name' => 'Товары для животных', 'parent_id' => $h->id]);*/
    }
}
