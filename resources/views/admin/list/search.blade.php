@extends('admin.dashboard')

@section('admincontent')

<center><h1 class="page-title">Поиск объявлений</h1></center>

@if(request()->isMethod('post'))
    <div class="row">
        <p class="col-xs-12" style="text-align: center;">Задан некорректный поисковый запрос</p>
    </div>
@endif

<form action="{{ route('admin.list', ['model' => $model]) }}" method="post" class="admin-filters">
    {{ csrf_field() }}

    <fieldset class="row">
        <label class="col-xs-offset-3 col-xs-6">Ключевое слово или ID:&nbsp;</label>
        <input class="col-xs-offset-3 col-xs-6" type="text" name="search">
    </fieldset>

    <fieldset class="row">
        <label class="col-xs-offset-5 col-xs-4">
            <input type="checkbox" name="cities[]" value="{{ \Config::get('area')->id }}" checked>
            Только в {{ \Config::get('area')->getInName() }}
        </label>
    </fieldset>

    <fieldset class="row">
        <label class="col-xs-offset-5 col-xs-4">
            <input type="checkbox" name="searchfields[]" value="name" checked>
            В названиях объявлений
        </label>
    </fieldset>

    <fieldset class="row">
        <label class="col-xs-offset-5 col-xs-4">
            <input type="checkbox" name="searchfields[]" value="content" checked>
            В содержании объявления
        </label>
    </fieldset>

    <fieldset class="row">
        <label class="col-xs-offset-5 col-xs-4">
            <input type="checkbox" name="searchfields[]" value="phone">
            В основном телефоне
        </label>
    </fieldset>

    <fieldset class="row">
        <label class="col-xs-offset-5 col-xs-4">
            <input type="checkbox" name="searchfields[]" value="contacts">
            В дополнительных контактах
        </label>
    </fieldset>

    <fieldset class="row">
        <label class="col-xs-offset-5 col-xs-4">
            <input type="checkbox" name="searchfields[]" value="extend_content">
            В полном тексте
        </label>
    </fieldset>

    <fieldset class="row">
        <button type="submit" class="col-xs-offset-5 col-xs-2">Поиск</button>
    </fieldset>
</form>

@endsection