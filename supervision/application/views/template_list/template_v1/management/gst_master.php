<div class="bodyContent col-md-12">
	<div class="panel card clearfix">
		<!-- PANEL WRAP START -->
		<div class="card-header">
			<!-- PANEL HEAD START -->
			GST Master
		</div>
		<!-- PANEL HEAD START -->
		<div class="card-body">
			<h4>TDS & GST</h4>
			<hr>
			<form method="POST" autocomplete="off"
				action="<?php echo base_url(). 'index.php/management/gst_master/';?>">
				
				<?php 
				if (isset($details) && valid_array($details)) {
				    foreach ($details as $key => $val) {
				        ?><input type="hidden" class="form-control" name="gst_origin[]" value="<?=@$val['origin']?>">
				        <div class="clearfix form-group">
				        	<div class="col-md-2">
				        		<label><?=strtoupper(@$val['module'])?></label>
				        	</div>
        					<!-- <div class="col-md-3">
        						<label>TDS (%)</label> 
        						<input type="text" class="form-control" name="tds[]" value="<?=@$val['tds']?>" placeholder="TDS">
        					</div> -->
        					<div class="col-md-3">
        						<label>GST (%)</label> 
        						<input type="text" class="form-control" name="gst[]" value="<?=@$val['gst']?>" placeholder="GST">
        					</div>
        					
        				</div>
				        
				        <?php 
				    }
				}
				?>
				<div class="clearfix form-group">
					<div class="col-6">
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>