@extends('admin.dashboard')

@section('admincontent')

<div class="create-ad-wrapper ad-post">

    <form action="{{ request()->input('id') ? route('admin.edit', ['model' => $model, 'id' => $megaadvert->id]) : route('admin.create', ['model' => $model]) }}" id="create-ad" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ request()->input('id') }}">
        <input type="hidden" name="position" value="advert">

        @if($megaadvert->url > 0)
        <a href="{{ route('admin.edit', ['model' => 'adverts', 'id' => $megaadvert->url]) }}" target="__blank">Перейти к редактированию закрепленного объявления</a>
        <br>
        @endif

        @include('elements.forms.wrapper', [
            'name' => 'bannerimage',
            'name_index' => 'text_top',
            'title' => 'Первая строка',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'value' => request()->input('bannerimage.text_top'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'bannerimage',
            'name_index' => 'text_bottom',
            'title' => 'Вторая строка',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'value' => request()->input('bannerimage.text_bottom'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'url',
            'title' => 'Ссылка или номер объявления',
            'input' => 'text',
            'help' => '',
            'desc' => '',
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
            'name' => 'bannerimage',
            'name_index' => 'border_color',
            'title' => 'Цвет рамки',
            'input' => 'color',
            'help' => 'Цвет обрамляющей линии',
            'desc' => '',
            'value' => request()->input('bannerimage.border_color'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'bannerimage',
            'name_index' => 'background',
            'title' => 'Цвет фона',
            'input' => 'color',
            'help' => '',
            'desc' => '',
            'value' => request()->input('bannerimage.background'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'bannerimage',
            'name_index' => 'font_color',
            'title' => 'Цвет текста',
            'input' => 'color',
            'help' => '',
            'desc' => '',
            'value' => request()->input('bannerimage.font_color'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'bannerimage',
            'name_index' => 'font_size',
            'title' => 'Величина текста',
            'input' => 'number',
            'help' => '',
            'desc' => '',
            'value' => request()->input('bannerimage.font_size', 12),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'bannerimage',
            'name_index' => 'border_width',
            'title' => 'Толщина рамки',
            'input' => 'number',
            'help' => '',
            'desc' => '',
            'value' => request()->input('bannerimage.border_width', 1),
        ])

        <div id="banner-image" class="col-xs-8 col-xs-offset-4">
            {!! $bannerImage->getImage(450, 118) !!}
        </div>

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                $(document).on('keyup', '[name="bannerimage[text_top]"]', function () {
                    console.log($(this).val());
                    $('#banner-image .text_top').html($(this).val());
                });

                $(document).on('keyup', '[name="bannerimage[text_bottom]"]', function () {
                    console.log($(this).val());
                    $('#banner-image .text_bottom').html($(this).val());
                });
                
                $(document).on('change', '[name="bannerimage[border_color]"]', function () {
                    console.log($(this).val());
                    $('#banner-image .banner-image').css('border-color', $(this).val());
                });
                
                $(document).on('keyup', '[name="bannerimage[border_width]"]', function () {
                    console.log($(this).val());
                    $('#banner-image .banner-image').css('border-width', $(this).val() + 'px');
                });
                
                $(document).on('change', '[name="bannerimage[background]"]', function () {
                    console.log($(this).val());
                    $('#banner-image .banner-image').css('background', $(this).val());
                });
                
                $(document).on('keyup', '[name="bannerimage[font_size]"]', function () {
                    console.log($(this).val());
                    $('#banner-image .banner-image').css('font-size', $(this).val() + 'px');
                });
                
                $(document).on('change', '[name="bannerimage[font_color]"]', function () {
                    console.log($(this).val());
                    $('#banner-image .banner-image').css('color', $(this).val());
                });
            })
        </script>

        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection