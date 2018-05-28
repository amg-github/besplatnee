<?php

namespace App\Http\Sections;

use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;
use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminColumnEditable;
use Illuminate\Support\Facades\Request;

/**
 * Class AdvertMedia
 *
 * @property \App\AdvertMedia $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class AdvertMedia extends Section
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
        // remove if unused
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        $request = Request::instance();

        return AdminForm::panel()->addBody([
            AdminFormElement::text('name', 'Название')->required(),
            AdminFormElement::select('type', 'Тип файла', ['default' => 'Общий тип', 'image' => 'Изображение', 'video' => 'Видео']),
            AdminFormElement::upload('path', 'Файл')->required(),
            AdminFormElement::select('advert_id', 'Объявление')
                ->setModelForOptions(\App\Advert::class, 'name')
                ->setDisplay('name')
                ->setDefaultValue($request->input('advert_id', 0))
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
}
