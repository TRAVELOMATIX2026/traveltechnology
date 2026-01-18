
<style>
.dataTables_paginate
{
display:none;
}
#accounts_led_filter {
display:none;
}

</style>
<?php
if (is_array($search_params)) {
	extract($search_params);
}
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));
?>
<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel card clearfix"><!-- PANEL WRAP START -->
			<div class="card-header"><!-- PANEL HEAD START -->
				<div class="card-title"><h3>Account Statement</h3>
				</div>
			</div>
			<!-- PANEL HEAD START -->	
			<div class="card-body">
			<h4>Search Panel</h4>
			<hr>
			<form method="GET" autocomplete="off">
				<div class="form-group row">
					<div class="col-sm-6 col-md-5">
						<label>From Date</label>
						<input type="text" readonly id="created_datetime_from" class="form-control" name="created_datetime_from" value="<?=@$created_datetime_from?>" placeholder="From Date">
					</div>
					<div class="col-sm-6 col-md-5">
						<label>To Date</label>
						<input type="text" readonly id="created_datetime_to" class="form-control disable-date-auto-update" name="created_datetime_to" value="<?=@$created_datetime_to?>" placeholder="To Date">
					</div>
				</div>
				<div class="col-sm-12 card card-body card card-body p-2">
					<button type="submit" class="btn btn-gradient-primary"><i class="bi bi-search"></i> Search</button> 
					<button type="reset" class="btn btn-gradient-warning"><i class="bi bi-arrow-clockwise"></i> Reset</button> 
					<a href="<?php echo base_url().'index.php/management/account_ledger'?>" id="clear-filter" class="btn btn-gradient-info"><i class="bi bi-x-lg"></i> Clear Filter</a>
				</div>
			</form>
			</div>

			<?php if($total_records > 0): ?>
			<a href="<?php echo base_url(); ?>index.php/management/export_account_ledger/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">
					<button class="btn btn-primary" type="button"><i class="bi bi-file-earmark-excel" aria-hidden="true"></i> Export to Excel</button>
			</a> 
			<a href="<?php echo base_url(); ?>index.php/management/export_account_ledger/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" target="_blank">
					<button class="btn btn-primary" type="button"><i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> PDF</button>
			</a>
			<?php endif; ?>
			<div class="card-body"><!-- PANEL BODY START -->
				<div class="">
					<?php echo $this->pagination->create_links();?>
					<div><?php echo 'Total <strong>'.$total_records.'</strong> transaction found.'?></div>
				</div>
			<div class="table-responsive">
			<table id="accounts_led" class="table table-sm table-bordered table-striped">
			<thead>	
				<tr>
					<th>Sl. No.</th>
					<th>Date</th>
					<th>Reference Number</th>
					<th>Description</th>
					<th>Debit</th>
					<th>Credit</th>
					<th>Opening Balance</th>
					<th>Closing Balance</th>
				</tr>
			</thead>	
			<tbody>
				<?php
				$segment_3 = $GLOBALS['CI']->uri->segment(3);
				$current_record = (empty($segment_3) ? 1 : $segment_3+1);
				
					if(valid_array($table_data) == true){ 
						
						$i=0;
						foreach($table_data as $k => $v){ ?>
							<tr>
								<td><?=($re = $current_record++)?></td>
								<td><?=app_friendly_datetime($v['transaction_date'])?></td>
								<td><?=$v['reference_number']?></td>
								<td><strong><?=$v['description']?></strong>
									<br>
									<small><?=$v['transaction_details']?></small>
								</td>
								<td><?=(empty($v['debit_amount']) == false ? $v['debit_amount'] : '-')?></td>
								<td><?=(empty($v['credit_amount']) == false ? $v['credit_amount']: '-')?></td>
								<td><?=$v['opening_balance']?></td>
								<td><?=$v['closing_balance']?></td>
							</tr>
					<?php $i++;
						if(empty($segment_3 = $GLOBALS['CI']->uri->segment(3)))
						{if($re>=20){break;}}else{
						if($i>=20){break;}
						} } ?>
				<?php }	else{ ?>
					<tr><td colspan="9">No Transaction Found !!</td></tr>
				<?php }?>
				</tbody>
			</table>
			</div>
			<div class="">
				<?php echo $this->pagination->create_links();?> 
			</div>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL END -->
</div>
