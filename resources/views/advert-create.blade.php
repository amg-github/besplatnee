@extends('layouts.app')

@section('bodyclass', 'ad-post')

@section('content')
    <div class="block-left b-content-wrapper">


        <div class="ad-post-wrapper">

            <!-- Page title  -->

            <h1 class="page-title block-left">{{ $pagetitle }}</h1>

            <!-- End page title -->



            <div class="add-post-params post-item-params block-right">

                <ul class="smart-settings-post-user block-left">

                    <li><a href=""><img src="img/post-params/1.png" alt=""></a></li>

                    <li><a href=""><img src="img/post-params/2.png" alt=""></a></li>

                    <li><a href=""><img src="img/post-params/3.png" alt=""></a></li>

                    <li><a href=""><img src="img/post-params/4.png" alt=""></a></li>

                </ul>

            </div>



            <div class="clearfix"></div>

            <div class="create-ad-wrapper">

                <form action="{{ route('advert-save') }}" id="create-ad" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="create-ad-row">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-title">Фотографии</div>

                                <div class="create-ad-desc">Возможно добавить до 10 фотографий</div>

                            </div>

                            <div class="col-xs-8">

                                <button id="add-photos" onclick="photoAdd(event);"></button>
                                <input type="file" id="file-uploader" style="position: absolute; opacity: 0" multiple onchange="photoSelected(this)">

                            </div>

                        </div>

                        <div class="row photo-list">
                            @foreach(Request::input('photos', []) as $photo)
                            <div class="col-xs-3 photo-item">
                                <input type="hidden" name="photos[]" value="{{ $photo }}">
                                <img src="{{ $photo }}" style="width: 100%;cursor: pointer" onclick="removePhoto(event, this);">
                            </div>
                            @endforeach
                        </div>

                    </div>

                    <div class="create-ad-row">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-title">Видео</div>

                                <div class="create-ad-desc">Возможно добавить до 5 видео файлов</div>

                            </div>

                            <div class="col-xs-8">

                                <button id="add-videos" onclick="videoAdd(event);"></button>
                                <input type="file" id="video-uploader" style="position: absolute; opacity: 0" multiple onchange="videoSelected(this)">

                            </div>

                        </div>

                        <div class="row video-list">
                            @foreach(Request::input('videos', []) as $video)
                            <div class="col-xs-3 video-item">
                                <input type="hidden" name="videos[]" value="{{ $video }}">
                                <span style="width: 100%;cursor: pointer" onclick="removeVideo(event, this);">Видео № {{ $loop->index + 1 }}</span>
                            </div>
                            @endforeach
                        </div>

                    </div>

                    <div class="create-ad-row {{ $errors->has('phone') ? 'error' : '' }}">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-title">Основной телефон</div>

                                <div class="create-ad-desc">

                                    <label for="visibile-phone">

                                        <input id="visibile-phone" type="checkbox" name="show_phone" />

                                        <span>Не показывать в объявлении</span>

                                    </label>

                                </div>

                            </div>

                            <div class="col-xs-8">
                                @if(Auth::check())
                                    <p>{{ Auth::user()->phone }}</p>
                                @else      
                                    <input class="col-xs-9"  type="text" placeholder="Укажите номер телефона для связи" name="phone" value="{{ $user_phone }}">
                                @endif

                            </div>

                        </div>

                    </div>

                    <div class="create-ad-row {{ $errors->has('heading_id') ? 'error' : '' }}">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-title">Рубрика</div>

                            </div>

                            <div class="col-xs-8">

                                <select class="col-xs-9"  id="categories" name="heading_id" onchange="categoryChange(this);">

                                    <option disabled {{ !Request::input('heading_id') ? 'selected' : ''}}>Выберите категорию</option>

                                    @foreach (App\Facades\Besplatnee::headings()->getTree() as $heading)
                                    <option value="{{ $heading->id }}" {{ Request::input('heading_id') == $heading->id ? 'selected' : ''}}
                                    data-properties="@foreach ($heading->properties as $prop){{ $prop->id }},@endforeach"
                                    >{{ $heading->name }}</option>
                                        @foreach ($heading->childrens as $children) 
                                            <option value="{{ $children->id }}" {{ Request::input('heading_id') == $children->id ? 'selected' : ''}}
                                    data-properties="@foreach ($children->properties as $prop){{ $prop->id }},@endforeach"
                                    >{{ $children->name }}</option>
                                        @endforeach
                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                    <div class="create-ad-row {{ $errors->has('name') ? 'error' : '' }}">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-title">Заголовок</div>

                            </div>

                            <div class="col-xs-8">

                                <input type="text" class="col-xs-9" placeholder="Введите название объявления" name="name" value="{{ Request::input('name') }}">

                            </div>

                        </div>

                    </div>

                    <div class="create-ad-row {{ $errors->has('main_phrase') ? 'error' : '' }}">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-title">Главная фраза</div>

                            </div>

                            <div class="col-xs-8">

                                <input type="text" class="col-xs-9" placeholder="Введите главную фразу объявления" name="main_phrase" value="{{ Request::input('main_phrase') }}">

                            </div>

                        </div>

                    </div>

                    <div class="create-ad-row">
                        <div class="row">
                            <
                        </div>
                    </div>

                    <div class="create-ad-row {{ $errors->has('city_id') ? 'error' : '' }}">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-title">Город</div>

                            </div>

                            <div class="col-xs-8">

                                <select class="col-xs-9"  id="cities" name="city_id">

                                    <option disabled {{ !Request::input('city_id') ? 'selected' : ''}}>Выберите город</option>

                                    @foreach (App\Facades\Besplatnee::cities()->getAll() as $city)
                                    <option value="{{ $city->id }}" {{ Request::input('city_id') == $city->id ? 'selected' : ''}}>{{ $city->name }}</option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                    <div class="create-ad-row {{ $errors->has('content') ? 'error' : '' }}">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-desc">Информация</div>

                            </div>

                            <div class="col-xs-8">

                                <textarea class="col-xs-9" rows="8" name="content" placeholder="Введите текст объявления">{{ Request::input('content') }}</textarea>

                                <div class="clearfix"></div>

                                <div class="textarea-count">Осталось символов: <span>100</span></div>

                            </div>

                        </div>

                    </div>

                    @foreach ($properties as $property)
                    <div class="create-ad-row {{ $errors->has('properties.' . $property->id) ? 'error' : '' }}" style="display: none" data-property-id="{{ $property->id }}">

                        <div class="row">

                            <div class="col-xs-4">

                                <div class="create-ad-title">{{ $property->title }}</div>

                            </div>

                            <div class="col-xs-8">

                                <input type="text" class="col-xs-9" placeholder="{{ $property->description }}" name="properties[{{ $property->id }}]" value="{{ Request::input('properties.' . $property->id) or $property->default }}">

                            </div>

                        </div>

                    </div>
                    @endforeach

                    <div class="add-post-params clearfix">

                        <div class="buttons block-right">

                            <a class="block-left add-create-next" href="#" onclick="event.preventDefault();
                                                 document.getElementById('create-ad').submit();"><span>Далее</span><i class="fa fa-angle-right" aria-hidden="true"></i></a>

                        </div>

                    </div>

                </form>

            </div>



        </div>
        

    </div>

    <script type="text/javascript">
        function categoryChange (el) {
            $('[data-property-id]').hide();
            $(el).find('option:selected').attr('data-properties').split(',').forEach(function (item) {
                $('[data-property-id="' + item + '"]').show();
            });
        }

        function photoAdd(e) {

            $('#file-uploader').trigger('click');

            e.stopPropagation();
            e.preventDefault();
        }

        function removePhoto(e, photo) {
            $(photo).parent().remove();

            e.stopPropagation();
            e.preventDefault();
        }

        function photoSelected(el) {
            console.log(el.files);

            var formData = new FormData();
            
            $.each(el.files, function (i, f) {
                formData.append('uploadfiles[]', f, f.name);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fileupload') }}',
                data: formData,
                dataType: 'json',
                async: false,
                type: 'post',
                processData: false,
                contentType: false,
                success: function(res){
                    console.log(res);
                    $.each(res, function (i, filename) {
                        $('.photo-list').append('<div class="col-xs-3 photo-item"><input type="hidden" name="photos[]" value="' + filename + '"><img src="' + filename + '" style="width: 100%;cursor: pointer" onclick="removePhoto(event, this);"></div>');
                    });
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest.responseText);
                }
            });
        }

        function videoAdd(e) {

            $('#video-uploader').trigger('click');

            e.stopPropagation();
            e.preventDefault();
        }

        function videoSelected(el) {
            console.log(el.files);

            var formData = new FormData();
            
            $.each(el.files, function (i, f) {
                formData.append('uploadfiles[]', f, f.name);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fileupload') }}',
                data: formData,
                dataType: 'json',
                async: false,
                type: 'post',
                processData: false,
                contentType: false,
                success: function(res){
                    console.log(res);
                    $.each(res, function (i, filename) {
                        $('.video-list').append('<div class="col-xs-3 video-item"><input type="hidden" name="videos[]" value="' + filename + '"><span  style="width: 100%;cursor: pointer" onclick="removeVideo(event, this);">Видео №' + ($('.video-item').length + 1) + '</span></div>');
                    });
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest.responseText);
                }
            });
        }

        function removeVideo(e, photo) {
            $(photo).parent().remove();

            e.stopPropagation();
            e.preventDefault();
        }
    </script>
@endsection

@section('leftsidebar')
    @include('layouts.leftsidebar')
@endsection

@section('rightsidebar')
    @include('layouts.rightsidebar')
@endsection