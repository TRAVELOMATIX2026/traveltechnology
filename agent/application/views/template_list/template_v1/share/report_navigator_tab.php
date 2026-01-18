<?php
$active_domain_modules = $GLOBALS ['CI']->active_domain_modules;
$master_module_list = $GLOBALS ['CI']->config->item ( 'master_module_list' );
if (empty ( $default_view )) {
	$default_view = $GLOBALS ['CI']->uri->segment ( 2 );
}
?>
<!-- Module Tabs Starts-->
<ul id="myTab" role="tablist" class="nav nav-tabs b2b_navul">
<?php
	$append_query_string = $_SERVER['QUERY_STRING'];
	if(empty($append_query_string) == false) {
		$append_query_string = '?'.$append_query_string;
	}
	foreach ( $master_module_list as $k => $v ) {
		if (in_array ( $k, $active_domain_modules )) {
			if($v != 'package') {//FIXME: remove later
		?>
	<li class="<?=((@$default_view == $k || $default_view == $v) ? 'active' : '')?>" role="presentation">
		<a  href="<?=base_url()?>index.php/report/<?=($v)?><?=$append_query_string?>"> 
			<?=ucfirst($v)?>
		</a>
	</li>
	<?php } 
		} 
	}?>
</ul>


<div class="extra_content">
    <!-- Module Tabs Ends-->
    <!-- Search Filter Starts -->
    <div id="advance_search_form_container" class="serch_area_fltr">
        <form action="<?=base_url().'report/'.$default_view?>" method="get" autocomplete="off" class="row" role="form">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="form-label"> From </label>
                    <input type="text" id="from_date" class="form-control" name="from_date" placeholder="From Date" value="<?=@$from_date?>">
                </div>
                <div class="col-sm-3">
                    <label class="form-label"> To </label>
                    <input type="text" id="to_date" class="form-control disable-date-auto-update" name="to_date" placeholder="To Date" value="<?=@$to_date?>">
                </div>
                <div class="col-sm-2">
                    <label class="form-label"> Status </label>
                    <select class="form-control" name="filter_booking_status">
                       <option value="">All</option>
                       	<?=generate_options(get_enum_list('report_filter_status'),(array)@$_GET['filter_booking_status']);?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-gradient-primary mt-4 w-100" type="submit"><i class="bi bi-search"></i> Search</button>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-gradient-warning mt-4 w-100" type="reset"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
    <!-- Search Ends Ends -->
    <div class="clearfix"></div>
    <!-- Time Tab Filter Starts -->
    <div class="searc_fliter_all">
        <div class="filter_heading">
            <i class="bi bi-lightning-charge-fill"></i>
            <span>Quick Search Filters</span>
        </div>
        <?php $today_search = date('Y-m-d');
        $last_today_search = date('Y-m-d', strtotime('-1 day'));
        ?>
        <div class="list_of_sections">
            <a class="quick-filter-btn <?=(($today_search == @$_GET['today_booking_data']) ? 'active' : '')?>" href="<?=base_url().'report/'.$default_view.'?today_booking_data='.$today_search?>">
                <span class="filter-icon"><i class="bi bi-calendar-check"></i></span>
                <span class="filter-label">Today</span>
            </a>
            <a class="quick-filter-btn <?=(($last_today_search == @$_GET['last_day_booking_data']) ? 'active' : '')?>" href="<?=base_url().'report/'.$default_view.'?last_day_booking_data='.$last_today_search?>">
                <span class="filter-icon"><i class="bi bi-calendar-day"></i></span>
                <span class="filter-label">Yesterday</span>
            </a>
            <?php 
                $filter_duration_values = array(
                    'Last 3 Days' => 3,
                    'Last 7 Days' => 7,
                    'Last 15 Days' => 15,
                    'Last Month' => 30,
                    'Last 3 Months' => 90
                );
                $icon_map = array(
                    3 => 'bi-calendar3',
                    7 => 'bi-calendar-week',
                    15 => 'bi-calendar-range',
                    30 => 'bi-calendar-month',
                    90 => 'bi-calendar2-range'
                );
                foreach($filter_duration_values as $k => $v) { 
                $prev_filter_date = date('Y-m-d', strtotime('-'.intval($v).' day'));
            ?>
                <a class="quick-filter-btn <?=(($prev_filter_date == @$_GET['prev_booking_data']) ? 'active' : '')?>" href="<?=base_url().'report/'.$default_view.'?prev_booking_data='.$prev_filter_date?>">
                    <span class="filter-icon"><i class="<?=isset($icon_map[$v]) ? $icon_map[$v] : 'bi-calendar'?>"></i></span>
                    <span class="filter-label"><?=$k;?></span>
                </a>
            <?php }
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<script>
$(document).ready(function() {
	var cache = {};
	$("#auto_suggest_booking_id").autocomplete({
		source:  function( request, response ) {
	        var term = request.term;
	        if ( term in cache ) {
	          response( cache[ term ] );
	          return;
	        } else {
		        var module = $('#module', 'form#auto_suggest_booking_id_form').val().trim();
	        	$.getJSON( app_base_url+"index.php/ajax/auto_suggest_booking_id?module="+module, request, function( data, status, xhr ) {
	                cache[ term ] = data;
	                response( cache[ term ] );
	              });
	        }
	      },
	    minLength: 1
	 });
});
</script>
<?php
$datepicker = array(array('from_date', PAST_DATE), array('to_date', PAST_DATE));
$GLOBALS['CI']->current_page->set_datepicker($datepicker);
$this->current_page->auto_adjust_datepicker(array(array('from_date', 'to_date')));
?>