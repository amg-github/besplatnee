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

    <form action="{{ request()->input('id') ? route('admin.edit', ['model' => 'headings', 'id' => $heading->id]) : route('admin.create', ['model' => 'headings']) }}" id="create-ad" method="post">

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


        @foreach($langs as $lang)

            @include('elements.forms.wrapper', [
                'name' => '',
                'color' => 'black',
                'title' => $lang->name,
                'input' => 'hidden',
                'help' => '',
                'desc' => '',
                'id' => 'lang_name_'.$lang->code,
                'params' => [
                ]
            ])

            @include('elements.forms.wrapper', [
                'name' => 'lang_name['.$lang->code.']',
                'title' => 'Название',
                'input' => 'text',
                'help' => '',
                'desc' => '',
                'id' => 'lang_name_'.$lang->code,
                'params' => [
                    'value'     => (isset($names[$lang->code]) ? $names[$lang->code] : '' ),
                ]
            ])

            @include('elements.forms.wrapper', [
                'name' => 'alias_local['.$lang->code.']',
                'title' => 'Алиас',
                'input' => 'text',
                'help' => '',
                'desc' => '',
                'id' => 'alias_'.$lang->code,
                'params' => [
                    'value' => (isset($aliases[$lang->code]) ? $aliases[$lang->code]->alias_local : '')
                ]
            ])

            @include('elements.forms.wrapper', [
                'name' => 'alias_international['.$lang->code.']',
                'title' => 'Алиас интернациональный',
                'input' => 'text',
                'help' => '',
                'desc' => '',
                'id' => 'alias_'.$lang->code,
                'params' => [
                    'value' => (isset($aliases[$lang->code]) ? $aliases[$lang->code]->alias_international : '')
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