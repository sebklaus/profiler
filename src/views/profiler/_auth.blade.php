@if (Auth::check() && Auth::user() instanceof \Illuminate\Database\Eloquent\Model)
	<table>
		<tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
		@foreach(Auth::user()->toArray() as $key => $value)
			<tr>
				<td>{{ $key }}</td>
				<td>{{ print_r($value, true) }}</td>
			</tr>
		@endforeach
	</table>
@endif
