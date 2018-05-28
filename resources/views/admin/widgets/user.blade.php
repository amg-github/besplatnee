<p>Основной телефон:&nbsp;{{ $item->phone }}</p>

<p>{{ $item->fullname() }}</p>

@if($item->phone()->first()->verify)
<p style="color: green">Телефон проверен</p>
@else
<p style="color: red">Телефон не проверен</p>
@endif
<div class="clearfix"></div>