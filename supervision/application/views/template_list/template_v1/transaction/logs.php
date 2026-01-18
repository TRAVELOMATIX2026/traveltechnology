<?php

if (is_array($search_params)) {

    extract($search_params);

}

$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));

$this->current_page->set_datepicker($_datepicker);

$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));

?>

<div class="bodyContent col-md-12">
<h4 class="mb-3">Transaction Logs</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="card-body p-0">

            <div id="show-search">

                <form method="GET" autocomplete="off">

                    <div class="form-group row gap-0 mb-0">
                        <?php if(is_active_module('b2b')): ?>
                        <div class="col-sm-6 col-md-3">
                            <label>
                                Agent
                            </label>
                            <select class="form-control" name="agent_id">
                                <option value="">Select Agent</option>
                                <option value="all" <?= (($agent_id == 'all')?'selected':''); ?>>All</option>
                                <?= generate_options($agent_list, array(@$agent_id)) ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                Transaction Type
                            </label>
                            <select class="form-control" name="transaction_type">
                                <option value="">All</option>
                                <?= generate_options(get_enum_list('transaction_type'), array(@$transaction_type)) ?>
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                Reference Number
                            </label>
                            <input type="text" class="form-control" name="app_reference" value="<?= @$app_reference ?>" placeholder="Reference Number">
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                From Date
                            </label>
                            <input type="text" readonly id="created_datetime_from" class="form-control" name="created_datetime_from" value="<?= @$created_datetime_from ?>" placeholder="Request Date">
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                To Date
                            </label>

                            <input type="text" readonly id="created_datetime_to" class="form-control disable-date-auto-update" name="created_datetime_to" value="<?= @$created_datetime_to ?>" placeholder="Request Date">

                        </div>

                        <div class="col-sm-6 col-md-6">
                        <label>&nbsp;</label>
                        <div class="d-flex flex-wrap justify-content-end gap-3">
                        <button type="submit" class="btn btn-gradient-primary"><i class="bi bi-search"></i> Search</button> 
                        
                        <button type="reset" class="btn btn-gradient-warning"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                        </div>  

                        <!-- <a href="<?php echo base_url() . 'index.php/transaction/logs? ' ?>" id="clear-filter" class="btn btn-gradient-info"><i class="fa fa-times"></i> Clear Filter</a> -->

                    </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<div class="d-flex justify-content-between align-items-center">
<div class="float-start my-3">
<?php echo $this->pagination->create_links(); ?> <span class="totl_bkngs_flt">Total <?php echo $total_rows ?> Records</span>
</div> 
<?php if($total_records > 0): ?>
<div class="d-flex gap-2">
    <a href="<?php echo base_url(); ?>index.php/transaction/export_account_ledger/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" target="_blank" class="btn btn-info">
        <i class="bi bi-file-earmark-excel"></i> Export to Excel
    </a> 
    <a href="<?php echo base_url(); ?>index.php/transaction/export_account_ledger/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" target="_blank" class="btn btn-info">
        <i class="bi bi-file-earmark-pdf"></i> PDF
    </a>
</div>
<?php endif; ?>
</div>

<div class="clearfix table-responsive reprt_tble"><!-- PANEL BODY START -->

           
            <table class="table table-sm table-bordered example3" id="transaction_logs_table">
				<thead>
					<tr>
						<th>Sl. No.</th>
						<th>Agent</th>
						<th>Transaction Date</th>
						<th>Reference Number</th>
						<th>Transaction Type</th>
						<th>Amount</th>
						<th>Description</th>
					</tr>
				</thead>
			<?php
			if (valid_array($table_data)) {
				foreach ($table_data as $k => $v) {

					if ($v['transaction_owner_id'] == 0) {
						$user_info = 'Guest';
					} else {
						$user_info = $v['username'];
					}

				?>
					<tr>
						<td><?=($k+1)?></td>
						<td><?=$v['agent_name']?></td>
						<td><?=app_friendly_date($v['created_datetime'])?></td>
						<td><?=$v['app_reference']?></td>
						<td><?=ucfirst($v['transaction_type'])?></td>
						<td><?=(abs($v['grand_total'])).'-'.$v['currency']?></td>				
						<td><?=$v['remarks']?></td>						
					</tr>
				<?php
				}
			} else {
				?>
				<tr>
					<td colspan="7" class="text-center no-data-found">
						<div class="empty-state">
							<i class="bi bi-inbox" aria-hidden="true"></i>
							<h4>No Data Found</h4>
							<p>No transaction records match your search criteria. Please try adjusting your filters.</p>
						</div>
					</td>
				</tr>
				<?php
			}
			?>
			</table>
        </div>
