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

    <form action="{{ request()->input('id') ? route('admin.edit', ['model' => 'groups', 'id' => $group->id]) : route('admin.create', ['model' => 'groups']) }}" id="create-ad" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ request()->input('id') }}">

        @include('elements.forms.wrapper', [
            'name' => 'name',
            'title' => 'Название группы',
            'input' => 'text',
            'help' => 'Например "Администратор"',
            'desc' => '',
            'value' => request()->input('name'),
        ])

        @include('elements.forms.wrapper', [
            'name' => 'description',
            'title' => 'Описание',
            'input' => 'textarea',
            'help' => 'Краткое описание группы',
            'desc' => '',
            'value' => request()->input('description'),
            'max' => 300,
        ])

        @include('elements.forms.wrapper', [
            'name' => 'sudo',
            'title' => 'Назначить супер права',
            'input' => 'radiogroup',
            'help' => '',
            'desc' => 'Пользователи этой группы получат все привилегии',
            'values' => [1 => 'Да', 0 => 'Нет',],
        ])


        @foreach(\App\Permission::orderBy('group')->distinct()->get(['group'])->pluck('group') as $permissionGroup) 
            <div class="create-ad-row ">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="create-ad-title">@lang('permissions.group.' . $permissionGroup)</div>
                    </div>
                    <div class="col-xs-8">
                        @foreach(\App\Permission::where('group', $permissionGroup)->get() as $permission)
                        <div class="row">
                            <div class="col-xs-12">
                                <label style="font-weight: normal;">
                                    <input id="sudo" type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $group->permissions->contains($permission) ? 'checked' : '' }}>&nbsp;@lang($permission->title)
                                </label>  

                                <div class="create-ad-desc">@lang($permission->description)</div>
                                <div class="error-message"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection