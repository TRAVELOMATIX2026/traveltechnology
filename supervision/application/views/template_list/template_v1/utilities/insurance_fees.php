<?php 
//debug($insurance[0]['amount']);exit;
?>
<!-- HTML BEGIN -->
<div class="bodyContent">
    <div class="panel card clearfix"><!-- PANEL WRAP START -->
        <div class="card-header"><!-- PANEL HEAD START -->
            <div class="card-title"><i class="fa fa-credit-card"></i>  Insurance Amount</div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="card-body"><!-- PANEL BODY START -->
            <div class="table-responsive" id="checkbox_div">
                <form action="" method="POST" autocomplete="off" class="row">

                    <div class="form-group">
                        <label class="col-sm-3 form-label">Status</label>
                        <div class="col-sm-6">
                            <label class="form-check form-check-inline">
                                <input type="radio" value="1" name="status" <?php if($insurance[0]['status']==1) { echo "checked"; } ?>> Active
                            </label>
                            <label class="form-check form-check-inline">
                                <input type="radio" value="0" name="status" <?php if($insurance[0]['status']==0) { echo "checked"; } ?>> In-Active
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 form-label">Insurance Amount<span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control numeric" value="<?php echo $insurance[0]['amount'] ?>" name="insurance" maxlength="4">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-6 offset-sm-3">
                            <button type="submit" name="submit" class="btn btn-gradient-success"><i class="fa fa-save"></i> Submit</button>
                            <button type="reset" class="btn btn-gradient-warning ml-2"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                    </div>

                </form>
            </div>
        </div><!-- PANEL BODY END -->
    </div><!-- PANEL END -->
</div>
