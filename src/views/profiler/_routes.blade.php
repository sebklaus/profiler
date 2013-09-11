<table>
    <tr>
        <th></th>
        <th>Path</th>
        <th>Route</th>
        <th>Uses</th>
        <th>Before</th>
        <th>After</th>
    </tr>
    <?php $routes = Route::getRoutes(); ?>
    @foreach($routes as $name => $route)
        <tr>
            <td>[{{ array_get($route->getMethods(), 0) }}]</td>
            <td>{{ $route->getPath() }}</td>

            @if ( Route::currentRouteName() == $name)
                <td><strong>{{ $name }}</strong></td>
            @else
                <td>{{ $name }}</td>
            @endif

            <td>{{ $route->getAction() ?: 'Closure' }}</td>

            <td>{{ implode('|', $route->getBeforeFilters()) }}</td>
            <td>{{ implode('|', $route->getAfterFilters()) }}</td>
        </tr>
    @endforeach
</table>
