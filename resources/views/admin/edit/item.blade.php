@extends('admin.dashboard')

@section('admincontent')

<div class="create-ad-wrapper ad-post">

    <form action="{{ route('admin.save', ['model' => $model, 'id' => $item->id]) }}" id="create-ad" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ request()->input('id') }}">
        
        

        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection