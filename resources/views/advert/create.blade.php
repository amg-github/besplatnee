@extends('layouts.app')

@section('bodyclass', 'ad-post')
@section('bottombanners', '')
@section('topbanners', '')

@section('content')
	<div class="block-left b-content-wrapper" style="width: 88.3%;">

        <div class="ad-post-wrapper">

            <!-- Page title  -->

            <h1 class="page-title block-left">{{ request()->route()->controller->header() }}</h1>

            <!-- End page title -->

            <div class="clearfix"></div>

            <div class="create-ad-wrapper">

                <form action="{{ route('advert-save') }}" id="create-ad" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="city_ids[]" value="{{ \Config::get('area')->id }}">
                    <input type="hidden" name="id" value="{{ request()->input('id', 0) }}">
                    <input type="hidden" name="active" value="{{ request()->input('active', 0) }}">

                    <div class="create-ad-row ">
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="create-ad-title" style="">Тип объявления</div>
                            </div>
                            <div class="col-xs-8">
                                <label>
                                    <input id="advert_type" type="radio" name="advert_type" value="" {{ request()->has('mega') ? "" : "checked" }}>
                                    <span>Обычное объявление</span><br>
                                    <span class="advert-type-preview"><img src="/img/advert/default.png" alt=""></span>
                                </label>
                                <label>
                                    <input id="advert_type" type="radio" name="advert_type" value="mega" {{ request()->has('mega') ? "checked" : "" }}>
                                    <span>Мега-объявление</span>
                                    <span class="advert-type-preview"><img src="/img/advert/mega.png" alt=""></span>

                                </label>
                                <label>
                                    <input id="advert_type" type="radio" name="advert_type" value="accented">
                                    <span>Выделенное объявление</span>
                                    <span class="advert-type-preview"><img src="/img/advert/accented.png" alt=""></span>

                                </label>
                                <div class="create-ad-desc">Выберите тип создаваемого объявления</div>
                                <div class="error-message"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mega-advert" style="{{ request()->has('mega') ? "" : "display: none;" }}" >

                        @include('elements.forms.wrapper', [
                            'name' => 'mega_text_top',
                            'title' => 'Первая строка',
                            'input' => 'text',
                            'help' => '',
                            'desc' => '',
                        ])

                        @include('elements.forms.wrapper', [
                            'name' => 'mega_text_bottom',
                            'title' => 'Вторая строка',
                            'input' => 'text',
                            'help' => '',
                            'desc' => '',
                        ])

                    </div>

                    <div class="accented" style="display: none;" >
                        <div class="create-ad-row">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="create-ad-title" style="">Тип выделения</div>
                                </div>
                                <div class="col-xs-8">
                                    <select name="template_id">
                                        <option value="">Без выделения</option>
                                        @foreach(\App\AdvertTemplate::all() as $template)
                                            <option value="{{ $template->id }}" {{ request()->input('template_id', 0) == $template->id ? 'selected' : '' }}>@lang($template->name)</option>
                                        @endforeach
                                    </select>
                                   <div class="create-ad-desc"></div>
                                   <div class="error-message"></div>
                               </div>
                           </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        document.addEventListener("DOMContentLoaded", function () {
                            $(document).on('change', 'input[name="advert_type"]', function() {
                                switch ($(this).val()) {
                                    case 'mega':
                                        $('.mega-advert').show();
                                        $('.accented').show();
                                    break;

                                    case 'accented': 
                                        $('.mega-advert').hide();
                                        $('.accented').show();
                                    break;

                                    default:
                                        $('.mega-advert').hide();
                                        $('.accented').hide();
                                    break;
                                }
                            });
                        });
                    </script>

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

                    @if(!Auth::check())
                        @include('elements.forms.wrapper', [
                            'name' => 'user.phone',
                            'title' => __('adverts.phone'),
                            'input' => 'phone',
                            'help' => __('adverts.phone.description'),
                            'desc' => view('elements.forms.checkbox', [
                                'name' => 'visible-phone',
                                'title' => __('adverts.phone.hide'),
                            ]),
                            'value' => '',
                        ])
                    @else
                        @include('elements.forms.wrapper', [
                            'name' => 'user.phone',
                            'title' => __('adverts.phone'),
                            'input' => 'hidden',
                            'help' => __('adverts.phone.description'),
                            'desc' => view('elements.forms.checkbox', [
                                'name' => 'visible-phone',
                                'title' => __('adverts.phone.hide'),
                            ]),
                            'value' => Auth::user()->phone,
                        ])
                    @endif

                    @include('elements.forms.wrapper', [
                        'name' => 'photos',
                        'title' => __('adverts.photos'),
                        'input' => 'uploader',
                        'help' => '',
                        'desc' => trans_choice('adverts.photos.description', 10),
                        'id' => 'addphotos',
                        'upload_type' => 'image',
                        'result_container' => '.photo-list',
                        'result_template' => 'photo-item-multiple'
                    ])

                    <template name="photo-item-multiple">
                        <div class="photo-item" style="background: url(#PREVIEW#); background-size: cover; background-position: center;">
                            <button class="remove-btn" onclick="$(this).parent().remove();return false;">X</button>
                            <input type="hidden" name="photos[]" value="#DETAIL#">
                        </div>
                    </template>

                    <template name="photo-item-single">
                        <div class="photo-item">
                            <img src="#PREVIEW#">
                            <input type="hidden" name="photos" value="#DETAIL#">
                        </div>
                    </template>

                    <div class="row photo-list"></div>
                    <script type="text/javascript">
                        document.addEventListener("DOMContentLoaded", function () {
                            $(document).on('change', 'input[name="mega"]', function() {
                                $('.mega-advert').toggle();
                                $('.accented').toggle();
                            });
                            var photoInterval_handler = setInterval(function () {
                                if(SITE.template) {
                                    clearInterval(photoInterval_handler);
                                    @foreach(request()->input('photos', []) as $photo)

                                        
                                        $('.photo-list').append(SITE.template("photo-item-multiple", {
                                            detail: '{!! $photo !!}',
                                            preview: '{!! $photo !!}'
                                        }));

                                    @endforeach
                                }
                            }, 100);

                        });
                    </script>

                    @include('elements.forms.wrapper', [
                        'name' => 'videos',
                        'title' => __('adverts.videos'),
                        'input' => 'uploader',
                        'help' => '',
                        'desc' => trans_choice('adverts.videos.description', 5),
                        'id' => 'addvideos',
                        'upload_type' => 'video',
                        'result_container' => '.video-list',
                        'result_template' => 'video-item-multiple'
                    ])

                    <template name="video-item-multiple">
                        <div class="video-item" style="background: url(#PREVIEW#); background-size: cover; background-position: center;">
                            <button class="remove-btn" onclick="$(this).parent().remove();return false;">X</button>
                            <input type="hidden" name="videos[]" value="#DETAIL#">
                        </div>
                    </template>

                    <div class="row video-list"></div>

                    <script type="text/javascript">
                        document.addEventListener("DOMContentLoaded", function () {
                            var videoInterval_handler = setInterval(function () {
                                if(SITE.template) {
                                    clearInterval(videoInterval_handler);
                                    @foreach(request()->input('videos', []) as $video)

                                        
                                        $('.video-list').append(SITE.template("video-item-multiple", {
                                            detail: '{!! $video !!}',
                                            preview: 'https://i1.ytimg.com/vi/{!! $video !!}/default.jpg'
                                        }));

                                    @endforeach
                                }
                            }, 100);

                        });
                    </script>

                    @include('elements.forms.wrapper', [
                        'name' => 'heading_id',
                        'title' => __('adverts.heading'),
                        'input' => 'select',
                        'help' => __('adverts.heading.description'),
                        'desc' => '',
                        'id' => 'categories',
                        'options' => Besplatnee::headings()->getTreeArray(),
                    ])


                    @include('elements.forms.wrapper', [
                        'name' => 'name',
                        'title' => __('adverts.name'),
                        'input' => 'text',
                        'help' => __('adverts.name.description'),
                        'desc' => '',
                        'max_length' => 40,
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'main_phrase',
                        'title' => __('adverts.main_phrase'),
                        'input' => 'text',
                        'help' => __('adverts.main_phrase.description'),
                        'desc' => '',
                        'max_length' => 50,
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'content',
                        'title' => __('adverts.information'),
                        'input' => 'textarea',
                        'help' => __('adverts.information.description'),
                        'desc' => '',
                        'max' => 300,
                    ])

                    <div class="create-ad-row ">
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="create-ad-title">@lang('adverts.price')</div>
                            </div>
                            <div class="col-xs-8">
                                <input type="text" class="col-xs-9 input-numeric" placeholder="@lang('adverts.price.description')" name="price" value="{{ request()->input('price') }}" data-decimals="2" data-decsep="," data-thousanssep=" " data-min="0" data-decsep-secounds=".">
                                <select class="col-xs-3" style="height: 26px;">
                                    @foreach(\Besplatnee::cities()->getCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ config('area')->country()->getProps()['currency'] == $currency->id ? 'selected' : '' }}>{{ $currency->sign }} @lang($currency->name)</option>
                                    @endforeach
                                </select>
                                <div class="create-ad-desc"></div>
                                <div class="error-message">{{ $errors->first('price') }}</div>
                            </div>
                        </div>
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

                    <script type="text/javascript">
                        document.addEventListener('DOMContentLoaded', function (event) {
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


                                        propertyValues = {!! collect(request()->input('properties', []))->toJson() !!};
                                        $.each(r.data, function (idx, property) {
                                            if(propertyValues[property.name]) {
                                                $contain.find('[data-name="' + property.name + '"]').val(propertyValues[property.name]);
                                                $contain.find('[data-name="' + property.name + '"]').trigger('change');
                                            }
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

                    @include('elements.forms.wrapper', [
                        'name' => 'site_url',
                        'title' => __('adverts.site'),
                        'input' => 'text',
                        'help' => __('adverts.site.description'),
                        'desc' => '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'extend_content',
                        'title' => __('adverts.extend_content'),
                        'input' => 'textarea',
                        'help' => __('adverts.extend_content.description'),
                        'desc' => '',
                        'max' => 10000,
                    ])
                    </script>

                    @include('elements.forms.wrapper', [
                        'name' => 'fullname',
                        'title' => __('adverts.contact'),
                        'input' => 'text',
                        'help' => __('adverts.contact.description'),
                        'desc' => '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'user.email',
                        'title' => __('adverts.email'),
                        'input' => 'text',
                        'help' => __('adverts.email.description'),
                        'desc' => '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'contacts',
                        'title' => __('adverts.contacts'),
                        'input' => 'textarea',
                        'help' => __('adverts.contacts.description'),
                        'desc' => '',
                        'max' => 2000,
                    ])

                    @include('elements.forms.address', [
                        'id' => 'yandexMapObject',
                        'inputname' => 'address',
                        'title' => __('adverts.address'),
                        'help' => __('adverts.address.help'),
                        'desc' => __('adverts.address.description'),
                        'longname' => 'longitude',
                        'latname' => 'latitude',
                        'findtitle' => __('adverts.address.find'),

                    ])

                    <div class="add-post-params clearfix">

                        <div class="buttons block-right">

                            <a class="block-left add-create-next" href="#" onclick="event.preventDefault();
                                                 document.getElementById('create-ad').submit();"><span>@lang('site.phrases.next')</span><i class="fa fa-angle-right" aria-hidden="true"></i></a>

                        </div>

                    </div>

                </form>

            </div>



        </div>
        

    </div>
@endsection

@section('leftsidebar')
    
@endsection

@section('rightsidebar')
    <div class="block-left right-sidebar-banner-wrapper">
        @include('elements.banners.right', [
            'category' => null,
            'block_number' => 0,
        ])
        @include('elements.banners.right', [
            'category' => null,
            'block_number' => 1,
        ])
    </div>
@endsection