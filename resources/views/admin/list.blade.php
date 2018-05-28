<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				@foreach($columns as $column) 
					<td>{{ $column }}</td>
				@endforeach
				<td></td>
			</tr>
		</thead>
		<tbody>
			@foreach ($models as $model) 
				<tr data-id="{{ $model[0] }}">
					@foreach($model as $field)
						<td>{{ $field }}</td>
					@endforeach
					<td>
						<a href="{{ route('office', ['model' => $modelname, 'action' => 'edit', 'id' => $model[0]]) }}" class="btn btn-primary">Редактировать</a>
						<a href="{{ route('office', ['model' => $modelname, 'action' => 'remove', 'id' => $model[0]]) }}" class="btn btn-danger">Удалить</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>