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

    <form action="{{ request()->input('id') ? route('admin.edit', ['model' => 'geoobjects', 'id' => $gobject->id]) : route('admin.create', ['model' => 'geoobjects']) }}" id="create-ad" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ request()->input('id') }}">

        @include('elements.forms.wrapper', [
            'name' => 'parent_id[]',
            'title' => 'Страна',
            'input' => 'select',
            'help' => '',
            'desc' => '',
            'id' => 'select_countries',
            'options' => $countries,
            'params'    => [
                'value' => (isset($chain[0]) ? $chain[0]->id : ''),
            ]
        ])

        @include('elements.forms.wrapper', [
            'name' => 'parent_id[]',
            'title' => 'Регион',
            'input' => 'select',
            'help' => '',
            'desc' => '',
            'id' => 'select_regions',
            'options' => $regions,
            'params'    => [
                'value' => (isset($chain[1]) ? $chain[1]->id : ''),
            ]
        ])

        @include('elements.forms.wrapper', [
            'name' => '',
            'color' => 'black',
            'title' => 'Название',
            'input' => 'hidden',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_names',
            'params' => [
            ]
        ])

        @include('elements.forms.wrapper', [
            'name' => 'name',
            'title' => 'Именительный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_name',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'genitive_name',
            'title' => 'Родительный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_genitive_name',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'accusative_name',
            'title' => 'Винительный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_accusative_name',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'dative_name',
            'title' => 'Дательный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_dative_name',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'ergative_name',
            'title' => 'Предложный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_ergative_name',
        ])

        @include('elements.forms.wrapper', [
            'name' => '',
            'color' => 'black',
            'title' => 'Алиас',
            'input' => 'hidden',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_aliases',
            'params' => [
            ]
        ])

        @include('elements.forms.wrapper', [
            'name' => 'alias',
            'title' => 'Именительный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_alias'
        ])

        @include('elements.forms.wrapper', [
            'name' => 'genitive_alias',
            'title' => 'Родительный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_genitive_alias',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'accusative_alias',
            'title' => 'Винительный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_accusative_alias',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'dative_alias',
            'title' => 'Дательный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_dative_alias',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'ergative_alias',
            'title' => 'Предложный падеж',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'gobject_ergative_alias',
        ])


        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection