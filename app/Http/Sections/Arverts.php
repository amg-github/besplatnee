<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminColumnEditable;
use AdminColumnFilter;
use SleepingOwl\Admin\Contracts\DisplayInterface;
use SleepingOwl\Admin\Contracts\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;
use \App\Advert;
use \App\Heading;
use \App\Property;
use \App\HeadingProperty;
use \App\AdvertProperty;
use \App\AdvertMedia;
use \App\City;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class Arverts
 *
 * @property \App\Advert $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Arverts extends Section implements Initializable
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    public function initialize()
    {
        $callback = function($config, \Illuminate\Database\Eloquent\Model $model) {
            $request = Request::instance();

            $model->properties()->detach();
            foreach($properties = Property::whereIn('id', array_keys($request->input('properties', [])))->get() as $property) {
                $pivotData = ['value' => $request->input('properties', [])[$property->id]];
                $model->properties()->attach($property->id, $pivotData);
            }
        };

        $this->updating($callback);
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::table()->with('heading')->with('owner')->with('phone')->with('city')
           ->setHtmlAttribute('class', 'table-primary')
           ->paginate(20);
        if(Auth::user()->isSuperAdmin()) {
            $display->setColumns(
               AdminColumn::link('id', '#')->setWidth('30px'),
               AdminColumnEditable::text('name', 'Имя')->setWidth('80px'),
               AdminColumnEditable::textarea('content', 'Содержимое')->setWidth('280px'),
               AdminColumn::text('city.name', 'Город'),
               AdminColumn::text('created_at', 'Дата добавления'),
               AdminColumn::text('clickcount', 'Кол-во просмотров')
           );
        } else {
            $display->setApply(function ($query) {
                $query->where('owner_id', Auth::user()->id);
            })->setColumns(
               AdminColumn::text('id', '#')->setWidth('30px'),
               AdminColumn::link('name', 'Имя'),
               AdminColumn::text('heading.name', 'Категория'),
               AdminColumn::custom('Активно', function ($model) { return $model->active ? 'Да' : 'Нет'; }),
               AdminColumn::custom('Заблокировано', function ($model) { return $model->blocked ? 'Да' : 'Нет'; })
           );
        }

        $display->setColumnFilters([
          null, // Не ищем по первому столбцу

          // Поиск текста
          AdminColumnFilter::text()->setPlaceholder('Название'),

          null,

          AdminColumnFilter::select(new City, 'Город')->setDisplay('name')->setPlaceholder('Выберите город')->setColumnName('city_id'),

          // Поиск по диапазону дат
          AdminColumnFilter::range()->setFrom(
              AdminColumnFilter::date()->setPlaceholder('От даты')->setFormat('d.m.Y')
          )->setTo(
              AdminColumnFilter::date()->setPlaceholder('До даты')->setFormat('d.m.Y')
          ),

          null
        ]);


        return $display;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        $properties = [];

        if($id) {
            $advert = Advert::where('id', $id)->first();
            if($advert && $advert->heading()) {
                foreach(HeadingProperty::where('heading_id', $advert->heading_id)->get() as $prop) {
                    $property = Property::where('id', $prop->property_id)->first();
                    $propValue = AdvertProperty::where('property_id', $property->id)->where('advert_id', $id)->first();
                    $propValue = $propValue ? $propValue->value : $property->default;
                    $properties[] = AdminFormElement::text('properties[' . $property->id . ']', $property->title)->setDefaultValue($propValue)->setValueSkipped(true);
                }
            }
        }

        if (!is_null($id)) { // Если галерея создана и у нее есть ID
            $photos = AdminDisplay::table()
                ->setModelClass(AdvertMedia::class) // Обязательно необходимо указать класс модели в которой хранятся фотографии
                ->setApply(function($query) use($id) {
                    $query->where('advert_id', $id); // Фильтруем список фотографий по ID галереи
                })
                ->setParameter('advert_id', $id) // При нажатии на кнопку "добавить" - подставлять идентификатор галереи
                ->setColumns(
                    AdminColumnEditable::select('type', 'Тип', ['image' => 'Изображение', 'video' => 'Видео', 'other' => 'Другой']),
                    AdminColumn::image('path', 'Файл')
                        ->setHtmlAttribute('class', 'text-center')
                        ->setWidth('100px')
                )
                ->setTitle('Связанные файлы');
        }

        if(Auth::user()->isSuperAdmin()) {

            return AdminForm::panel()->addBody(array_merge([
                    AdminFormElement::text('name', 'Имя')->required(),
                    AdminFormElement::select('owner_id', 'Владелец', \App\User::class)->setDisplay('email'),
                    AdminFormElement::select('heading_id', 'Категория', \App\Heading::class)->setDisplay('name'),
                    AdminFormElement::select('phone_id', 'Телефон', \App\Phone::class)->setDisplay('phone'),
                    AdminFormElement::select('city_id', 'Город', \App\City::class)->setDisplay('name'),
                    AdminFormElement::text('main_phrase', 'Главная фраза'),
                    AdminFormElement::ckeditor('content', 'Содержимое'),
                    AdminFormElement::text('street', 'Улица'),
                    AdminFormElement::text('house', 'Дом'),
                    AdminFormElement::checkbox('active', 'Активен'),
                    AdminFormElement::checkbox('blocked', 'Заблокирован'),
                    $photos
                ], $properties
            ));


        } else {
            return AdminForm::panel()->addBody(array_merge([
                    AdminFormElement::text('name', 'Имя')->required(),
                    AdminFormElement::select('city_id', 'Город', \App\City::class)->setDisplay('name'),
                    AdminFormElement::text('main_phrase', 'Главная фраза'),
                    AdminFormElement::ckeditor('content', 'Содержимое'),
                    AdminFormElement::text('street', 'Улица'),
                    AdminFormElement::text('house', 'Дом'),
                    $photos
                ], $properties
            ));
        }
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {
        // remove if unused
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }

    public function getTitle()
    {
        return 'Список объявлений';
    }
    /**
     * Переопределение метода содержащего заголовок создания записи
     *
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function getCreateTitle()
    {
        return 'Добавление объявления';
    }
}
