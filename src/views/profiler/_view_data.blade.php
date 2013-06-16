<table>
	<tr>
		<th>Variable</th>
		<th>Value</th>
	</tr>
	@foreach($view_data as $key => $value)
		<tr>
			<td>{{ $key }}</td>
			@if($is_array = is_array($value))
				<?php $value = Profiler::cleanArray($value); ?>
				<td><pre>{{ print_r($value, true) }}</pre></td>
			@else
				<td>{{ $value }}</td>
			@endif
		</tr>
	@endforeach
</table>