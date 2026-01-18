<div class="fulloading result-pre-loader-wrapper forhoteload">
	<div class="loadmask"></div>
	<div class="centerload cityload">
	<div class="load_links" style="position: absolute;top: 0;right: 0;z-index: 9999;font-size:14px;font-weight:300">
			<a href=""><i class="fa fa-refresh"></i></a>
			<a href="<?php echo base_url(); ?>"><i class="fa fa-close"></i></a>			
		</div>
		<div class="loadcity"></div>
		<div class="clodnsun"></div>
		<div class="reltivefligtgo">
			<div class="loader-logo">
				<img class="ful_logo" src="<?php echo $GLOBALS['CI']->template->domain_images('TMX2783081694775862kapido-logo_1.png'); ?>" alt="">
			</div>
			<div class="progress">
			  <div class="progress-bar progress-bar-info  active" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
			    <span class="sr-only">80% Complete (danger)</span>
			  </div>
			</div>
			<div class="loader-wrapper">
				<span class="loader"></span>
				<span class="fa-bed fas loader-ico"></span>
			</div>
			<div class="flitfly"></div>
		</div>
		<div class="relativetop">
			<div class="paraload">
				Searching for the best hotels
			</div>
			<div class="clearfix"></div>
			<div class="placenametohtl"><?php echo ucfirst($result['location']); ?></div>
			<div class="clearfix"></div>
			<div class="sckintload">
				<div class="ffty">
					<div class="borddo brdrit">
						<span class="lblbk">Check In</span>
					</div>
				</div>
				<div class="ffty">
					<div class="borddo">
						<span class="lblbk">Check Out</span>
					</div>
				</div>
				<div class="tabledates">
					<div class="tablecelfty">
						<div class="borddo brdrit">
							<div class="fuldate">
								<span class="bigdate"><?php echo date("d",strtotime($result['from_date']));?></span>
								<div class="biginre">
									<?php echo date("M",strtotime($result['from_date']));?>
									<?php echo date("Y",strtotime($result['from_date']));?>
								</div>
							</div>
						</div>
					</div>
					<div class="tablecelfty">
						<div class="borddo">
							<div class="fuldate">
								<span class="bigdate"><?php echo date("d",strtotime($result['to_date']));?></span>
								<div class="biginre">
									<?php echo date("M",strtotime($result['to_date']));?>
									<?php echo date("Y",strtotime($result['to_date']));?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="nigthcunt"><?php echo $hotel_search_params['no_of_nights'];?> <?=(intval($hotel_search_params['no_of_nights']) > 1 ? 'Nights' : 'Night')?></div>
			</div>
			<div class="clearfix"></div>
			
		</div>
		<div class="clearfix"></div>
		<div class="busrunning">
			<div class="runbus"></div>
			<div class="runbus2"></div>
			<div class="roadd"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var i = 0;
    function makeProgress(){
        if(i < 90){
            i = i + 1;
           $(".progress-bar").css("width", i + "%"); //.text(i + " %")
        }
        setTimeout("makeProgress()",200);
    }
    makeProgress();
</script>