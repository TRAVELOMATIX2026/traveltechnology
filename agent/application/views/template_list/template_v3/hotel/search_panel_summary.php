<style>

	.topssec::after{display:none;}

</style>



<?php

$adult_count = array_sum($hotel_search_params['adult_config']);

$child_count = array_sum($hotel_search_params['child_config']);

$room_count = $hotel_search_params['room_count'];

?>

<div class="modfictions for_hotel_modi">

	

	<div class="splmodify">

		<div class="container p-0">

			<div id="modify" class="	araeinner">

				<div class="insplarea">

					<?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search') ?>

				</div>

			</div>

		</div>

	</div>

</div>



<script type="text/javascript">

$(document).ready(function(){

	$('.modifysrch').click(function(){

		$(this).stop( true, true ).toggleClass('up');

		$('.search-result').stop( true, true ).toggleClass('flightresltpage');

		$('.modfictions').stop( true, true ).toggleClass('fixd');

	});



});

</script>