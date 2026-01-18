<?php if (form_visible_operation()) {
	$tab1 = " active ";
	$tab2 = "";
} else {
	$tab2 = " active ";
	$tab1 = "";
}
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
if (is_array($search_params)) {
	extract($search_params);
}
?>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent col-md-12">
<h4 class="mb-3">B2B User Management</h4>
<div class="panel card clearfix"><!-- PANEL WRAP START -->
<div class="p-0"><!-- PANEL BODY START -->
<div class="d-flex justify-content-between align-items-center px-3 pt-3 flex-wrap gap-2">
	<ul class="nav nav-tabs mb-0" role="tablist" id="myTab">
		<li role="presentation" class="<?php echo $tab1; ?>"><a id="fromListHead" href="#fromList" aria-controls="home" role="tab" data-bs-toggle="tab"><i class="bi bi-pencil-square"></i> <?php echo get_app_message('AL0014');?></a></li>
		<li role="presentation" class="<?php echo $tab2; ?>"><a href="#tableList" aria-controls="profile" role="tab" data-bs-toggle="tab"><i class="bi bi-people-fill"></i> <?=(isset($_GET['user_status']) == true ? (empty($_GET['user_status']) == true ? 'Inactive' : 'Active') : '')?> Agent List</a></li>
	</ul>
	<?php if (intval(@$eid) > 0) { $i_fil = @$_GET['user_status'] ? 'user_status='.intval($_GET['user_status']) : ''; ?>
	<a class="btn btn-sm btn-outline-danger" href="<?php echo base_url(); ?>index.php/user/b2b_user?<?php echo $i_fil; ?>"><i class="bi bi-x-circle"></i> Cancel Editing</a>
	<?php } ?>
</div>
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab1; ?>" id="fromList">
<div class="card-body p-4">
<?php 
/************************ GENERATE CURRENT PAGE FORM ************************/
$form_data['user_type'] = B2B_USER;
$city_text = '';
$country_text = '';
$dafaultCity = '';
$dafaultState = '';
if (isset($eid) == false || empty($eid) == true) {
	/*** GENERATE ADD PAGE FORM ***/
	$form_data['country_code'] = (isset($form_data['country_code']) == false ? INDIA_CODE : $form_data['country_code']);
	$form_data['country_name'] = $form_data['api_country_list']['api_country_list_fk'];
	//$form_data['city'] = $form_data['api_country_list']['api_city_list_fk'];
	$dafaultCity = $form_data['api_country_list']['api_city_list_fk'];
	$dafaultState = $form_data['api_country_list']['api_state_list_fk'];//debug($dafaultCity);exit;
	//$form_data['country_name'] = (isset($form_data['country_code']) == false ? INDIA : $form_data['country_code']);
	//$form_data['country_name'] = (isset($form_data['country_name']) == false ? INDIA : $form_data['country_name']);
	echo $this->current_page->generate_form('b2b_user', $form_data);
} else {
	//$form_data['country_name'] =  INDIA;
	$city_text = $form_data['city_name'];
	$dafaultCity = $form_data['city'];
	$country_text = $form_data['country_name'];
	$dafaultState = $form_data['state'];
	echo $this->current_page->generate_form('b2b_user_edit', $form_data);
}
/************************ GENERATE UPDATE PAGE FORM ************************/
?></div>
</div>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab2; ?>" id="tableList">
<div class="p-4">
<form method="GET" autocomplete="off" id="search_filter_form">
	<input type="hidden" name="user_status" value="<?=@$user_status?>" >
	<div class="form-group row gap-0 mb-0">
		<div class="col-md-3 col-6 mb-2">
			<label>Agency Name</label>
			<input type="text" placeholder="Agency Name" value="<?=@$agency_name?>" name="agency_name" class="search_filter form-control">
		</div>
		<div class="col-md-3 col-6 mb-2">
			<label>Agency ID</label>
			<input type="text" placeholder="Agency ID" value="<?=@$uuid?>" name="uuid" class="search_filter form-control">
		</div>
		<div class="col-md-3 col-6 mb-2">
			<label>PAN</label>
			<input type="text" placeholder="PAN" value="<?=@$pan_number?>" name="pan_number" class="search_filter form-control">
		</div>
		<div class="col-md-3 col-6 mb-2">
			<label>Email</label>
			<input type="text" placeholder="Email" value="<?=@$email?>" name="email" class="search_filter form-control">
		</div>
		<div class="col-md-3 col-6 mb-2">
			<label>Phone</label>
			<input type="text" placeholder="Phone Number" value="<?=@$phone?>" name="phone" class="search_filter numeric form-control">
		</div>
		<div class="col-md-3 col-6 mb-2">
			<label>Member Since</label>
			<input type="text" placeholder="Registration Date" readonly value="<?=@$created_datetime_from?>" id="created_datetime_from" name="created_datetime_from" class="search_filter form-control">
		</div>
		<div class="col-12 d-flex flex-wrap align-items-end gap-2 mt-2">
			<button class="btn btn-gradient-primary" type="submit"><i class="bi bi-search"></i> Search</button>
			<button class="btn btn-gradient-warning" type="reset"><i class="bi bi-arrow-clockwise"></i> Reset</button>
			<button class="btn btn-secondary" type="button" id="clear_search_filters"><i class="bi bi-x-circle"></i> Clear Filters</button>
		</div>
	</div>
</form>
<div class="clearfix my-3"></div>
<?php echo get_table(@$table_data, $total_rows); ?>
</div>
</div>
</div>
</div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->
<?php
function get_table($table_data='', $total_rows=0)
{
	$table = '';
	$table .= '<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">'.$GLOBALS['CI']->pagination->create_links().'<span class="text-muted">Total '.$total_rows.' agents</span></div>';
	$table .= '<form method="GET" role="search" class="d-flex gap-2 mb-3" id="filter_agency_form"><input type="hidden" name="user_status" value="'.@$_GET['user_status'].'"><input type="hidden" name="filter" value="search_agent"><input type="text" autocomplete="off" placeholder="Search Agency, Email, Mobile, ID" class="form-control" style="max-width:280px" id="filter_agency" name="filter_agency" value="'.@$_GET['filter_agency'].'"><button title="Search: Agency, Email, Mobile, ID" class="btn btn-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button></form>';
	$table .= '<div class="clearfix table-responsive reprt_tble"><table class="table table-sm table-bordered example3">';
	$table .= '<thead><tr>
   <th><i class="bi bi-hash"></i> '.get_app_message('AL006').'</th>
   <th><i class="bi bi-building"></i> Agency Name</th>
   <th><i class="bi bi-upc"></i> Agency ID</th>
   <th><i class="bi bi-person"></i> Agent Name</th>
   <th><i class="bi bi-globe"></i> Country</th>
   <th><i class="bi bi-geo"></i> State</th>
   <th><i class="bi bi-geo-alt"></i> City</th>
   <th><i class="bi bi-wallet2"></i> Balance</th>
   <th><i class="bi bi-credit-card"></i> Credit Limit</th>
   <th><i class="bi bi-cash"></i> Due Amount</th>
   <th><i class="bi bi-telephone"></i> Mobile</th>
   <th><i class="bi bi-envelope"></i> Email</th>
   <th><i class="bi bi-file-earmark"></i> Incorp Cert</th>
   <th><i class="bi bi-receipt"></i> GST</th>
   <th><i class="bi bi-person-badge"></i> Owner Proof</th>
   <th><i class="bi bi-house"></i> Address Proof</th>
   <th><i class="bi bi-box-arrow-in-down"></i> <abbr title="Pending Deposit Request">Deposit Req</abbr></th>';
	if (is_active_airline_module()) { $table .= '<th>Flight</th>'; }
	if (is_active_hotel_module()) { $table .= '<th>Hotel</th>'; }
	if (is_active_bus_module()) { $table .= '<th>Bus</th>'; }
	if (is_active_transferv1_module()) { $table .= '<th>Transfers</th>'; }
	if (is_active_sightseeing_module()) { $table .= '<th>Sightseeing</th>'; }
	$table .= '<th><i class="bi bi-toggle-on"></i> Status</th><th><i class="bi bi-calendar3"></i> CreatedOn</th><th><i class="bi bi-gear"></i> Action</th></tr></thead><tbody>';
   // debug($table_data);exit;
	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		$rep_url = base_url().'index.php/report/';
		$dep_url = base_url().'index.php/management/b2b_balance_manager';
	
		foreach ($table_data as $k => $v) {

			/*$last_login = 'Last Login : '.last_login($v['last_login']);
			$login_status = login_status($v['logout_date_time']);*/
			$dep_req = '';
			if (isset($v['dep_req']) == true && isset($v['dep_req']['pending']) == true) {
				$dep_req = intval($v['dep_req']['pending']['count']);
			} else {
				$dep_req = 0;
			}
			
			$booking_summ = '';

			if (is_active_airline_module()) {
				$booking_summ .= '<td>'.intval(@$v['booking_summ']['flight']['BOOKING_CONFIRMED']['count']).' <a target="_blank" href="'.$rep_url.'b2b_flight_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}
			
			if (is_active_hotel_module()) {
				$booking_summ .= '<td>'.intval(@$v['booking_summ']['hotel']['BOOKING_CONFIRMED']['count']).'  <a target="_blank" href="'.$rep_url.'b2b_hotel_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}
			
			if (is_active_bus_module()) {
				$booking_summ .= '<td>'.intval(@$v['booking_summ']['bus']['BOOKING_CONFIRMED']['count']).'  <a target="_blank" href="'.$rep_url.'b2b_bus_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}
			if (is_active_transferv1_module()) {
				$booking_summ .= '<td>'.intval(@$v['booking_summ']['transfer']['BOOKING_CONFIRMED']['count']).'  <a target="_blank" href="'.$rep_url.'b2b_transfers_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}
			if (is_active_sightseeing_module()) {
				$booking_summ .= '<td>'.intval(@$v['booking_summ']['sightseeing']['BOOKING_CONFIRMED']['count']).'  <a target="_blank" href="'.$rep_url.'b2b_activities_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}
			$action_tab = '<div class="d-flex flex-wrap gap-2">';
			$action_tab .= get_privilege_button('B2B', $v['status'], $v['user_id']);
			$action_tab .= get_edit_button($v['user_id']);
			if($v['status'] == ACTIVE) {
				$action_tab .= send_password($v['user_id'], $v['uuid']);
			}
			$action_tab .= delete_agent_button($v['user_id'], $v['uuid']);
			$action_tab .= view_account_ledger($v['user_id'],$v['created_datetime']);
			$action_tab .= update_credit_limit($v['user_id']);
			$action_tab .= '</div>';
			//Booking
			$table .= '<tr>
            <td>'.(++$current_record).'</td>
            <td>'.(empty($v['agency_name']) ? 'Not Added' : $v['agency_name']).'</td>
            <td>'.provab_decrypt($v['uuid']).'</td>
            <td>'.get_enum_list('title', $v['title']).' '.$v['first_name'].' '.$v['last_name'].'</td>
            <td>'.$v['country_name'].'</td>
            <td>'.$v['state_name'].'</td>
            <td>'.$v['city_name'].'</td>
            <td>'.roundoff_number($v['agent_balance']).'</td>
            <td>'.roundoff_number($v['credit_limit']).'</td>
            <td>'.roundoff_number($v['due_amount']).'</td>
            <td>'.$v['phone'].'</td>
            <td>'.provab_decrypt($v['email']).'</td>
            <td><img src="'.(!empty($v['c_certificate']) ? str_replace('supervision/','',base_url()).'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/agent_proofs/' . $v['c_certificate'] : str_replace('supervision/','',base_url()).'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/agent_proofs/no_image.png').'" alt="Certificate" style="max-width: 100px; max-height: 100px;"/></td>
            <td><img src="'.(!empty($v['c_gst']) ? str_replace('supervision','',base_url()).'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/agent_proofs/' . $v['c_gst'] : str_replace('supervision/','',base_url()).'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/agent_proofs/no_image.png').'" alt="GST" style="max-width: 100px; max-height: 100px;"/></td>
            <td><img src="'.(!empty($v['c_owner_proof']) ? str_replace('supervision','',base_url()).'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/agent_proofs/' . $v['c_owner_proof'] : str_replace('supervision/','',base_url()).'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/agent_proofs/no_image.png').'" alt="Owner Proof" style="max-width: 100px; max-height: 100px;"/></td>
            <td><img src="'.(!empty($v['c_address_proof']) ? str_replace('supervision','',base_url()).'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/agent_proofs/' . $v['c_address_proof'] : str_replace('supervision/','',base_url()).'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/agent_proofs/no_image.png').'" alt="Address Proof" style="max-width: 100px; max-height: 100px;"/></td>
            <td>'.$dep_req.' <a target="_blank" href="'.$dep_url.'?uuid='.$v['uuid'].'">view</a></td>
            '.$booking_summ.'
            <td>'.get_status_toggle_button($v['status'], $v['user_id'], $v['uuid']).'</td>
            <td>'.app_friendly_absolute_date($v['created_datetime']).'</td>
            <td>'.$action_tab.'</td>
</tr>';

		}
	} else {
		$colspan = 20;
		if (is_active_airline_module()) $colspan++;
		if (is_active_hotel_module()) $colspan++;
		if (is_active_bus_module()) $colspan++;
		if (is_active_transferv1_module()) $colspan++;
		if (is_active_sightseeing_module()) $colspan++;
		$table .= '<tr><td colspan="'.$colspan.'" class="text-center no-data-found"><div class="empty-state"><i class="bi bi-inbox" aria-hidden="true"></i><h4>No Data Found</h4><p>'.get_app_message('AL005').'</p></div></td></tr>';
	}
	$table .= '</tbody></table></div>';
	return $table;
}

function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="badge bg-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '<span class="badge bg-danger"><i class="fa fa-circle-o"></i> '.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">'.get_app_message('AL0020').'</a>';
	}
}

function get_status_toggle_button($status, $user_id, $uuid)
{
	$status_options = get_enum_list('status');
	return '<select autocomplete="off" class="toggle-user-status" data-user-id="'.$user_id.'" data-uuid="'.$uuid.'">'.generate_options($status_options, array($status)).'</select>';
	/*if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
	}*/
}

function get_edit_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/user/b2b_user?'.$_SERVER['QUERY_STRING'].'&eid='.$id.'" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> View Profile</a>';
}
function send_password($user_id, $uuid)
{
	return '<a role="button" href="#" class="btn btn-sm btn-info send_agent_new_password" data-user-id="'.$user_id.'" data-uuid="'.$uuid.'"><i class="bi bi-send"></i> Send Password</a>';
}
function delete_agent_button($user_id, $uuid)
{
	return '<a role="button" href="#" class="btn btn-sm btn-danger delete_agent" data-user-id="'.$user_id.'" data-uuid="'.$uuid.'"><i class="bi bi-trash"></i> Delete</a>';
}
function view_account_ledger($user_id,$date){
	return '<a role="button" href="'.base_url().'management/account_ledger?agent_id='.$user_id.'" target="_blank" class="btn btn-sm btn-info"><i class="bi bi-journal-text"></i> Ledger</a>';
}
function update_credit_limit($user_id){
	return '<a role="button" href="'.base_url().'management/credit_balance_show?agent_id='.$user_id.'" target="_blank" class="btn btn-sm btn-secondary"><i class="bi bi-credit-card"></i> Credit Limit</a>';
}
function get_privilege_button($user_type, $user_status, $id)
{
	if($user_type == 'B2B' && $user_status == 1){
		return '<a role="button" href="'.base_url().'index.php/user/user_privilege?'.$_SERVER['QUERY_STRING'].'&eid='.$id.'" class="btn btn-sm btn-info"><i class="bi bi-shield-lock"></i> Privileges</a>';
	}
	return '';
}

?>
<script>
$(document).ready(function() {
	
	 //set dropdownlist selected
	var objSelect =document.getElementById("country_name");	
	var citySelect = document.getElementById("city");

	var country_edit_text = "<?= $country_text; ?>";
	var city_edit_text = "<?= $city_text; ?>";
	var default_city = "<?= $dafaultCity; ?>";
	var default_state = "<?= $dafaultState; ?>";

	if(country_edit_text !=''){

		setSelectedValue(objSelect,country_edit_text);
		
	}
	//set country dropdown list selected
	function setSelectedValue(selectObj, textToSet) {
	    for (var i = 0; i < selectObj.options.length; i++) {	    	
	        if (selectObj.options[i].text.toLowerCase() == textToSet.toLowerCase()) {	

	            selectObj.options[i].selected = true;
	            
	            return;
	        }
	    }
	}
	
	get_state_lists();
	//Enter only numbers
	$("#phone").on("keypress", function(evt){
  
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    });	
	//Enter only numbers and letters
	$("#pan_number").on("keypress", function(event){		
        var ew = event.which;
      
        if ((ew == 0 || ew == 8 )||(ew >= 48 && ew <= 57) || (ew >= 65 && ew <= 90) || (ew >= 97 && ew <= 122 ) ) {
           
            return true;
        }
        return false;
    });
    
    //Enter only letters 
     $("#first_name,#last_name").on("keypress",function(event){
         var inputValue = event.which;
       
        if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0 && inputValue != 8)) { 
            event.preventDefault(); 
        }
    });
    
	//Reset the Search Filters
	$('#clear_search_filters').click(function(){
		$('.search_filter', "form#search_filter_form").val('');
		$("form#search_filter_form").submit();
	});
	//Active/Deactive Agent
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/user/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'activate_account/';
		} else {
			_opp_url = _opp_url+'deactivate_account/';
		}
		_opp_url = _opp_url+$(this).data('user-id')+'/'+$(this).data('uuid');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});
	//Send Agent Password
	$('.send_agent_new_password').on('click', function(e) {
		e.preventDefault();
		var _opp_url = app_base_url+'index.php/user/send_agent_new_password/';
		_opp_url = _opp_url+$(this).data('user-id')+'/'+$(this).data('uuid');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});
	//Delete Agent
	$('.delete_agent').on('click', function(e) {
		e.preventDefault();
		var _opp_url = app_base_url+'index.php/user/delete_agent/';
		_opp_url = _opp_url+$(this).data('user-id')+'/'+$(this).data('uuid');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});
	//Fiter Agent
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
	 
	

	//get city list based on country code
	
	$("#country_name").on("change",function(){
		get_state_lists();
		$("#city").empty().html('<option value = "" selected="">Select City</option>');
	});
	$("#state").on("change",function(){
		get_city_lists();
	});
	function get_city_lists()
    {
      	var country_id = $("#country_name").val();
      	if(country_id == '' || country_id == 'INVALIDIP'){
			$("#city").empty().html('<option value = "" selected="">Select City</option>');
			return false;
      	}
		var state_id = $("#state").val();
      	if(state_id == '' || state_id == 'INVALIDIP'){
			$("#city").empty().html('<option value = "" selected="">Select City</option>');
			return false;
      	}
      	$.get(app_base_url+'index.php/ajax/get_city_lists',{country_id : country_id, state_id:state_id},function( data ) {
			$("#city").empty().html(data);console.log("default_city:",default_city);
			default_city && default_city!='0' && $("#city").find('option[value="' + default_city + '"]').length > 0 && $("#city").val(default_city);
      	});
    }
	function get_state_lists()
    {
		var country_id = $("#country_name").val();
		if(country_id == '' || country_id == 'INVALIDIP'){
			$("#state").empty().html('<option value = "" selected="">Select State</option>');
			return false;
		}
		$("#city").empty().html('<option value = "" selected="">Select City</option>');
      	$.get(app_base_url+'index.php/ajax/get_state_lists',{country_id : country_id},function( data ) {
			$("#state").empty().html(data);
	        default_state && default_state!='0' && $("#state").find('option[value="' + default_state + '"]').length > 0 &&  $("#state").val(default_state) && $('#state').change(); 
      	});
    }

});
</script>
