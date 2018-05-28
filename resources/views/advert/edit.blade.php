@extends('layouts.app')

@section('bodyclass', 'ad-post')

@section('content')
	<div class="block-left b-content-wrapper">

        <div class="ad-post-wrapper">

            <!-- Page title  -->

            <h1 class="page-title block-left">{{ request()->route()->controller->header() }}</h1>

            <!-- End page title -->

            <div class="clearfix"></div>

            <div class="create-ad-wrapper">

                <form action="{{ route('advert-save') }}" id="create-ad" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @foreach(request()->input('city_ids', []) as $city)
                        <input type="hidden" name="city_ids[]" value="{{ $city }}">
                    @endforeach
                    <input type="hidden" name="id" value="{{ request()->input('id') }}">

                    @include('elements.forms.wrapper', [
                        'name' => 'photos',
                        'title' => __('adverts.photos'),
                        'input' => 'uploader',
                        'help' => '',
                        'desc' => trans_choice('adverts.photos.description', 10),
                        'id' => 'addphotos',
                        'upload_type' => 'image',
                        'result_container' => '.photo-list',
                        'result_item' => 'photo-item'
                    ])

                    <div class="row photo-list">
                        @foreach(request()->input('photos') as $photo)
                        <div class="photo-item">
                            <button class="remove-btn" onclick="$(this).parent().remove();return false;">X</button>
                            <img src="{{ $photo }}">
                            <input type="hidden" name="photos[]" value="{{ $photo }}">
                        </div>
                        @endforeach
                    </div>

                    @include('elements.forms.wrapper', [
                        'name' => 'videos',
                        'title' => __('adverts.videos'),
                        'input' => 'uploader',
                        'help' => '',
                        'desc' => trans_choice('adverts.videos.description', 5),
                        'id' => 'addvideos',
                        'upload_type' => 'video',
                        'result_container' => '.video-list',
                        'result_item' => 'video-item'
                    ])

                    <div class="row video-list">
                        @foreach(request()->input('videos', []) as $video)
                        <div class="video-item">
                            <button class="remove-btn" onclick="$(this).parent().remove();return false;">X</button>
                            <img src="{{ $video }}">
                            <input type="hidden" name="videos[]" value="{{ $video }}">
                        </div>
                        @endforeach
                    </div>

                    @include('elements.forms.wrapper', [
                        'name' => 'heading_id',
                        'title' => __('adverts.heading'),
                        'input' => 'select',
                        'help' => __('adverts.heading.description'),
                        'desc' => '',
                        'id' => 'categories',
                        'options' => Besplatnee::headings()->getTreeArray(),
                    ])

                    {{-- @include('elements.forms.wrapper', [
                        'name' => 'city_id',
                        'title' => __('adverts.city'),
                        'input' => 'select',
                        'help' => __('adverts.description'),
                        'desc' => '',
                        'id' => 'cities',
                        'options' => Besplatnee::cities()->getTreeArray(),
                    ]) --}}

                    @include('elements.forms.wrapper', [
                        'name' => 'name',
                        'title' => __('adverts.name'),
                        'input' => 'text',
                        'help' => __('adverts.name.description'),
                        'desc' => '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'main_phrase',
                        'title' => '__('adverts.main_phrase'),
                        'input' => 'text',
                        'help' => __('adverts.main_phrase.description'),
                        'desc' => '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'content',
                        'title' => __('adverts.information'),
                        'input' => 'textarea',
                        'help' => __('adverts.information.description'),
                        'desc' => '',
                        'max' => 300,
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'price',
                        'title' => __('adverts.price'),
                        'input' => 'text',
                        'help' => __('adverts.price.description'),
                        'desc' => '',
                    ])

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
                                    }

                                    var properties = {!! json_encode(request()->input('properties', [])) !!}
                                    $.each(properties, function (name, value) {
                                        $contain.find('[name="properties[' + name + ']"]').val(value).trigger('change');
                                    });
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
                        'name' => 'extend_content',
                        'title' => __('adverts.extend_content'),
                        'input' => 'textarea',
                        'help' => __('adverts.extend_content.description'),
                        'desc' => '',
                        'max' => 300,
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'phone',
                        'title' => __('adverts.phone'),
                        'input' => Auth::check() ? 'hidden' : 'text',
                        'help' => __('adverts.phone.description'),
                        'desc' => view('elements.forms.checkbox', [
                            'name' => 'visibile-phone',
                            'title' => __('adverts.phone.hide'),
                        ]),
                        'value' => Auth::check() ? Auth::user()->phone : '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'fullname',
                        'title' => __('adverts.contact'),
                        'input' => Auth::check() ? 'hidden' : 'text',
                        'help' => __('adverts.contact.description'),
                        'desc' => '',
                        'value' => Auth::check() ? Auth::user()->fullname() : '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'site_url',
                        'title' => __('adverts.site'),
                        'input' => 'text',
                        'help' => __('adverts.site.description'),
                        'desc' => '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'email',
                        'title' => __('adverts.email'),
                        'input' => Auth::check() ? 'hidden' : 'text',
                        'help' => __('adverts.email.description'),
                        'desc' => '',
                        'value' => Auth::check() ? Auth::user()->email : '',
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
    @include('layouts.leftsidebar')
@endsection

@section('rightsidebar')
    @include('layouts.rightsidebar')
@endsection