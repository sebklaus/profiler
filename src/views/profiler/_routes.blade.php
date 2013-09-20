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
        @if ( Route::currentRouteName() == $name)
        <tr class="highlight">
        @else
        <tr>
        @endif
            <td>[{{ array_get($route->getMethods(), 0) }}]</td>
            <td>{{ $route->getPath() }}</td>
            <td>{{ $name }}</td>
            <td>{{ $route->getAction() ?: 'Closure' }}</td>
            <td>{{ implode('|', $route->getBeforeFilters()) }}</td>
            <td>{{ implode('|', $route->getAfterFilters()) }}</td>
        </tr>
    @endforeach
</table>
