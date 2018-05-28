@extends('admin.dashboard')

@section('admincontent')

<div class="create-ad-wrapper ad-post">

    <form action="{{ route('admin.create', ['model' => 'headings']) }}" id="create-ad" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ request()->input('id') }}">
        @include('elements.forms.wrapper', [
            'name' => 'name',
            'title' => 'Код рубрики',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'heading_code',
            'params' => [
                'value' => $heading->code
            ]
        ])


        @foreach($aliases as $alias)

            @include('elements.forms.wrapper', [
                'name' => '',
                'color' => 'black',
                'title' => $langs[$alias->language]->name,
                'input' => 'hidden',
                'help' => '',
                'desc' => '',
                'id' => 'lang_name_'.$alias->language,
                'params' => [
                ]
            ])

            @include('elements.forms.wrapper', [
                'name' => 'lang_name['.$alias->language.']',
                'title' => 'Название',
                'input' => 'text',
                'help' => '',
                'desc' => '',
                'id' => 'lang_name_'.$alias->language,
                'params' => [
                    'value'     => Lang::get($heading->name, array(), $alias->language),
                ]
            ])

            @include('elements.forms.wrapper', [
                'name' => 'alias_local['.$alias->language.']',
                'title' => 'Алиас',
                'input' => 'text',
                'help' => '',
                'desc' => '',
                'id' => 'alias_'.$alias->language,
                'params' => [
                    'value' => $alias->alias_local
                ]
            ])

            @include('elements.forms.wrapper', [
                'name' => 'alias_international['.$alias->language.']',
                'title' => 'Алиас интернациональный',
                'input' => 'text',
                'help' => '',
                'desc' => '',
                'id' => 'alias_'.$alias->language,
                'params' => [
                    'value' => $alias->alias_international
                ]
            ])
        @endforeach


        @include('elements.forms.wrapper', [
            'name' => 'parent_id',
            'title' => 'Родительская рубрика',
            'input' => 'select',
            'help' => '',
            'desc' => '',
            'id' => 'categories',
            'options' => $categories,
        ])

        @include('elements.forms.wrapper', [
            'name' => 'sortindex',
            'title' => 'Порядок сортировки',
            'input' => 'number',
            'help' => 'От 0 до 999',
            'desc' => '',
            'id' => 'sortindex',
        ])

        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection