<style>
	{{ file_get_contents($assetPath.'css/profiler.min.css') }}
	{{ file_get_contents($assetPath.'css/themes/profiler.' . Config::get('profiler::theme') . '.min.css') }}
	@if(!empty($sql_log))
		{{ file_get_contents($assetPath.'css/prettify.min.css') }}
	@endif
</style>

<div class="anbu <?php if (Config::get('profiler::minimized') == true) { echo "anbu-hidden";} ?>" style="<?php if (Config::get('profiler::minimized') == true) { echo "width: 2.6em; overflow:hidden;"; } ?>">

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

			@if (\Config::get('profiler::btns.config'))
			<div class="anbu-tab-pane anbu-table anbu-config">
				@include('profiler::profiler._config')
			</div>
			@endif

			@if (Config::get('profiler::btns.storage'))
			<div class="anbu-tab-pane anbu-table anbu-storage">
				@include('profiler::profiler._storage_logs')
			</div>
			@endif

			@if (class_exists('Auth') && Auth::check())
				<div class="anbu-tab-pane anbu-table anbu-auth">
					@include('profiler::profiler._auth')
				</div>
			@elseif (class_exists('Sentry') && Sentry::check())
				<div class="anbu-tab-pane anbu-table anbu-auth-sentry">
					@include('profiler::profiler._auth_sentry')
				</div>
			@endif

		</div>
	</div>

	<ul id="anbu-open-tabs" class="anbu-tabs" style="<?php if (Config::get('profiler::minimized') == true) { echo "display: none;"; } ?>">
	@if (Config::get('profiler::doc'))
		<a href="{{ Config::get('profiler::doc') }}" class="doc" target="doc">&nbsp;</a>
	@endif
	<?php
		$btns = Config::get('profiler::btns');
		foreach ($btns as $key => $btn)
		{
			$count = isset($counts[$key]) ? $counts[$key] : '';
			echo '<li><a data-anbu-tab="anbu-' . $key . '" class="anbu-tab" href="#" title="' . ((isset($btn['title'])) ? $btn['title'] : $btn['label']) . '">' . $btn['label'] . '<span class="anbu-count">' . $count . '</span></a>' . '</li>';
		}
	?>
		<li class="anbu-tab-right"><a id="anbu-hide" href="#">&#8614;</a></li>
		<li class="anbu-tab-right"><a id="anbu-close" href="#">&times;</a></li>
		<li class="anbu-tab-right"><a id="anbu-zoom" href="#">&#8645;</a></li>
	</ul>

	<ul id="anbu-closed-tabs" class="anbu-tabs" style="<?php if (Config::get('profiler::minimized') == true) { echo "display: block;"; } ?>">
		<li><a id="anbu-show" href="#">&#8612;</a></li>
	</ul>
</div>

<script type="text/javascript">window.jQuery || document.write('<script type="text/javascript" src="{{ Config::get("profiler::jquery_url") }}"><\/script>')</script>
<script><?php echo file_get_contents($assetPath.'js/profiler.min.js'); ?></script>

@if(!empty($sql_log))
	<script><?php echo file_get_contents($assetPath.'js/prettify.min.js'); ?></script>
	<script>
	$(function(){
		prettyPrint();
	});
	</script>
@endif
