<?php
$tab1 = 'active';
?>

<div class="bodyContent col-md-12">
<h4 class="mb-3">Manage Email Configuration</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <form role="form" enctype="multipart/form-data" method="POST" action="<?= base_url() . 'general/email_configuration' ?>" autocomplete="off" name="home_page_heading" class="p-4">
            <span class="error_msg"><?php if (isset($message)) {
    echo $message;
} ?></span>

                    <div class="form-group row gap-0 mb-0">
                        <div class="col-sm-12 col-md-6">
                            <label>
                                Username
                            </label>
                            <input type="email" id="username" class="form-control" placeholder="Username" name="username" required value="<?php echo $user_name; ?>">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Password
                            </label>
                            <input type="text" id="password" class="form-control" placeholder="Please Enter Your Password" name="password" required value="<?php echo @$password; ?>">
                            <small class="text-muted">Note: Password will not be displayed for security reasons.</small>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                From
                            </label>
                            <input type="text" id="from" class="form-control" placeholder="From" name="from" required value="<?php echo $from; ?>">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Host
                            </label>
                            <input type="text" id="host" class="form-control" placeholder="Host" name="host" required value="<?php echo $host; ?>">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Port
                            </label>
                            <input type="number" id="port" class="form-control" placeholder="Port" name="port" required value="<?php echo $port; ?>">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                CC Email
                            </label>
                            <input type="email" id="cc_email" class="form-control" placeholder="CC Emails" name="cc_email" value="<?php echo $cc; ?>">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                BCC Email
                            </label>
                            <input type="email" id="bcc_email" class="form-control" placeholder="BCC Emails" name="bcc_email" value="<?php echo $bcc; ?>">
                        </div>

                        <div class="col-sm-12">
                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <?php
                                if (isset($title)) {
                                    $button = 'Update';
                                } else {
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

