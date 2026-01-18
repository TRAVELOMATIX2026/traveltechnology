<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Holiday Package Quotation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            background-color: #ffffff;
        }
        .header {
            background-color: #059669;
            color: white;
            padding: 10px;
        }
        .logo {
           font-size: 20pt;
            font-weight: bold;
        }
        .info {
            font-size: 10pt;
        }
        .section-title {
            background-color: #059669;
            color: white;
            padding: 8px;
            margin-top: 20px;
            font-weight: bold;
        }
        .card {
            border: 1px solid #bbf7d0;
            background-color: #f0fdf4;
            padding: 10px;
            margin-bottom: 15px;
        }
        .car-details, .payment-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .car-details td, .payment-details td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .price {
            font-size: 16pt;
            font-weight: bold;
            color: #059669;
        }
        .total {
            font-weight: bold;
            font-size: 14pt;
        }
        .note {
            background-color: #fffbeb;
            border: 1px solid #fed7aa;
            padding: 10px;
            font-size: 10pt;
            margin-top: 20px;
        }
        .contact {
            margin-top: 20px;
            font-size: 10pt;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 10px 5px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 12pt;
        }
        .btn-primary {
            background-color: #059669;
            color: white;
        }
        .btn-outline {
            border: 1px solid #059669;
            color: #059669;
            background-color: #f0fdf4;
        }
    </style>
</head>
<body>

<table width="100%" style="border-collapse: collapse;background-color: #059669;color: white;padding: 10px;">
    <tr>
        <td style="width: 60%; vertical-align: top;padding: 10px;">
            <div class="logo" style="font-weight: bold; font-size: 22px;"><?= $this->entity_name ?></div>

            <div class="info" style="font-size: 14px;margin-top: 10px;">
                Reference ID: <?= rand(1111,9999); ?>-<?= rand(111,999); ?>-<?= rand(111,999); ?><br>
                Agency: <?= $this->entity_phone ?>
            </div>
        </td>

        <td style="width: 40%; text-align: right;padding: 20px;">
            <img style="height: 70px;" src="<?= $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()) ?>" alt="Logo" />
        </td>
    </tr>
</table>

<div class="section-title">Package Details</div>
<div class="card">
    <table class="car-details">
        <tr><td>
        <?php if(empty($package_details['image']) ==false)
                   {
                   ?>
               <img style="width:200px; height:130px; object-fit:cover;" src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($package_details['image'])); ?>" />
                 <?php 
                    }else{?>
                    <img style="width:200px; height:130px; object-fit:cover;" src="<?=$GLOBALS['CI']->template->template_images('no_image_available.jpg');?>" />
                 <?php };?>
        </td>
        <td> <strong><?= $package_details['package_name']?></strong>
        </br>
                    <?= $package_details['package_location']?> | <?= $package_details['package_city']?>
                    <br>
                <?php
                $stars = intval($package_details['rating']); 
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $stars) {
                        echo '<span style="color: gold; font-size: 16px;">&#9733;</span>'; 
                    } else {
                        echo '<span style="color: lightgray; font-size: 16px;">&#9733;</span>';
                    }
                }
                ?>
            <br><br>
        </td>
        </tr>
    </table>
</div>

<?php if(isset($package_details['package_description'])){?>
<div class="section-title">Package Inclusions</div>
<div class="card">
   <?= $package_details['package_description']?>
</div>

<?php } ?>


<?php if(isset($package_itinerary))
{?>
<div class="section-title">Itinenary Details</div>
<div class="card">
    
    <?php $i=1; ?>
    
        <?php foreach($package_itinerary as $pi){ ?>
                <strong>Day-</strong>
                <b><?php echo $i; ?></b>
                <br>
            <strong><?php echo $pi->place; ?></strong><br>
           <?php echo $pi->itinerary_description; ?>
           <br>
               
        <?php $i++; ?>
        <?php } ?>

</div>

<?php } ?>

<?php if(isset($package_cancel_policy)){?>
<div class="section-title">Cancellation & Refund Policy</div>
<div class="card">
   <?= $package_cancel_policy->cancellation_advance?>
</div>

<?php } ?>

<?php if(isset($package_details['price'])){?>
<div class="section-title">Payment Details</div>
<div class="card">
   <strong><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?><?php echo isset($package_details['price'])? $package_details['price']:0; ?> Only</strong> *Per Person <br>
   <?php echo isset($package_details['duration'])?($package_details['duration']-1):0; ?> Nights / <?php echo isset($package_details['duration'])?$package_details['duration']:0; ?> Days
</div>

<?php } ?>

<div class="note">
    <strong>IMPORTANT NOTE</strong><br>
    The details provided above are intended for reference only and are not yet confirmed.
    Rates may change upon booking confirmation.
</div>

<div class="contact">
    <strong>Customer Contact Details</strong><br>
    E-mail: <?= $this->entity_email ?><br>
    Contact No: +<?= $this->entity_country_code ?> <?= $this->entity_phone ?>
</div>

</body>
</html>
