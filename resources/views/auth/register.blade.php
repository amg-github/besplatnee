@extends('layouts.page')

@section('pagecontent')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    @include('elements.forms.wrapper', [
        'name' => 'fullname',
        'title' => __('auth.fullname'),
        'input' => 'text',
        'help' => __('auth.fullname.help'),
        'desc' => '',
    ])

    @include('elements.forms.wrapper', [
        'name' => 'email',
        'title' => __('auth.email'),
        'input' => 'text',
        'help' => __('auth.email.help'),
        'desc' => '',
    ])

    @include('elements.forms.wrapper', [
        'name' => 'phone',
        'title' => __('auth.phone'),
        'input' => 'phone',
        'help' => __('auth.phone.register.help'),
        'desc' => '',
    ])

    @include('elements.forms.wrapper', [
        'name' => 'password',
        'title' => __('auth.password'),
        'input' => 'password',
        'help' => __('auth.password.register.help'),
        'desc' => '',
    ])

    @include('elements.forms.wrapper', [
        'name' => 'password_confirmation',
        'title' => __('auth.password_confirmation'),
        'input' => 'password',
        'help' => __('auth.password_confirmation.help'),
        'desc' => '',
    ])

    @include('elements.forms.button', [
        'title' => __('auth.registration.submit'),
        'type' => 'submit',
    ])
</form>
@endsection
