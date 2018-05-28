<div class="model-field editable" data-field="name" data-view="text" style="color: #337ab7;" title="Нажмите, чтобы изменить">{{ $item->name }}</div>
<div class="model-field editable" data-field="content" data-view="textarea" title="Нажмите, чтобы изменить" style="margin: 8px 0;max-width: 560px; word-wrap: break-word;">{!! $item->content !!}</div>

<div><span class="model-field editable" data-field="price" data-view="number" data-format="0|,| " style="color: #337ab7;" title="Нажмите, чтобы изменить">{{ number_format($item->price, 0, ',', ' ') }}</span> руб.</div>

<div style="font-size: .83333em;color: #9c9c9c;margin-bottom: 8px">
    @if($item->heading->parent)
        @lang($item->heading->parent->name) / 
    @endif
    @lang($item->heading->name)
</div>

<div style="font-size: 14px;">
    @if($item->creator && $item->creator->isAccessToAdminPanel())
        <div style="color: green">Объявление добавлено руководством</div>
    @else 
        <div style="color: red">Объявление добавлено пользователем</div>
    @endif
</div>

<div style="font-size: 14px;">Основной телефон: <a href="#" title="Нажмите чтобы посмотреть объявления этого номера" style="display: inline-block;">{{ $item->owner ? $item->owner->phone : '' }}</a></div>

<div style="font-size: 14px;">
    @if($item->owner && $item->owner->phone()->first()->verify)
        <div style="color: red">Получен код доступа</div>
    @else
        @if($item->owner && !$item->owner->phone()->first()->verify_code) 
            <div style="color: green">Номер не проверен</div>
        @else
            <div style="color: blue">Код доступа отправлен</div>
        @endif
    @endif
</div>