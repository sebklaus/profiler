<table>
	<tr>
		<th></th>
		<th>Path</th>
		<th>Route</th>
		<th>Uses</th>
		<th>Before</th>
		<th>After</th>
	</tr>
	<?php
		$routes = Route::getRoutes();
		$profiler = $app['profiler'];
		?>
	@foreach($routes as $name => $route)
		{{-- Check for Laravel Version --}}
		@if ($profiler->isLaravelVersion('4.2,4.1'))
			@if ( Route::current()->getName() == $route->getName())
		<tr class="highlight">
			@else
		<tr>
			@endif
			<td>[{{ array_get($route->methods(), 0) }}]</td>
			<td>{{ $route->uri() }}</td>
			<td>{{ $routeName = $route->getName() != "" ? $route->getName() : "[no name]" }}</td>
			<td>{{ $route->getActionName() ?: 'Closure' }}</td>
			<td>{{ implode('|', array_keys($route->beforeFilters())) }}</td>
			<td>{{ implode('|', array_keys($route->afterFilters())) }}</td>
		@elseif ($profiler->isLaravelVersion('4.0'))
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
