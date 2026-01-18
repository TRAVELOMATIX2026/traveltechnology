<?php //Images Url

$template_images = $GLOBALS['CI']->template->template_images();

if($flight_search_params['trip_type'] == 'multicity') {

	$flight_search_params['depature'] = $flight_search_params['depature'][0];

	$flight_search_params['from'] = $flight_search_params['from'][0];

	$flight_search_params['to'] = end($flight_search_params['to']);

}

?>

<style>

	.topssec::after{display:none;}

</style>

<div class="modfictions">


	<div class="clearfix"></div>

	<div class="splmodify">

		<div class="container p-0">

			<div id="modify" class="araeinner">

				<div class="insplarea">

					<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search') ?>

				</div>

			</div>

		</div>

	</div>

</div>



