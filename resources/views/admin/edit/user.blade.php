@extends('admin.dashboard')

@section('admincontent')

<div class="create-ad-wrapper ad-post">

    <form action="{{ route('admin.edit', ['model' => 'users', 'id' => $user->id]) }}" id="create-ad" method="post" autocomplete="false">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ request()->input('id') }}">

        @include('elements.forms.wrapper', [
            'name' => 'phone',
            'title' => 'Основной телефон',
            'input' => 'phone',
            'help' => '',
            'desc' => '',
            'value' => request()->input('phone'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'password',
            'title' => 'Смена пароля',
            'input' => 'password',
            'help' => '',
            'desc' => view('elements.forms.checkbox', [
                'name' => 'new-password',
                'title' => 'Сменить пароль?',
            ]),
            'value' => request()->input('password'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'verify',
            'title' => 'Номер проверен?',
            'input' => 'select',
            'help' => '',
            'desc' => '',
            'id' => 'categories',
            'options' => [['title' => 'Да', 'value' => '1'],['title' => 'Нет', 'value' => '0']],
        ])

        @include('elements.forms.wrapper', [
            'name' => 'firstname',
            'title' => 'Фамилия',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'value' => request()->input('firstname'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'lastname',
            'title' => 'Имя',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'value' => request()->input('lastname'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'patronymic',
            'title' => 'Отчество',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'value' => request()->input('patronymic'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'group_id',
            'title' => 'Права доступа',
            'input' => 'select',
            'help' => '',
            'desc' => '',
            'id' => 'categories',
            'options' => $groups,
        ])
        
        @include('elements.forms.areapicker', [])

        @include('elements.forms.wrapper', [
            'name' => 'email',
            'title' => 'E-mail',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'value' => request()->input('email'),
        ])

        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection