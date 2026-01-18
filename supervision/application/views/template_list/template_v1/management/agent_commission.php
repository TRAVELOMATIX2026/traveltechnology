<?
$add_default_commission = false;
if((isset($agent_ref_id) == true && empty($agent_ref_id) == false) || (isset($default_commission) == true && $default_commission == ACTIVE)) {
	//update the commission for specific agent or default commission
	$show_update_form = true;
	$tab1 = " active ";
	$tab2 = "";
	$flight_commission_details = $commission_details['flight_commission_details'];
	$hotel_commission_details = $commission_details['hotel_commission_details'];
	$bus_commission_details = $commission_details['bus_commission_details'];
	// debug($commission_details);
	// exit;
	$sightseeing_commission_details = $commission_details['sightseeing_commission_details'];

	$transfer_commission_details = $commission_details['transfer_commission_details'];


	if(isset($agent_ref_id) == true && empty($agent_ref_id) == false) {
		$agent_details = $commission_details['agent_details'];
		$update_tab_label = 'AL00321';
	} else if(isset($default_commission) == true && $default_commission == ACTIVE) {
		$add_default_commission = true;
		$update_tab_label = 'AL00321';
	}
} else {
	$show_update_form = false;
	$tab2 = " active ";
	$tab1 = "";
}
?>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent col-md-12">
<h4 class="mb-3">Agent Commission</h4>
<div class="panel card clearfix"><!-- PANEL WRAP START -->
<div class="p-0">
<ul class="nav nav-tabs px-3 pt-3" role="tablist" id="myTab">
<?php if($show_update_form == true || $add_default_commission == true) { ?>
	<li role="presentation" class="<?php echo $tab1; ?>"><a id="fromListHead" href="#fromList" aria-controls="home" role="tab" data-bs-toggle="tab"><i class="bi bi-pencil-square"></i> <?php echo get_app_message($update_tab_label);?></a></li>
<?php } ?>
<?php if($add_default_commission == false) { ?>
	<li role="presentation" class="<?php echo $tab2; ?>"><a href="#tableList" aria-controls="profile" role="tab" data-bs-toggle="tab"><i class="bi bi-people-fill"></i> <?php echo get_app_message('AL00319');?></a></li>
<?php } ?>
</ul>
<div class="tab-content"><?php if($show_update_form == true || $add_default_commission == true) { ?>
<div role="tabpanel" class="tab-pane clearfix <?php echo $tab1; ?>" id="fromList">
<div class="card-body p-4">
<div class="col-md-12">
	<div class="">
		Note : Commission amount is calculated from the commission which you get from API.
	</div>
	<?php
	if($add_default_commission == false) {
		echo get_agent_details($agent_details);
	}
	?>
</div>
<form method="post" name="commission_form">
	<div class="col-md-12 table-responsive">
		<div class="panel card border-info clearfix">
		<?php if(is_active_airline_module()):
				echo flight_commission_tab($flight_commission_details, @$agent_ref_id, $super_admin_flight_commission);
			endif; ?>
			<?php if(is_active_bus_module()):
				echo bus_commission_tab($bus_commission_details, @$agent_ref_id, $super_admin_bus_commission);
			endif; ?>
			<?php if(is_active_sightseeing_module()):
				echo sightseeing_commission_tab($sightseeing_commission_details,@$agent_ref_id,$super_admin_sightseeing_commission);
			endif; ?>
			<?php if(is_active_transfer_module()):
				echo transfer_commission_tab($transfer_commission_details,@$agent_ref_id,$super_admin_transfer_commission);
			endif; ?>

		</div>
	</div>
	<div class="col-md-12 mt-3">
      <button type="submit" class="btn btn-sm btn-gradient-success"><i class="bi bi-check-circle"></i> Update</button>
      <button type="reset" class="btn btn-sm btn-gradient-warning"><i class="bi bi-arrow-clockwise"></i> Reset</button>
    </div>
</form>
</div>
</div>
<?php } ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab2; ?>" id="tableList">
<div class="p-4">
<?php
if($add_default_commission == false) {
	echo get_table(@$agent_list);
}
?></div>
</div>
</div>
</div>
</div>
<!-- PANEL WRAP END -->
<!-- HTML END -->
<?php
function get_table($table_data='')
{
	$table = '';
	$table .= '<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">'.$GLOBALS['CI']->pagination->create_links().'</div>';
	$table .= '<form method="GET" role="search" class="d-flex gap-2 mb-3" id="filter_agency_form"><input type="hidden" name="filter" value="search_agent"><input type="text" autocomplete="off" placeholder="Search Agency, Email, Mobile, ID" class="form-control" style="max-width:280px" id="filter_agency" name="filter_agency" value="'.@$_GET['filter_agency'].'"><button title="Search: Agency, Email, Mobile, ID" class="btn btn-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button><a title="Clear Search" class="btn btn-secondary btn-sm" href="'.base_url().'index.php/management/agent_commission"><i class="bi bi-x-circle"></i></a></form>';
	$table .= '<div class="clearfix table-responsive reprt_tble"><table class="table table-sm table-bordered example3">';
	$table .= '<thead><tr>
   <th><i class="bi bi-hash"></i> '.get_app_message('AL006').'</th>
   <th><i class="bi bi-building"></i> Agency Name</th>
   <th><i class="bi bi-upc"></i> Agency ID</th>
   <th><i class="bi bi-person"></i> Agent Name</th>
   <th><i class="bi bi-percent"></i> Commission(%)</th>
   <th><i class="bi bi-telephone"></i> Contact</th>
   <th><i class="bi bi-gear"></i> Action</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {
			
			$flight_commission = (is_active_airline_module()) ? 'Flight:'.floatval($v['flight_api_value']) : '';
			$bus_commission = (is_active_bus_module()) ? 'Bus:'.floatval($v['bus_api_value']) : '';
			$sightseeing_commission = (is_active_sightseeing_module())?	'Activities:'.floatval($v['sightseeing_api_value']): '';
			$transfer_commission = (is_active_transfer_module())?'Transfers:'.floatval($v['transfer_api_value']) : '';

			$commission_details = $flight_commission.'  '.$bus_commission.' '.$sightseeing_commission.'  '.$transfer_commission;
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td>'.(empty($v['agency_name']) == false ? $v['agency_name'] : 'Not Added').'</td>
			<td>'.provab_decrypt($v['uuid']).'</td>
			<td>'.get_enum_list('title', $v['title']).' '.$v['first_name'].' '.$v['last_name'].'</td>
			<td>'.$commission_details.'</td>
			<td>'.$v['phone'].'-'.provab_decrypt($v['email']).'</td>
			<td>'.update_commission_button($v['user_id']).'</td>
</tr>';
		}
	} else {
		$table .= '<tr><td colspan="7" class="text-center no-data-found"><div class="empty-state"><i class="bi bi-inbox" aria-hidden="true"></i><h4>No Data Found</h4><p>'.get_app_message('AL005').'</p></div></td></tr>';
	}
	$table .= '</tbody></table></div>';
	$table .= '<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">'.$GLOBALS['CI']->pagination->create_links().'</div>';
	return $table;
}
function update_commission_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/management/agent_commission?agent_ref_id='.base64_encode($id).'" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> '.get_app_message('AL00318').'</a>';
}
function get_agent_details($agent_details)
{
	$agent_name = get_enum_list('title', $agent_details['title']).' '.ucfirst($agent_details['first_name']).' '.$agent_details['last_name'];
	$agency_name = $agent_details['agency_name'];
	$agent_logo = $GLOBALS['CI']->template->domain_images(get_profile_image($agent_details['agent_logo']));
	$email = provab_decrypt($agent_details['email']);
	$phone = $agent_details['country_code'].$agent_details['phone'];
	$address = $agent_details['address'];
	$agent_id = provab_decrypt($agent_details['uuid']);
	$status = get_enum_list('status', $agent_details['status']);
	$details = '<div class="col-md-12">
			    <div class="text-info">
			    	 <h4><i class="bi bi-building"></i> '.$agency_name.' - '.$agent_id.'</h4>
			    </div>
			    </div>';
	return $details;
}
function flight_commission_tab($flight_commission_details, $agent_ref_id, $super_admin_flight_commission)
{
	if(isset($flight_commission_details['origin']) == true && intval($flight_commission_details['origin']) > 0) {
		$flight_commission_origin = $flight_commission_details['origin'];
		$flight_commission_value = $flight_commission_details['value'];
	} else {
		$flight_commission_origin = 0;
		$flight_commission_value = 0;
	}
	$comm = $super_admin_flight_commission['api_value'] + 0;
	$details = '
    
        <div class="card-header">
        	<div class="card-title"><i class="bi bi-airplane"></i> Flight Commission</div>
       </div>
        <div class="card-body commission_wrapper">
	        <div class="hide hidden_commission_details">
	        <input type="hidden" name="module[]" value="'.META_AIRLINE_COURSE.'">
	        <input type="hidden" name="agent_ref_id[]" value="'.base64_encode(intval($agent_ref_id)).'">
	        <input type="hidden" name="commission_origin[]" value="'.intval($flight_commission_origin).'">
	        <input type="hidden" name="commission[]" value="'.floatval($flight_commission_value).'">
	        </div>
            <div class="col-md-12">
	               Agents Get 
	               <select name="api_value[]" class="api_value" data-superadmin_commission="'.floatval($super_admin_flight_commission['api_value']).'">
	               	<option value="0">0</option>
	               	'.generate_options(numeric_dropdown(array('size' => ($super_admin_flight_commission['api_value']*2), 'divider' => 2)), array(floatval((@$flight_commission_details['api_value'])))).'
	               </select>
	             	% 
	             	Commission From <strong>'.$comm.'%</strong> Commission
            </div>
        </div>
    ';
	return $details;
}
function bus_commission_tab($bus_commission_details, $agent_ref_id, $super_admin_bus_commission)
{
	
	if(isset($bus_commission_details['origin']) == true && intval($bus_commission_details['origin']) > 0) {
		$bus_commission_origin = $bus_commission_details['origin'];
		$bus_commission_value = $bus_commission_details['value'];
	} else {
		$bus_commission_origin = 0;
		$bus_commission_value = 0;
	}
	$comm = $super_admin_bus_commission['api_value'] + 0;
	$details = '
    
        <div class="card-header">
        	<div class="card-title"><i class="bi bi-bus-front"></i> Bus Commission</div>
       </div>
        <div class="card-body commission_wrapper" >
	        <div class="hide hidden_commission_details">
	        <input type="hidden" name="module[]" value="'.META_BUS_COURSE.'">
	        <input type="hidden" name="agent_ref_id[]" value="'.base64_encode(intval($agent_ref_id)).'">
	        <input type="hidden" name="commission_origin[]" value="'.intval($bus_commission_origin).'">
	        <input type="hidden" name="commission[]" value="'.floatval($bus_commission_value).'">
	        </div>
            <div class="col-md-12">
	               Agents Get 
	               <select name="api_value[]" class="api_value" data-superadmin_commission="'.floatval($super_admin_bus_commission['api_value']).'">
	               	<option value="0">0</option>
	               	'.generate_options(numeric_dropdown(array('size' => ($super_admin_bus_commission['api_value']*2), 'divider' => 2)), array(floatval((@$bus_commission_details['api_value'])))).'
	               </select>
	             	% 
	             	Commission From <strong>'.$comm.'%</strong> Commission
            </div>
        </div>
    ';
	return $details;
}
function sightseeing_commission_tab($sightseeing_commission_details, $agent_ref_id, $super_admin_sightseeing_commission){

	if(isset($sightseeing_commission_details['origin']) == true && intval($sightseeing_commission_details['origin']) > 0) {
		$sightseeing_commission_origin = $sightseeing_commission_details['origin'];
		$sightseeing_commission_value = $sightseeing_commission_details['value'];
	} else {
		$sightseeing_commission_origin = 0;
		$sightseeing_commission_value = 0;
	}
	$comm = $super_admin_sightseeing_commission['api_value'] + 0;
	$details = '
    
        <div class="card-header">
        	<div class="card-title"><i class="bi bi-binoculars"></i> Activities Commission</div>
       </div>
        <div class="card-body commission_wrapper" >
	        <div class="hide hidden_commission_details">
	        <input type="hidden" name="module[]" value="'.META_SIGHTSEEING_COURSE.'">
	        <input type="hidden" name="agent_ref_id[]" value="'.base64_encode(intval($agent_ref_id)).'">
	        <input type="hidden" name="commission_origin[]" value="'.intval($sightseeing_commission_origin).'">
	        <input type="hidden" name="commission[]" value="'.floatval($sightseeing_commission_value).'">
	        </div>
            <div class="col-md-12">
	               Agents Get 
	               <select name="api_value[]" class="api_value" data-superadmin_commission="'.floatval($super_admin_sightseeing_commission['api_value']).'">
	               	<option value="0">0</option>
	               	'.generate_options(numeric_dropdown(array('size' => ($super_admin_sightseeing_commission['api_value']*2), 'divider' => 2)), array(floatval((@$sightseeing_commission_details['api_value'])))).'
	               </select>
	             	% 
	             	Commission From <strong>'.$comm.'%</strong> Commission
            </div>
        </div>
    ';
	return $details;
}
function transfer_commission_tab($transfer_commission_details, $agent_ref_id, $super_admin_transfer_commission){

	if(isset($transfer_commission_details['origin']) == true && intval($transfer_commission_details['origin']) > 0) {
		$transfer_commission_origin = $transfer_commission_details['origin'];
		$transfer_commission_value = $transfer_commission_details['value'];
	} else {
		$transfer_commission_origin = 0;
		$transfer_commission_value = 0;
	}
	$comm = $super_admin_transfer_commission['api_value'] + 0;
	$details = '
    
        <div class="card-header">
        	<div class="card-title"><i class="bi bi-car-front"></i> Transfer Commission</div>
       </div>
        <div class="card-body commission_wrapper" >
	        <div class="hide hidden_commission_details">
	        <input type="hidden" name="module[]" value="'.META_TRANSFERV1_COURSE.'">
	        <input type="hidden" name="agent_ref_id[]" value="'.base64_encode(intval($agent_ref_id)).'">
	        <input type="hidden" name="commission_origin[]" value="'.intval($transfer_commission_origin).'">
	        <input type="hidden" name="commission[]" value="'.floatval($transfer_commission_value).'">
	        </div>
            <div class="col-md-12">
	               Agents Get 
	               <select name="api_value[]" class="api_value" data-superadmin_commission="'.floatval($super_admin_transfer_commission['api_value']).'">
	               	<option value="0">0</option>
	               	'.generate_options(numeric_dropdown(array('size' => ($super_admin_transfer_commission['api_value']*2), 'divider' => 2)), array(floatval((@$transfer_commission_details['api_value'])))).'
	               </select>
	             	% 
	             	Commission From <strong>'.$comm.'%</strong> Commission
            </div>
        </div>
    ';
	return $details;
}

?>
<script>
$(document).ready(function() {
	//Update Commission Percentage
	$(document).on('change', '.api_value', function(){
		var api_value = parseFloat($(this).val().trim());
		//alert(api_value);
		var api_commission = parseFloat($(this).data('superadmin_commission'));
		//alert(api_commission);
		var given_percentage = (api_value/api_commission)*100;
		$(this).closest('div.commission_wrapper').find('div.hidden_commission_details').find('input[name="commission[]"]').val(given_percentage);
		});
	var cache = {};
	$('#filter_agency', 'form#filter_agency_form').autocomplete({
		source:  function( request, response ) {
	        var term = request.term;
	        if ( term in cache ) {
	          response( cache[ term ] );
	          return;
	        } else {
	        	$.getJSON( app_base_url+"index.php/ajax/auto_suggest_agency_name", request, function( data, status, xhr ) {
	                cache[ term ] = data;
	                response( cache[ term ] );
	              });
	        }
	      },
	    minLength: 1
	 });
});
</script>