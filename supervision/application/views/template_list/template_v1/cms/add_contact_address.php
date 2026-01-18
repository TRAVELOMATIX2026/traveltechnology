<?php

   $tab1 = 'active';
   $get_data = $this->input->get();
 	if(isset($get_data) && valid_array($get_data)){
 		$action = base_url().'index.php/cms/add_contact_address';
 	}
 	else{
 		$action = base_url().'index.php/cms/add_contact_address';
 	}
?>

<div class="bodyContent col-md-12">
<h4 class="mb-3">Contact Address</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <form role="form" enctype="multipart/form-data" method="POST" action="<?= $action; ?>" autocomplete="off" name="home_page_heading" class="p-4">
            <span class="error_msg"><?php $msg = $this->uri->segment(3); if(isset($msg)){ echo urldecode($msg); }?></span>

                    <div class="form-group row gap-0 mb-0">
                        <div class="col-sm-12">
                            <label>
                                Address
                            </label>
                            <textarea class="form-control" rows="6" name="address" required><?php echo @$address;?></textarea>
                            <input type="hidden" name="domain_id" value="<?php echo @$domain_id?>">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Email
                            </label>
                            <input type="text" id="email" class="form-control" placeholder="Email" name="email" value="<?php echo @$email?>" required>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Phone Number
                            </label>
                            <input type="text" id="phone" class="form-control" placeholder="Phone Number" name="phone" value="<?php echo @$phone?>" required>
                        </div>

                        <div class="col-sm-12">
                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <?php if(isset($title)){
                                    $button = 'Update';
                                }else{
                                    $button = 'Save';
                                }
                                ?>
                                <button class="btn btn-gradient-success" type="submit"><i class="bi bi-check-circle"></i> <?php echo $button; ?></button>
                                <button class="btn btn-gradient-warning" id="promo_codes_form_edit_reset" type="reset"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>

    </div>
</div>

