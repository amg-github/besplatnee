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

    <form action="{{ $item->id ? route('admin.edit', ['model' => $model, 'id' => $item->id]) : route('admin.create', ['model' => $model]) }}" id="create-ad" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $item->id }}">

        @include('elements.forms.wrapper', [
            'name' => 'query',
            'title' => 'Запрос',
            'input' => 'text',
            'help' => '',
            'desc' => '',
            'id' => 'query',
            'params' => [
                'value' => $item->query
            ]
        ])

        @include('elements.forms.wrapper', [
            'name' => 'approved',
            'title' => 'Поисковой запрос одобрен',
            'input' => 'checkbox',
            'help' => 'Состояние одобрения поискового запроса',
            'desc' => '',
        ])

        @include('elements.forms.areapicker_query', [

        ])

        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection