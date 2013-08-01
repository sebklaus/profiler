<style>
	<?php echo file_get_contents($assetPath.'profiler.min.css'); ?>
	@if(!empty($sql_log))
		<?php echo file_get_contents($assetPath.'prettify.min.css'); ?>
	@endif
</style>

<div class="anbu">

	<div class="anbu-window">
		<div class="anbu-content-area">

			<div class="anbu-tab-pane anbu-table anbu-environment">
				@include('profiler::profiler._environment')
			</div>

			<div class="anbu-tab-pane anbu-table anbu-controller">
				@include('profiler::profiler._controller')
			</div>

			<div class="anbu-tab-pane anbu-table anbu-routes">
				@include('profiler::profiler._routes')
			</div>

			<div class="anbu-tab-pane anbu-table anbu-log">
				@include('profiler::profiler._logs')
			</div>

			<div class="anbu-tab-pane anbu-table anbu-sql">
				@include('profiler::profiler._sql')
			</div>

			<div class="anbu-tab-pane anbu-table anbu-checkpoints">
				@include('profiler::profiler._times')
			</div>

			<div class="anbu-tab-pane anbu-table anbu-view">
				@include('profiler::profiler._view_data')
			</div>

			<div class="anbu-tab-pane anbu-table anbu-file">
				@include('profiler::profiler._files')
			</div>

			<div class="anbu-tab-pane anbu-table anbu-session">
				@include('profiler::profiler._session')
			</div>

			@if (Auth::check())
				<div class="anbu-tab-pane anbu-table anbu-auth">
					@include('profiler::profiler._auth')
				</div>
			@endif

			@if (class_exists('Cartalyst\Sentry\SentryServiceProvider') AND Sentry::check())
				<div class="anbu-tab-pane anbu-table anbu-auth-sentry">
					@include('profiler::profiler._auth_sentry')
				</div>
			@endif

		</div>
	</div>

	<ul id="anbu-open-tabs" class="anbu-tabs">
		<li><a data-anbu-tab="anbu-environment" class="anbu-tab" href="#">Env <span class="anbu-count">{{ App::environment() }}</span></a></li>
		<li><a data-anbu-tab="anbu-controller" class="anbu-tab" href="#">Controller <span class="anbu-count">{{ (Route::currentRouteAction()) ? Route::currentRouteAction() : 'NULL' }}</span></a></li>
		<li><a data-anbu-tab="anbu-routes" class="anbu-tab" href="#">Routes <span class="anbu-count">{{ count(Route::getRoutes()) }}</span></a></li>
		<li><a data-anbu-tab="anbu-log" class="anbu-tab" href="#">Log <span class="anbu-count">{{ count($app_logs) }}</span></a></li>
		<li><a data-anbu-tab="anbu-sql" class="anbu-tab" href="#">SQL <span class="anbu-count">{{ count($sql_log) }}</span></a></li>
		<li><a class="anbu-tab" data-anbu-tab="anbu-checkpoints">Time <span class="anbu-count">{{ round($times['total'], 3) }} s</span></a></li>
		<li><a class="anbu-tab">Memory <span class="anbu-count">{{ Profiler::getMemoryUsage() }}</span></a></li>
		<li><a class="anbu-tab" data-anbu-tab="anbu-file">Files <span class="anbu-count">{{ count($includedFiles) }}</span></a></li>
		<li><a class="anbu-tab" data-anbu-tab="anbu-view">View <span class="anbu-count">{{ count($view_data) }}</span></a></li>
		<li><a class="anbu-tab" data-anbu-tab="anbu-session">Session <span class="anbu-count">{{ count(Session::all()) }}</span></a></li>
		@if (Auth::check())
			<li><a class="anbu-tab" data-anbu-tab="anbu-auth">Auth</a></li>
		@endif
		@if (class_exists('Cartalyst\Sentry\SentryServiceProvider') AND Sentry::check())
			<li><a class="anbu-tab" data-anbu-tab="anbu-auth-sentry">Auth <span class="anbu-count">{{ Sentry::getUser()->email }}</span></a></li>
		@endif

		<li class="anbu-tab-right"><a id="anbu-hide" href="#">&#8614;</a></li>
		<li class="anbu-tab-right"><a id="anbu-close" href="#">&times;</a></li>
		<li class="anbu-tab-right"><a id="anbu-zoom" href="#">&#8645;</a></li>
	</ul>

	<ul id="anbu-closed-tabs" class="anbu-tabs">
		<li><a id="anbu-show" href="#">&#8612;</a></li>
	</ul>
</div>

<script type="text/javascript">window.jQuery || document.write('<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"><\/script>')</script>
<script><?php echo file_get_contents($assetPath.'profiler.min.js'); ?></script>

@if(!empty($sql_log))
	<script><?php echo file_get_contents($assetPath.'prettify.min.js'); ?></script>
	<script>
	$(function(){
		prettyPrint();
	});
	</script>
@endif
