<div class="fulloading result-pre-loader-wrapper forhoteload sightseen_lodr">
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
        <span class="fa-camera fas loader-ico"></span>
      </div>
      <div class="flitfly"></div>
    </div>
    <div class="relativetop sghtseen">
      <div class="paraload">
        Searching for the best activities places
      </div>
      <div class="clearfix"></div>
      <div class="clearfix"></div>
      <div class="sckintload">
        <div class="placenametohtl"><?php echo ucfirst($sight_seen_search_params['destination']); ?></div>
        <!--         <div class="ffty">
          <div class="borddo brdrit">
            <span class="lblbk">Check In</span>
          </div>
        </div>
        <div class="ffty">
          <div class="borddo">
            <span class="lblbk">Check Out</span>
          </div>
        </div> -->
        <div class="clearfix"></div>
        <div class="tabledates">
          <div class="tablecelfty">
            <div class="borddo brdrit">
             <?php if($sight_seen_search_params['from_date']):?>
                <div class="fuldate">
                
                    <span class="bigdate"><?php echo date("d",strtotime($sight_seen_search_params['from_date']));?></span>
                    <div class="biginre">
                        <?php echo date("M",strtotime($sight_seen_search_params['from_date']));?>
          <?php echo date("Y",strtotime($sight_seen_search_params['from_date']));?>
                    </div>
                </div>
            <?php endif;?>
            </div>
          </div>
           <div class="tablecelfty">
              <div class="borddo">
              <?php if($sight_seen_search_params['to_date']):?>
                  <div class="fuldate">
                      <span class="bigdate"><?php echo date("d",strtotime($sight_seen_search_params['to_date']));?></span>
                      <div class="biginre">
                           <?php echo date("M",strtotime($sight_seen_search_params['to_date']));?>
             <?php echo date("Y",strtotime($sight_seen_search_params['to_date']));?>
                      </div>
                  </div>
              <?php endif;?>
              </div>
          </div>
        </div>
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