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
        @if ( Route::current()->getName() == $name)
        <tr class="highlight">
        @else
        <tr>
        @endif
            <td>[{{ array_get($route->methods(), 0) }}]</td>
            <td>{{ $route->uri() }}</td>
            <td>{{ $name }}</td>
            <td>{{ $route->getActionName() ?: 'Closure' }}</td>
            <td>{{ implode('|', array_keys($route->getBeforeFilters())) }}</td>
            <td>{{ implode('|', array_keys($route->getAfterFilters())) }}</td>
        </tr>
    @endforeach
</table>
