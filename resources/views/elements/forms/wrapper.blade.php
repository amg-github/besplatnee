<div class="create-ad-row {{ $errors->has($name) ? 'error' : '' }}">
    <div class="row">
        <div class="col-xs-4">
            <div class="create-ad-title" style="{{ (isset($color) ? 'color: '.$color : '') }}">{{ $title }}</div>
        </div>
        <div class="col-xs-8">
            @if (isset($params))
                @include('elements.forms.' . $input, $params)
            @else
                @include('elements.forms.' . $input)
            @endif
            <div class="create-ad-desc">{!! $desc !!}</div>
            <div class="error-message">{{ $errors->first($name) }}</div>
        </div>
    </div>
</div>