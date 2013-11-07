<table>
	<tr>
		<th>Key</th>
		<th>Value</th>
	</tr>
	<tr>
		<td>Current route</td>
{{-- Check for Laravel Version --}}
@if (strpos($app::VERSION, '4.1') !== FALSE)
		<td>{{ Route::current()->getName() }}</td>
@elseif (strpos($app::VERSION, '4.0') !== FALSE)
		<td>{{ Route::currentRouteName() }}</td>
@endif
	</tr>
	<tr>
		<td>Current controller action</td>
{{-- Check for Laravel Version --}}
@if (strpos($app::VERSION, '4.1') !== FALSE)
		<td>{{ Route::current()->getActionName() }}</td>
@elseif (strpos($app::VERSION, '4.0') !== FALSE)
		<td>{{ Route::currentRouteAction() }}</td>
@endif
	</tr>
</table>
