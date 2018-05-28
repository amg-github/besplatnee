<?php
$field = $item->{$content['name']}();
?>

@if (is_array($field))
<div class=""> {{ implode(', ', $field) }}</div>
@else
<div class=""> {{ $field }}</div>
@endif