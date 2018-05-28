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

    <form action="{{ route('admin.list', ['model' => $model]) }}" id="create-ad" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        @forelse($settings as $key => $item)

            <input type="hidden" name="settings[{{ $item->id }}][id]" value="{{ $item->id }}">

            @include('elements.forms.wrapper', [
                'name' => 'settings['.$item->id.'][value]',
                'title' => __('admin.setting.'.$item->name),
                'input' => 'text',
                'help' => '',
                'desc' => '',
                'value' => $item->value
            ])
            
        @empty
            <p>Пусто</p>

        @endforelse

        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection