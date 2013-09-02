<table>
    <tr>
        <th>Routes</th>
    </tr>
	<?php $routes = Route::getRoutes(); ?>
    @foreach($routes as $name => $route)
		<tr>
            @if ( Route::currentRouteName() == $name)
			    <td><strong>{{ $name }}</strong></td>
            @else
                <td>{{ $name }}</td>
            @endif
		</tr>
    @endforeach
</table>
