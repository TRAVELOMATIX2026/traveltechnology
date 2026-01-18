<style>
	.topssec::after{display:none;}
	
</style>

<?php //debug($car_search_params);exit;?>
<div class="modfictions for_hotel_modi">
	

	<div class="modify_search_wrap splmodify">
		<div class="container p-0" style="position: relative;">
		 <div class="">
			<div id="modify" class="araeinner">
				<div class="insplarea">
					<?php echo $GLOBALS['CI']->template->isolated_view('share/car_search') ?>
				</div>
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