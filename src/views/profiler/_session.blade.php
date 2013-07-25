<?php $session = Session::all() ?>

@if(!empty($session))
	<table>
		<tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
		@foreach($session as $key => $value)
			<tr>
				<td>{{ $key }}</td>
				<td>
					@if (is_array($value) || is_object($value))
						<pre>{{ print_r($value, true) }}</pre>
					@else
						{{ $value }}
					@endif
				</td>
			</tr>
		@endforeach
	</table>
@else
	<span class="anbu-empty">There are no session entries.</span>
@endif
