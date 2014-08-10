<table>
	<tr>
		<th>Key</th>
		<th>Value</th>
	</tr>
	<tr>
		<td>Current route</td>
@if ($app['profiler']->isLaravelVersion('4.2,4.1'))
		<td>{{ Route::current()->getName() }}</td>
@elseif ($app['profiler']->isLaravelVersion('4.0'))
		<td>{{ Route::currentRouteName() }}</td>
@endif
	</tr>
	<tr>
		<td>Current controller action</td>
@if ($app['profiler']->isLaravelVersion('4.2,4.1'))
		<td>{{ Route::current()->getActionName() }}</td>
@elseif ($app['profiler']->isLaravelVersion('4.0'))
		<td>{{ Route::currentRouteAction() }}</td>
@endif
	</tr>
</table>