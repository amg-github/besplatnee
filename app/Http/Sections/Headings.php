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
use App\Property;
use App\HeadingProperty;
use Illuminate\Support\Facades\Request;

/**
 * Class Headings
 *
 * @property \App\Heading $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Headings extends Section implements Initializable
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
            $pivotData = ['sort' => 0];
            foreach($properties = Property::whereIn('id', array_keys($request->input('properties', [])))->get() as $property) {
                $model->properties()->attach($property->id, $pivotData);
            }
        };

        $this->updating($callback);
        $this->created($callback);
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::table()->with('parent')->with('properties')
           ->setHtmlAttribute('class', 'table-primary')
           ->setColumns(
               AdminColumn::text('id', '#')->setWidth('30px'),
               AdminColumn::link('name', 'Имя'),
               AdminColumn::relatedLink('parent.name', 'Родительская категория'),
               AdminColumnEditable::checkbox('active')->setLabel('Активен')
           )->paginate(20);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {

        $properties = [];

        foreach(Property::all() as $prop) {
            $checked = HeadingProperty::where('heading_id', $id)->where('property_id', $prop->id)->exists() ? 1 : 0;
            $properties[] = AdminFormElement::checkbox('properties[' . $prop->id . ']', $prop->title)
                ->setDefaultValue($checked)->setValueSkipped(true);
        }

        return AdminForm::panel()->addBody(array_merge([
                AdminFormElement::text('name', 'Имя')->required(),
                AdminFormElement::text('allias', 'Псевдоним')->required(),
                AdminFormElement::select('parent_id', 'Категория', \App\Heading::class)->setDisplay('name')->nullable(),
                AdminFormElement::checkbox('active', 'Активен'),
            ], $properties
        ));
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
        return 'Список категорий';
    }
    /**
     * Переопределение метода содержащего заголовок создания записи
     *
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function getCreateTitle()
    {
        return 'Добавление категории';
    }

    public function created() {

    }
}
