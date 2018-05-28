@extends('layouts.page')

@section('pagecontent')
<form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}

    @if($errors->has('time'))
        <div class="error">{{ $errors->first('time') }}</div>

    @endif

    @if($errors->has('need_verify'))

        <div class="error">{{ $errors->first('need_verify') }}</div>
        <input type="hidden" name="phone" value="{{ old('phone') }}">

        @include('elements.forms.wrapper', [
            'name' => 'verify_code',
            'title' => 'Код подтверждения',
            'input' => 'text',
            'help' => 'Введите пароль из смс',
            'desc' => 'На ваш номер был выслан одноразовый пароль',
        ])

    @else 

        @include('elements.forms.wrapper', [
            'name' => 'phone',
            'title' => 'Телефон',
            'input' => 'phone',
            'help' => 'Введите номер телефона, указанный при регистрации',
            'desc' => '',
        ])

    @endif

    @include('elements.forms.button', [
        'title' => 'Восстановить пароль',
        'type' => 'submit',
    ])
</form>
@endsection
