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
            'name' => 'price',
            'title' => 'Цена',
            'input' => 'number',
            'help' => '',
            'desc' => '',
        ])

        <div class="create-ad-row">
            <div class="row">
                <div class="col-xs-4">
                    <div class="create-ad-title" style="">Тип выделения</div>
                </div>
                <div class="col-xs-8">
                    <select name="advert_template_id">
                        <option value="">Без выделения</option>
                        @foreach(\App\AdvertTemplate::all() as $template)
                            <option value="{{ $template->id }}" {{ $item->advert_template_id == $template->id ? 'selected' : '' }}>@lang($template->name)</option>
                        @endforeach
                    </select>
                   <div class="create-ad-desc"></div>
                   <div class="error-message"></div>
               </div>
           </div>
        </div>

        <div class="create-ad-row">
	        <div class="row" style="margin-bottom: 8px">
	            <div class="col-xs-4">
	               <div class="create-ad-title" style="">Продлить на</div>
	            </div>

	            <div class="col-xs-8">
					<span>Годен до: {{ $item->deleted_at.' '.(\Carbon\Carbon::parse($item->deleted_at)->lt(\Carbon\Carbon::now()) ? '(Просрочен)' : '(Действителен)') }}</span><br>
	                <select name="period" >
	                    @foreach([0,1,2,3,4,5,8,12,16] as $period)
	                        <option value="{{ $period }}" {{ request()->input('bill.period', 0) == $period ? 'selected' : '' }}>@lang('adverts.pickup.period.' . $period)</option>
	                    @endforeach
	                </select>
	            </div>
	        </div>
    	</div>

    	<div class="create-ad-row">
	        <div class="row" style="margin-bottom: 8px">
	            <div class="col-xs-4">
	               <div class="create-ad-title" style="">Статус</div>
	            </div>

	            <div class="col-xs-8">
                    <select name="status" style="">
                        @foreach([1,3,0,2] as $status)
                            <option value="{{ $status }}" {{ $item->status == $status ? 'selected' : '' }}>@lang('adverts.pickup.status.' . $status)</option>
                        @endforeach
                    </select>
	            </div>
	        </div>
    	</div>


        <div class="add-post-params clearfix">

            <div class="buttons block-right">

                <button class="block-left add-create-next" type="submit"><span>Сохранить</span><i class="fa fa-angle-right" aria-hidden="true"></i></button>

            </div>

        </div>

    </form>

</div>
@endsection