@if (Sentry::check())
	<table>
		<tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
		@foreach(Sentry::getUser()->toArray() as $key => $value)
			<tr>
				<td>{{ $key }}</td>
				<td>
					@if (is_array($value))
						<pre>{{ print_r($value, true) }}</pre>
					@else
						{{ $value }}
					@endif
				</td>
			</tr>
		@endforeach
	</table>
@endif
