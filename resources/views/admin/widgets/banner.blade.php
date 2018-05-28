<img src="{{ $item->image }}" style="width: auto; max-width: 100%">
<p>Ссылка:&nbsp;<a href="{{ $item->getUrl() }}">{{ $item->getUrl() }}</a></p>
<p>Текст при наведении:&nbsp;{{ $item->hover_text }}</p>