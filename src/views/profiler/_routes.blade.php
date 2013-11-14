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
		{{-- Check for Laravel Version --}}
		@if (stripos($app::VERSION, '.1.'))
			@if ( Route::currentRouteName() == $name)
		<tr class="highlight">
			@else
		<tr>
			@endif
			<td>[{{ array_get($route->methods(), 0) }}]</td>
			<td>{{ $route->uri() }}</td>
			<td>{{ $name }}</td>
			<td>{{ $route->getActionName() ?: 'Closure' }}</td>
			<td>{{ implode('|', array_keys($route->beforeFilters())) }}</td>
			<td>{{ implode('|', array_keys($route->afterFilters())) }}</td>
		@elseif (stripos($app::VERSION, '.0.'))
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
		@endif
		</tr>
	@endforeach
</table>
