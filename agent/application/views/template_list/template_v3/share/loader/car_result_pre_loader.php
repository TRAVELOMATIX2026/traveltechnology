<div class="fulloading result-pre-loader-wrapper bus_preloader">
	<div class="loadmask"></div>
	<div class="centerload cityload">
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
				<span class="fa-car fas loader-ico"></span>
			</div>
		</div>
		<div class="relativetop">
		<div class="tmxloader hide"><img class="loadvgif" src="<?php echo $GLOBALS['CI']->template->domain_images('tm_bus_loader.gif'); ?>" alt="Logo" />
		    </div>
			<div class="paraload"> Searching for the best Cars </div>
			<div class="clearfix"></div>
			<div class="sckintload ">
				<div class="ffty">
					<div class="borddo brdrit"> 
						<span class="lblbk"><?php echo ucfirst($car_from); ?></span> 
					</div>
				</div>
				<div class="ffty">
					<div class="borddo"> 
						<span class="lblbk"><?php echo ucfirst($car_to); ?></span> 
					</div>
				</div>
				<div class="tabledates">
					<div class="tablecelfty">
						<div class="borddo brdrit">
							<div class="fuldate">
								<span class="bigdate"><?php echo  date("d",strtotime($depature));?></span>
								<div class="biginre"> <?php echo  date("M",strtotime($depature));?>
									<?php echo  date("Y",strtotime($depature));?> 
								</div>
							</div>
							<div class="fuldate">
								<span class="bigdate"><?php echo  date("d",strtotime($return));?></span>
								<div class="biginre"> <?php echo  date("M",strtotime($return));?>
									<?php echo  date("Y",strtotime($return));?> 
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="busrunning hide">
			<div class="runbus"></div>
			<div class="runbus2"></div>
			<div class="roadd"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var i = 0;
    function makeProgress(){
        if(i < 100){
            i = i + 1;
            $(".progress-bar").css("width", i + "%")//.text(i + " %");
        }
        // Wait for sometime before running this script again
        setTimeout("makeProgress()", 100);
    }
    makeProgress();
</script>