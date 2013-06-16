@if(!empty($app_logs))
	<table>
		<tr>
			<th>Type</th>
			<th>Message</th>
		</tr>
		@foreach($app_logs as $log)
			<tr>
				<td>{{ $log[0] }}</td>
				<td>{{ (is_object($log[1])) ? null : $log[1] }}</td>
			</tr>
		@endforeach
	</table>
@else
	<span class="anbu-empty">There are no log entries.</span>
@endif