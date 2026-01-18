<?php
    // debug($package_cancel_policy);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Package</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1><b><?php echo $package->package_name;?></b></h1>
                <div>
                                    <img
                                        src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($package->image)); ?>"
                                        alt="<?php echo $package->package_name; ?>" width="50%"/>
                                </div>
                                <br/>
                                <div> 
                                    <span><h3><strong> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> </strong> <?php echo isset($package->price)?get_converted_currency_value ( $currency_obj->force_currency_conversion ( $package->price ) ):0; ?> Only</h3></div>
                            <h4>*Per Person</h4>
                            <h5><?php echo isset($package->duration)?($package->duration-1):0; ?> Nights / <?php echo isset($package->duration)?$package->duration:0; ?> Days</h5></span>
                        </div><br/>
                                <div>
                                    <h3><b>Overview</b></h3>
                                    <p> <?php echo $package->package_description; ?></p>
                                </div>
                                <div>
                                    <h3><b>Detailed Itinerary</b></h3>
                                    <?php
                                        foreach ($package_itinerary as $key => $value) { ?>

                                            <h4><b>Day <?php echo $value->day;?></b></h4>
                                            <h4><b><?php echo $value->place;?></b></h4>
                                            <p> <?php echo $value->itinerary_description; ?></p>
                                            <?php
                                                if ($GLOBALS['CI']->template->domain_upload_pckg_images(basename($value->itinerary_image) !== '')) {
                                                    ?>
                                                    <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($value->itinerary_image)); ?>" width="50%">;
                                            <?php    }
                                            ?>
                                            
                                            
                                    <?php    }
                                    ?>
                                </div>
                                <div>
                                    <h3><b>Terms & Conditions</b></h3>
                                    <h4><b>Price Includes: </b></h4>
                                    <P><?php echo isset($package_price_policy->price_includes)?($package_price_policy->price_includes):"No Description"; ?></P>
                                    <h4><b>Price Excludes: </b></h4>
                                    <P><?php echo isset($package_price_policy->price_excludes)?($package_price_policy->price_excludes):"No Description"; ?></P>
                                    <h4><b>Cancellation Advance: </b></h4>
                                    <P><?php echo isset($package_cancel_policy->cancellation_advance)?($package_cancel_policy->cancellation_advance):""; ?></P>
                                    <h4><b>Cancellation penality: </b></h4>
                                    <P><?php echo isset($package_cancel_policy->cancellation_penality)?($package_cancel_policy->cancellation_penality):""; ?></P>
                                </div>
                                <div>
                                    <h3><b>Package Traveller Photos</b></h3>
                                    <?php if(!empty($package_traveller_photos)){ ?>
                                    <?php foreach($package_traveller_photos as $ptp){ ?>
                                        <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($ptp->traveller_image); ?>" alt="" width="50%" height="50%"/><br/><br/>
                                  <?php } ?>
                                <?php } ?>
                                </div>
                                <div>
                                    <h3><b>Rating</b></h3>
                                    <p><strong><?php echo $package->rating; ?></strong><b> User Rating</b></p>
                                </div>
            </div>
        </div>
    </div>
</body>
</html>