<table>
    <tr>
        <th>Routes</th>
    </tr>
	<?php $routes = Route::getRoutes(); ?>
    @foreach($routes as $name => $route)
		<tr>
			<td>{{ $name }}</td>
		</tr>
    @endforeach
</table>