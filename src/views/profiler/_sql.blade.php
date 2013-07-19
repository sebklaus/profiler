@if(!empty($sql_log))
	<table>
		<tr>
			<th>No.</th>
			<th>Query</th>
			<th>Bindings</th>
			<th>Time</th>
		</tr>
		@foreach($sql_log as $key => $log)
			<tr>
				<td>{{ $key+1 }}</td>
				<td><pre class="prettyprint languague-sql">{{ $log['query'] }}</pre></td>
				<td>
				@foreach($log['bindings'] as $k => $binding)
					@if(is_object($binding) || is_array($binding))
						<?php $binding = print_r($binding, true); ?>
					@endif
					@if($k != count($log['bindings'])-1)
						{{ $binding }},
					@else
						{{ $binding }}
					@endif
				@endforeach
				</td>
				<td>{{ $log['time'] }}</td>
			</tr>
		@endforeach
	</table>
@else
	<span class="anbu-empty">There have been no SQL queries executed.</span>
@endif