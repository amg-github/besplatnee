<textarea class="col-xs-12 area-counter" rows="8" name="{{ $name }}" placeholder="{{ $help }}" data-max="{{ $max }}">{{ request()->input($name) }}</textarea>
<div class="clearfix"></div>
<div class="textarea-count">Осталось символов: <span>{{ $max - Str::length(request()->input($name, '')) }}</span></div>