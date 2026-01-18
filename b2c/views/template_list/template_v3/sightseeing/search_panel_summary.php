<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/sight-search-form.css'), 'media' => 'screen');

$destination = @$sight_seen_search_params['destination'];

?>
<div class="modfictions for_hotel_modi">
	<div class="splmodify">
		<div class="container">
			<div id="modify" class="araeinner">
				<div class="insplarea">
					<?php echo $GLOBALS['CI']->template->isolated_view('share/sightseeing_search') ?>
				</div>
			</div>
		</div>
	</div>
</div>