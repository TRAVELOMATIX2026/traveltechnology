<?php 
//debug($transfer_search_params);
if($transfer_search_params['trip_type']=='oneway') {
    $disable_return_date_label = ' style="opacity:0.4" ';
} else {
    $disable_return_date_label = '';
}

?>
<style>
    .topssec::after{display:none;}
    .boxlabl { font-weight:bold; }
</style>
<div class="modfictions">
    <div class="modinew">
        <div class="container">
            <div class="contentsdw">
                <div class="smldescrptn">
                    <div class="col-sm-9 col-10 nopad">
                        <div class="col-6 boxpad none_boil_full">
                            <h4 class="contryname">Route</h4>
                            <h3 class="placenameflt"><?php echo $transfer_search_params['from']?> <br> <?php echo $transfer_search_params['to']?> </h3>
                        </div>
                        <div class="col-2 boxpad none_boil_full">
                            <h4 class="contryname">Trip Type</h4>
                            <h3 class="placenameflt">
                                <?php if($transfer_search_params['trip_type']=='circle') { echo 'Round Trip'; } else { echo 'One Way Trip'; } ?>
                            </h3>
                        </div>

                        <div class="col-2 boxpad none_boil">
                            <h4 class="boxlabl"><span class="faldate fa fa-calendar"></span>Departure</h4>
                            <div class="datein">
                                <span class="calinn"><?=date('d M h:i A', strtotime($transfer_search_params['from_date']))?></span>
                            </div>
                        </div>
                        <?php if($transfer_search_params['trip_type']=='circle') { ?>
                        <div class="col-2 boxpad none_boil <?=$disable_return_date_label?>">
                            <div class="boxlabl"><span class="faldate fa fa-calendar"></span>Return</div>
                        
                            <div class="datein">
                                <span class="calinn">
                                   <?=date('d M h:i A', strtotime($transfer_search_params['to_date']))?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-sm-3 col-2 nopad">
                        <div class="col-3 boxpad">
                            <h4 class="boxlabl">Adult</h4>
                            <div class="countlbl" style="text-align: left;color: #fff"><?php echo $transfer_search_params['adult']; ?></div>
                        </div>
                         <div class="col-3 boxpad">
                            <h4 class="boxlabl">Child</h4>
                            <div class="countlbl" style="text-align: left;color: #ff"><?php echo $transfer_search_params['child']; ?></div>
                        </div>
                        <div class="col-5 boxpad float-end">
                            <a class="modifysrch" data-bs-toggle="collapse" data-bs-target="#modify"><span class="mdyfydsktp">Modify Search</span>
                                <i class="fa fa-angle-down mobresdv" aria-hidden="true"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="splmodify">
        <div class="container">
            <div id="modify" class="collapse araeinner">
                <div class="insplarea">
                    <?php echo $GLOBALS['CI']->template->isolated_view('share/transfer_search') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.modifysrch').click(function () {
            $(this).stop(true, true).toggleClass('up');
            $('.search-result').stop(true, true).toggleClass('flightresltpage');
            $('.modfictions').stop(true, true).toggleClass('fixd');
        });
    });
</script>