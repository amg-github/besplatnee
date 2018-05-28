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

                <form action="{{ route('site.save') }}" id="create-ad" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    @include('elements.forms.wrapper', [
                        'name' => 'type',
                        'title' => 'Тип вашего сайта',
                        'input' => 'select',
                        'help' => 'Выберите тип',
                        'desc' => '',
                        'id' => 'categories',
                        'options' => [
                            ['title' => 'База данных', 'value' => 0],
                            ['title' => 'Магазин', 'value' => 1],
                        ],
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'name',
                        'title' => 'Заголовок',
                        'input' => 'text',
                        'help' => 'Введите название сайта',
                        'desc' => '',
                    ])

                    @include('elements.forms.wrapper', [
                        'name' => 'description',
                        'title' => 'Описание деятельности',
                        'input' => 'textarea',
                        'help' => 'Введите текст описания',
                        'desc' => '',
                        'max' => 300,
                    ])

                    {{-- @include('elements.forms.wrapper', [
                        'name' => 'allias',
                        'title' => 'Адрес',
                        'input' => 'text',
                        'help' => 'URI адрес вашего сайта',
                        'desc' => 'Если не указывать, то адрес сгенерируется на основе названия',
                    ]) --}}

                    @include('elements.forms.wrapper', [
                        'name' => 'logo',
                        'title' => 'Логотип',
                        'input' => 'uploader',
                        'help' => '',
                        'desc' => 'Логотип вашего сайта',
                        'id' => 'addphotos',
                        'upload_type' => 'image',
                        'result_container' => '.photo-list',
                        'result_item' => 'photo-item',
                        'multiple' => false,
                    ])

                    <div class="row photo-list">
                        @if(request()->has('logo'))
                        <div class="photo-item">
                            <img src="{{ request()->input('logo') }}">
                            <input type="hidden" name="logo" value="{{ request()->input('logo') }}">
                        </div>
                        @endif
                    </div>

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
@endsection

@section('leftsidebar')
    @include('layouts.leftsidebar')
@endsection

@section('rightsidebar')
    @include('layouts.rightsidebar')
@endsection