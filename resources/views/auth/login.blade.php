@extends('layouts.page')

@section('pagecontent')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <input type="hidden" name="__applicationKey" value="{{ request()->input('application') }}">

    @if($errors->has('user_inactive'))
        <div class="error">{{ $errors->first('user_inactive') }}</div>
    @endif

    @if($errors->has('need_verify'))

        <div class="error">{{ $errors->first('need_verify') }}</div>
        <input type="hidden" name="phone" value="{{ old('phone') }}">
        <input type="hidden" name="password" value="{{ old('password') }}">

        @include('elements.forms.wrapper', [
            'name' => 'verify_code',
            'title' => __('auth.verify_code'),
            'input' => 'text',
            'help' => __('auth.verify_code.help'),
            'desc' => __('auth.verify_code.description'),
        ])

    @else

        @include('elements.forms.wrapper', [
            'name' => 'phone',
            'title' => __('auth.phone'),
            'input' => 'phone',
            'help' => __('auth.phone.help'),
            'desc' => '',
        ])

        @include('elements.forms.wrapper', [
            'name' => 'password',
            'title' => __('auth.password'),
            'input' => 'password',
            'help' => __('auth.password.help'),
            'desc' => view('elements.forms.checkbox', [
                'name' => 'remember',
                'title' => __('auth.remember'),
            ]),
        ])

    @endif

    @include('elements.forms.button', [
        'title' => __('auth.system.login'),
        'type' => 'submit',
    ])

    <a class="btn btn-link" href="{{ route('password.request') }}">
        @lang('auth.forgot.password')
    </a>

    <a class="btn btn-link" href="{{ route('register') }}">
       @lang('auth.registration')
    </a>
</form>
@endsection
