@extends('admin.dashboard')

@section('admincontent')
@if(request()->input('id'))
<b>Добавил пользователь {{ $advert->creator->phone }} группы "{{ $advert->creator->groups()->first()->name }}" ({{ $advert->getCreatedAtForCurrentTimeZone() }})</b><br>
    @if($advert->owner)
        <b>Принадлежит пользователю {{ $advert->owner->phone }} группы "{{ $advert->owner->groups()->first()->name }}"</b>
    @endif
@endif

<style type="text/css">
    .bill-row-template {
        display: none!important;
    }

    .row-eq-height {
      display: -webkit-box;
      display: -webkit-flex;
      display: -ms-flexbox;
      display:         flex;
    }

    .table-besplatnee {
    }

    .table-besplatnee-row {
    }

    .table-besplatnee-col {
        padding: 5px;
        border: 1px solid gray;
    }
</style>

<div class="clone">
    <div class="row table-besplatnee-row row-eq-height bill-row-template" data-bill-id="" data-bill-number="#nnn#">
        <input type="hidden" name="bills[#nnn#][type]" value="">
        <input type="hidden" name="bills[#nnn#][change]" value="1">
        <div class="col-xs-1 table-besplatnee-col bill-index">#nnnn#</div>
        <div class="col-xs-3 table-besplatnee-col">
            <span>Мега-объявление</span>
            <input type="checkbox" data-mega-id="#nnn#" class="mega-checkbox" name="bills[#nnn#][mega]"><br>
            <div class="mega-advert" id="mega-#nnn#" style="display: none;" >
                <span>Первая строка</span>
                <input type="text" name="bills[#nnn#][mega_text_top]" style="width: 80%"><br>
                <span>Вторая строка</span>
                <input type="text" name="bills[#nnn#][mega_text_bottom]" style="width: 80%"><br>
            </div>
            <select name="bills[#nnn#][advert_template_id]" style="width: 80%">
                <option value="">Без выделения</option>
                @foreach(\App\AdvertTemplate::all() as $template)
                    <option value="{{ $template->id }}">@lang($template->name)</option>
                @endforeach
            </select>
        </div>            
        <div class="col-xs-3 table-besplatnee-col">
            <select name="bills[#nnn#][period]" style="width: 80%">
                @foreach([0,1,2,3,4,5,8,12,16] as $period)
                    <option value="{{ $period }}">@lang('adverts.pickup.period.' . $period)</option>
                @endforeach
            </select>
        </div>            
        <div class="col-xs-2 table-besplatnee-col">
            <select name="bills[#nnn#][status]" style="width: 80%">
                @foreach([1,3,0,2] as $status)
                    <option value="{{ $status }}">@lang('adverts.pickup.status.' . $status)</option>
                @endforeach
            </select>
        </div>            
        <div class="col-xs-2 table-besplatnee-col">
            <input type="text" name="bills[#nnn#][price]" value="" style="width: 80%">&nbsp;р.
        </div> 
        <div class="col-xs-1 table-besplatnee-col">
            <div class="admin-model-list">
                <a class="action delete-bill" href="#" data-bill-number="#nnn#" data-bill-id="" title="Удалить">
                    <img src="http://samara.besplatnee.net/img/post-params/3.png" title="">
                </a>
            </div>
        </div> 
    </div>
</div>

<div class="create-ad-wrapper ad-post">

    <form action="{{ request()->input('id') ? route('admin.edit', ['model' => 'adverts', 'id' => $advert->id]) : route('admin.create', ['model' => 'adverts']) }}" id="create-ad" method="post">


        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ request()->input('id') }}">
        <input type="hidden" name="send_to_print" value="{{ request()->input('send_to_print', 0) }}">

        @if(request()->input('alias_local') || request()->input('alias_international'))
            @include('elements.forms.wrapper', [
                'name' => 'alias_local',
                'title' => 'Алиас на русском',
                'input' => 'text',
                'desc' => '',
                'help' => 'Путь в адресной строке на русском',
                'value' => request()->input('alias_local'),
            ])

            @include('elements.forms.wrapper', [
                'name' => 'alias_international',
                'title' => 'Алиас на латинице',
                'input' => 'text',
                'desc' => '',
                'help' => 'Путь в адресной строке на латинице',
                'value' => request()->input('alias_international'),
            ])
        @endif

        @include('elements.forms.wrapper', [
            'name' => 'user.phone',
            'title' => 'Основной телефон',
            'input' => 'phone',
            'help' => 'Номер телефона владельца объявления',
            'desc' => view('elements.forms.checkbox', [
                'name' => 'visible-phone',
                'title' => 'Не показывать в объявлении',
            ]),
            'value' => request()->input('phone'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'fullname',
            'title' => 'Контактное лицо',
            'input' => 'text',
            'help' => 'Имя владельца объявления',
            'desc' => '',
            'value' => request()->input('fullname'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'user.email',
            'title' => 'E-mail',
            'input' => 'text',
            'help' => 'E-mail владельца объявления',
            'desc' => '',
            'value' => request()->input('email'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'active',
            'title' => 'Опубликовать объявление',
            'input' => 'checkbox',
            'help' => 'Выводить объявление на сайте',
            'desc' => '',
            'value' => 1,
        ])

        <input type="hidden" name="unpublished_on" value="0">
        <input type="hidden" name="accented" value="0">

        @include('elements.forms.wrapper', [
            'name' => 'unpublished_on',
            'title' => 'Объявление в архиве',
            'input' => 'checkbox',
            'help' => 'Объявление помечено как архивное',
            'desc' => '',
            'value' => '1',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'approved',
            'title' => 'Объявление проверено',
            'input' => 'checkbox',
            'help' => 'Состояние проверки объявления',
            'desc' => '',
            'value' => 1,
        ])

        @include('elements.forms.wrapper', [
            'name' => 'heading_id',
            'title' => 'Рубрика',
            'input' => 'select',
            'help' => 'Выберите рубрику',
            'desc' => '',
            'id' => 'categories',
            'options' => Besplatnee::headings()->getTreeArray(),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'vip',
            'title' => 'VIP объявление',
            'input' => 'vip',
            'help' => 'Выберите рубрику',
            'desc' => '',
            'id' => 'vip',
            'options' => [],
        ])

        @include('elements.forms.areapicker', [

        ])

        @include('elements.forms.wrapper', [
            'name' => 'name',
            'title' => 'Заголовок',
            'input' => 'text',
            'help' => 'Название объявления',
            'desc' => '',
            'max_length' => 40,
        ])

        @include('elements.forms.wrapper', [
            'name' => 'main_phrase',
            'title' => 'Главная фраза',
            'input' => 'text',
            'help' => 'Главная фраза объявления',
            'desc' => '',
            'max_length' => 50,
        ])

        @include('elements.forms.wrapper', [
            'name' => 'content',
            'title' => 'Информация',
            'input' => 'textarea',
            'help' => 'Текст объявления',
            'desc' => '',
            'max' => 300,
        ])

        @include('elements.forms.wrapper', [
            'name' => 'price',
            'title' => 'Цена',
            'input' => 'text',
            'help' => 'Цена',
            'desc' => '',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'extend_content',
            'title' => 'Дополнительная информация',
            'input' => 'textarea',
            'help' => 'Дополнительную информация',
            'desc' => '',
            'max' => 10000,
        ])

        @include('elements.forms.wrapper', [
            'name' => 'site_url',
            'title' => 'Сайт',
            'input' => 'text',
            'help' => 'Адрес сайта владельца объявления',
            'desc' => '',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'photos',
            'title' => 'Фотографии',
            'input' => 'uploader',
            'help' => '',
            'desc' => 'Возможно добавить до 10 фотографий',
            'id' => 'addphotos',
            'upload_type' => 'image',
            'result_container' => '.photo-list',
            'result_template' => 'photo-item-multiple'
        ])

        <template name="photo-item-multiple">
            <div class="photo-item">
                <button class="remove-btn" onclick="$(this).parent().remove();return false;">X</button>
                <img src="#PREVIEW#">
                <input type="hidden" name="photos[]" value="#DETAIL#">
            </div>
        </template>

        <div class="row photo-list">
            @foreach(request()->input('photos', []) as $photo)
            <div class="photo-item">
                <button class="remove-btn" onclick="$(this).parent().remove();return false;">X</button>
                <img src="{{ $photo }}">
                <input type="hidden" name="photos[]" value="{{ $photo }}">
            </div>
            @endforeach
        </div>

        @include('elements.forms.wrapper', [
            'name' => 'videos',
            'title' => 'Видео',
            'input' => 'uploader',
            'help' => '',
            'desc' => 'Возможно добавить до 5 видео файлов',
            'id' => 'addvideos',
            'upload_type' => 'video',
            'result_container' => '.video-list',
            'result_template' => 'video-item-multiple'
        ])

        <template name="video-item-multiple">
            <div class="video-item">
                <button class="remove-btn" onclick="$(this).parent().remove();return false;">X</button>
                <img src="#PREVIEW#">
                <input type="hidden" name="videos[]" value="#DETAIL#">
            </div>
        </template>

        <div class="row video-list">
            @foreach(request()->input('videos', []) as $video)
            <div class="video-item">
                <button class="remove-btn" onclick="$(this).parent().remove();return false;">X</button>
                <img src="{{ $video }}">
                <input type="hidden" name="videos[]" value="{{ $video }}">
            </div>
            @endforeach
        </div>

        <div class="advert-properties"></div>

        <template name="advert-property">
            <div class="create-ad-row ">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="create-ad-title">#PROPERTY_TITLE#</div>
                    </div>
                    <div class="col-xs-8">
                        #PROPERTY_INPUT#
                        <div class="create-ad-desc">#PROPERTY_DESCRIPTION#</div>
                        <div class="error-message">#PROPERTY_ERROR#</div>
                    </div>
                </div>
            </div>
        </template>

        @include('elements.forms.wrapper', [
            'name' => 'contacts',
            'title' => 'Дополнительные контакты',
            'input' => 'textarea',
            'help' => '',
            'desc' => '',
            'max' => 2000,
        ])

        @include('elements.forms.address', [
            'id' => 'yandexMapObject',
            'inputname' => 'address',
            'title' => 'Адрес',
            'help' => 'Адрес объявления',
            'desc' => 'Заполните поле адреса, или укажите метку на карте',
            'longname' => 'longitude',
            'latname' => 'latitude',
            'findtitle' => 'Найти на карте',

        ])

        <script type="text/javascript">
            

            document.addEventListener('DOMContentLoaded', function (event) {

                $(document).on('change', 'input[name="mega"]', function() {
                    $('.mega-advert').toggle();
                });

                $(document).on('change', '.mega-checkbox', function() {
                    $('#mega-'+$(this).data('mega-id')).toggle();
                });

                $(document).on('click', '.add-bill', function() {
                    var last_index = 0;
                    var clone = $('.clone').html();


                    if ($('.bill-row').length > 0 ) {
                        var last_index = $('.bill-row').last().data('bill-id') + 1;
                    }

                    clone = clone.replace( /#nnn#/g, last_index);
                    clone = clone.replace( /#nnnn#/g, last_index + 1);
                    clone = clone.replace("bill-row-template", "bill-row");

                    $('.table-besplatnee').append(clone);
                });

                $(document).on('click', '.delete-bill', function(e) {
                    if ($(this).data('bill-id') != "") {
                        $.get( "/admin/advertbills/remove/" + $(this).data('bill-id'));
                        $('.bill-row[data-bill-id="' + $(this).data('bill-id') + '"]').remove();
                    } else {
                        $('.bill-row[data-bill-number="' + $(this).data('bill-number') + '"]').remove();
                    }

                    return false;
                });


                $('#categories').on('change', function () {
                    var heading_id = $(this).val();

                    SITE.headings.getProperties($(this).val(), function (r) {
                        var $contain = $('.advert-properties');
                        $contain.html('');

                        if(r.success) {
                            $.each(r.data, function (idx, property) {
                                var $property = SITE.properties.builder.make($contain, property, 'advert-property');

                                $property.find('input,select,textarea').addClass('col-xs-12');

                                $contain.append($property);
                            });

                            var properties = {!! json_encode(request()->input('properties', [])) !!}
                            $.each(properties, function (name, value) {
                                $contain.find('[name="properties[' + name + ']"]').val(value).trigger('change');
                            });
                        }
                    });
                });

                var customInterval = setInterval(function () {
                    if(SITE.template != undefined) {
                        clearInterval(customInterval);
                        $('#categories').trigger('change');
                    }
                }, 500);
            });
        </script>


        <div class="create-ad-row table-besplatnee">
            <div class="row table-besplatnee-row row-eq-height">
                <div class="col-xs-1 table-besplatnee-col">
                    №
                </div>
                <div class="col-xs-3 table-besplatnee-col">
                    Тип
                </div>            
                <div class="col-xs-3 table-besplatnee-col">
                    Период
                </div>            
                <div class="col-xs-2 table-besplatnee-col">
                    Статус оплаты
                </div>            
                <div class="col-xs-2 table-besplatnee-col">
                    Сумма
                </div>      
                <div class="col-xs-1 table-besplatnee-col">
                </div>       
            </div>

            



            @forelse (request()->bills as $index => $bill)
                <div class="row table-besplatnee-row row-eq-height bill-row" data-bill-id="{{ $bill['id'] }}" data-bill-number="{{$index}}">
                    <input type="hidden" name="bills[{{ $index }}][type]" value="{{ $bill['type'] }}">
                    <input type="hidden" name="bills[{{ $index }}][change]" value="1">
                    <div class="col-xs-1 table-besplatnee-col">
                        {{ $index + 1}}
                    </div>
                    <div class="col-xs-3 table-besplatnee-col">
                        @if ($bill['type'] != "vip")
                            <span>Мега-объявление</span>
                            <input type="checkbox" data-mega-id="{{$index}}" class="mega-checkbox" name="bills[{{ $index }}][mega]" {{ $bill['type'] == "mega" ? "checked" : '' }}><br>
                            <div class="mega-advert" id="mega-{{$index}}" style="display: none;" >
                                <span>Первая строка</span>
                                <input type="text" name="bills[{{ $index }}][mega_text_top]" style="width: 80%"><br>
                                <span>Вторая строка</span>
                                <input type="text" name="bills[{{ $index }}][mega_text_bottom]" style="width: 80%"><br>
                            </div>
                            <select name="bills[{{ $index }}][advert_template_id]" style="width: 80%">
                                <option value="">Без выделения</option>
                                @foreach(\App\AdvertTemplate::all() as $template)
                                    <option value="{{ $template->id }}" {{ $bill['advert_template_id'] == $template->id ? 'selected' : '' }}>@lang($template->name)</option>
                                @endforeach
                            </select>
                        @else
                            <span>VIP</span>
                        @endif
                    </div>            
                    <div class="col-xs-3 table-besplatnee-col">
                        <span>Годен до: {{ $bill["deleted_at"].' '.(\Carbon\Carbon::parse($bill["deleted_at"])->lt(\Carbon\Carbon::now()) ? '(Просрочен)' : '(Действителен)') }}</span><br>
                        <select name="bills[{{ $index }}][period]" style="width: 80%">
                            @foreach([0,1,2,3,4,5,8,12,16] as $period)
                                <option value="{{ $period }}">@lang('adverts.pickup.period.' . $period)</option>
                            @endforeach
                        </select>
                    </div>            
                    <div class="col-xs-2 table-besplatnee-col">
                        <select name="bills[{{ $index }}][status]" style="width: 80%">
                            @foreach([1,3,0,2] as $status)
                                <option value="{{ $status }}" {{ $bill['status'] == $status ? 'selected' : '' }}>@lang('adverts.pickup.status.' . $status)</option>
                            @endforeach
                        </select>
                    </div>            
                    <div class="col-xs-2 table-besplatnee-col">
                        <input type="text" name="bills[{{ $index }}][price]" value="{{ $bill['price'] }}" style="width: 80%">&nbsp;р.
                    </div> 
                    <div class="col-xs-1 table-besplatnee-col">
                        <div class="admin-model-list">
                            <a class="action delete-bill" href="#" data-bill-number="{{$index}}" data-bill-id="{{ $bill['id'] }}" title="Удалить">
                                <img src="http://samara.besplatnee.net/img/post-params/3.png" title="">
                            </a>
                        </div>
                    </div> 
                </div>
            @empty
                <div class="row table-besplatnee-row">
                    <div class="col-xs-12">
                        Пусто
                    </div>
                </div>
            @endforelse
            
        </div>
        <div class="add-post-params clearfix">
            <div class="buttons block-right">
                <button class="block-left add-bill" type="button"><span>Добавить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>
            </div>
        </div>


        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

            <div class="block-right" style="margin-top: 1.66667em; margin-right: 40px">
                <input type="hidden" name="actions[deleted]" value="0">
                <label for="actions-deleted">Удалить объявление?&nbsp;&nbsp;&nbsp;
                    <input id="actions-deleted" type="checkbox" name="actions[deleted]" value="1" {{ $advert->trashed() ? 'checked' : '' }}>
                </label>
            </div>

        </div>

    </form>

</div>
@endsection