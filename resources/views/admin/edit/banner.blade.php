@extends('admin.dashboard')

@section('admincontent')

<div class="create-ad-wrapper ad-post">

    @if(isset($complete_messages))
        @foreach($complete_messages as $type => $messages)
            @foreach($messages as $message)
                <div class="alert alert-{{ $type }}">
                    <div class="glyphicon glyphicon-exclamation-sign"></div>
                    {{ $message }}
                </div>
            @endforeach
        @endforeach
    @endif

    <form action="{{ request()->input('id') ? route('admin.edit', ['model' => 'banners', 'id' => $banner->id]) : route('admin.create', ['model' => 'banners']) }}" id="create-ad" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ request()->input('id') }}">

        @include('elements.forms.wrapper', [
            'name' => 'url',
            'title' => 'Ссылка',
            'input' => 'text',
            'help' => '',
            'desc' => 'Укажите ссылку на страницу или ID объявления',
            'value' => request()->input('url'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'hover_text',
            'title' => 'Текст при наведении',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'value' => request()->input('hover_text'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'contact_information',
            'title' => 'Контактная информация',
            'input' => 'textarea',
            'help' => '',
            'desc' => '',
            'max' => 300,
            'value' => request()->input('contact_information'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'heading_id',
            'title' => 'Рубрика',
            'input' => 'select',
            'help' => 'Выберите рубрику',
            'desc' => 'Если не указывать, то баннер будет сквозным',
            'id' => 'categories',
            'options' => Besplatnee::headings()->getTreeArray(),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'position',
            'title' => 'Позиция',
            'input' => 'select',
            'help' => '',
            'desc' => 'Категория размещения баннера',
            'id' => 'categories',
            'options' => $positions,
        ])

        @include('elements.forms.wrapper', [
            'name' => 'size',
            'title' => 'Размер баннера',
            'input' => 'select',
            'help' => 'Количество занимаемых блоков (не учитывается для боковых баннеров)',
            'desc' => 'Поле в разработке',
            'id' => 'banner-size',
            'options' => [
                ['value' => '1x1','isroot' => false,'title' => '1x1','active' => request()->input('size', '1x1') == '1x1'],
                ['value' => '2x1','isroot' => false,'title' => '2x1','active' => request()->input('size', '1x1') == '2x1'],
                ['value' => '3x1','isroot' => false,'title' => '3x1','active' => request()->input('size', '1x1') == '3x1'],
            ],
        ])

        @include('elements.forms.wrapper', [
            'name' => 'block_number',
            'title' => 'Номер группы баннеров',
            'input' => 'select',
            'help' => 'Номер группы баннеров (для баннеров между объявлений)',
            'desc' => 'Группы нумеруются сверху вниз',
            'id' => 'banner-block-number',
            'options' => [
                ['value' => 0,'isroot' => false,'title' => 'Первая группа','active' => request()->input('block_number', 0) == 0],
                ['value' => 1,'isroot' => false,'title' => 'Вторая группа','active' => request()->input('block_number', 0) == 1],
                ['value' => 2,'isroot' => false,'title' => 'Третья группа','active' => request()->input('block_number', 0) == 2],
                ['value' => 3,'isroot' => false,'title' => 'Четвертая группа','active' => request()->input('block_number', 0) == 3],
                ['value' => 4,'isroot' => false,'title' => 'Пятая группа','active' => request()->input('block_number', 0) == 4],
                ['value' => 5,'isroot' => false,'title' => 'Шестая группа','active' => request()->input('block_number', 0) == 5],
                ['value' => 6,'isroot' => false,'title' => 'Седьмая группа','active' => request()->input('block_number', 0) == 6],
                ['value' => 7,'isroot' => false,'title' => 'Восьмая группа','active' => request()->input('block_number', 0) == 7],
                ['value' => 8,'isroot' => false,'title' => 'Девятая группа','active' => request()->input('block_number', 0) == 8],
                ['value' => 9,'isroot' => false,'title' => 'Десятая группа','active' => request()->input('block_number', 0) == 9],
            ],
        ])

        @include('elements.forms.wrapper', [
            'name' => 'banner_number',
            'title' => 'Позиция баннера в группе',
            'input' => 'select',
            'help' => '',
            'desc' => 'Для боковых баннеров отсчет идет сверху вниз. Для горизонтальных слева направо и сверху вниз. Например баннер во второй строке по середине имеет пятый номер.',
            'id' => 'banner-number',
            'options' => [
                ['value' => 0,'isroot' => false,'title' => 'Первый','active' => request()->input('banner_number', 0) == 0],
                ['value' => 1,'isroot' => false,'title' => 'Второй','active' => request()->input('banner_number', 0) == 1],
                ['value' => 2,'isroot' => false,'title' => 'Третий','active' => request()->input('banner_number', 0) == 2],
                ['value' => 3,'isroot' => false,'title' => 'Четвертый','active' => request()->input('banner_number', 0) == 3],
                ['value' => 4,'isroot' => false,'title' => 'Пятый','active' => request()->input('banner_number', 0) == 4],
                ['value' => 5,'isroot' => false,'title' => 'Шестой','active' => request()->input('banner_number', 0) == 5],
            ],
        ])

        @include('elements.forms.wrapper', [
            'name' => 'active',
            'title' => 'Баннер включен',
            'input' => 'radiogroup',
            'help' => '',
            'desc' => '',
            'values' => [1 => 'Да', 0 => 'Нет',],
        ])

        @include('elements.forms.wrapper', [
            'name' => 'image',
            'title' => 'Изображение',
            'input' => 'uploader',
            'help' => '',
            'desc' => 'Изображение баннера',
            'id' => 'addphotos',
            'upload_type' => 'image',
            'result_container' => '.photo-list',
            'result_template' => 'photo-item-single',
            'multiple' => false,
        ])


        <template name="photo-item-single">
            <div class="photo-item">
                <img src="#PREVIEW#">
                <input type="hidden" name="image" value="#DETAIL#">
            </div>
        </template>

        <div class="row photo-list">
            @if(request()->has('image'))
            <div class="photo-item">
                <img src="{{ request()->input('image') }}">
                <input type="hidden" name="image" value="{{ request()->input('image') }}">
            </div>
            @endif
        </div>

        @include('elements.forms.wrapper', [
            'name' => 'organization_id',
            'title' => 'Прикрепленная организация',
            'input' => 'select',
            'help' => 'Не прикреплять',
            'desc' => 'Баннеры закрепленные за одной организацией и размещенные в одном блоке объединяются в слайдер.',
            'id' => 'organization-id',
            'options' => \Besplatnee::organizations()->getListForForms(),
        ])

        @include('elements.forms.areapicker', [])

        <div class="create-ad-row ">
            <div class="row">
                <div class="col-xs-4">
                    <div class="create-ad-title">Детали счета</div>
                </div>
                <div class="col-xs-8" style="padding: 12px 24px;background: #eee;">
                    
                    <div class="row" style="margin-bottom: 8px">
                        <div class="col-xs-4">
                            <label for="period">Период</label>
                        </div>

                        <div class="col-xs-8">
                            <select name="period" style="width: 100%">
                                @foreach([0,1,2,3,4,5,8,12,16] as $period)
                                    <option value="{{ $period }}" {{ request()->input('period', 0) == $period ? 'selected' : '' }}>@lang('adverts.pickup.period.' . $period)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row" style="margin-bottom: 8px">
                        <div class="col-xs-4">
                            <label for="status">Оплата</label>
                        </div>

                        <div class="col-xs-8">
                            <select name="status" style="width: 100%">
                                @foreach([1,3,0,2] as $status)
                                    <option value="{{ $status }}" {{ request()->input('status', 0) == $status ? 'selected' : '' }}>@lang('adverts.pickup.status.' . $status)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row" style="margin-bottom: 8px">
                        <div class="col-xs-4">
                            <label for="price">Сумма</label>
                        </div>

                        <div class="col-xs-8">
                            <input type="text" name="price" value="{{ request()->input('price', 0) }}">&nbsp;р.
                        </div>
                    </div>

                    <div class="error-message"></div>
                </div>
            </div>
        </div>

        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection