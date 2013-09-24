<table>
	<tr>
		<th>Level</th>
		<th>Value</th>
	</tr>
	@foreach($storageLogs as $value)
		<tr>
			<td>{{ $value['level'] }}</td>
			<td>
			@if($is_array = is_array($value['header']))
				<?php $value = Profiler::cleanArray($value['header']); ?>
				<pre>{{ print_r($value['header'], true) }}</pre>
			@else
				{{ $value['header'] }}
			@endif
			@if(!empty($value['stack']))
				<pre><small><{{ trim(print_r($value['stack'], true)) }}</small></pre>
			@endif
			</td>
		</tr>
	@endforeach
</table>