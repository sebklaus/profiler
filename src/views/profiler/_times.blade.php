@if(!empty($times))
	<table>
		<tr>
			<th>Timer</th>
			<th>Elapsed Time (Seconds)</th>
		</tr>
		@foreach($times as $key => $time)
			<tr>
				<td>{{ ucwords($key) }}</td>
				<td>{{ number_format((is_array($time)) ? $time['total'] : $time, 5) }}</td>
			</tr>
		@endforeach
	</table>
@else
	<span class="anbu-empty">There have been no checkpoints.</span>
@endif