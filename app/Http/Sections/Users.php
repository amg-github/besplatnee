<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminColumnEditable;
use SleepingOwl\Admin\Contracts\DisplayInterface;
use SleepingOwl\Admin\Contracts\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;
use App\Group;
use App\Phone;
use App\City;

/**
 * Class Users
 *
 * @property \App\User $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Users extends Section
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

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::table()->with('phones')->with('groups')
           ->setHtmlAttribute('class', 'table-primary')
           ->setColumns(
               AdminColumn::text('id', '#')->setWidth('30px'),
               AdminColumn::link('email', 'E-mail'),
               AdminColumn::text('name', 'Имя'),
               AdminColumn::lists('groups.name', 'Группы')->setWidth('200px'),
               AdminColumnEditable::checkbox('active')->setLabel('Активен'),
               AdminColumnEditable::checkbox('blocked')->setLabel('Заблокирован')
           )->paginate(20);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return AdminForm::panel()->addBody([
            AdminFormElement::text('name', 'Имя')->required(),
            AdminFormElement::text('email', 'E-mail')->required()->unique(),
            AdminFormElement::multiselect('groups', 'Группы', Group::class)->setDisplay('name'),
            AdminFormElement::multiselect('phones', 'Телефоны', Phone::class)->setDisplay('phone')->taggable(),
            AdminFormElement::checkbox('active', 'Активен'),
            AdminFormElement::checkbox('blocked', 'Заблокирован'),
            AdminFormElement::select('gender', 'Пол', [0 => 'Выберите пол',1 => 'Мужской',2 => 'Женский']),
            AdminFormElement::image('photo', 'Фотография'),
        ]);
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
    /**
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function getTitle()
    {
        return 'Список пользователей';
    }
    /**
     * Переопределение метода содержащего заголовок создания записи
     *
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function getCreateTitle()
    {
        return 'Добавление пользователя';
    }
}
