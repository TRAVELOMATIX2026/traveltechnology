<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Activities Quotation</title>
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

<div class="section-title">Activity Details</div>
<div class="card">
    <table class="car-details">
        <tr><td>
        <?php if(empty($sightseeing_details['ImageUrl']) ==false)
                   {
                   ?>
               <img style="width:200px; height:130px; object-fit:cover;" src="<?= $sightseeing_details['ImageUrl'];?>" />
                 <?php 
                    }else{?>
                    <img style="width:200px; height:130px; object-fit:cover;" src="<?=$GLOBALS['CI']->template->template_images('no_image_available.jpg');?>" />
                 <?php };?>
        </td>
        <td> <strong><?= $sightseeing_details['ProductName']?></strong>
        </br>
                    <?= $sightseeing_details['DestinationName']?>
        </td>
        </tr>

        <tr><td><strong>Supplier:</strong> <?= $sightseeing_details['Supplier_Code']?></td>
            <td><strong>Supplier Phone No:</strong> N/A</td>
        </tr>
        <tr><td><strong>Duration:</strong> <?= $sightseeing_details['Duration']?></td>
            <td><strong>Cancellation Available:</strong> <?= $sightseeing_details['Cancellation_available']==1?'Yes':'No'?></td>
        </tr>

    </table>
</div>
<?php if(isset($sightseeing_details['Recommended_For_Ids']) && count($sightseeing_details['Recommended_For_Ids'])>0)
{?>
<div class="section-title">Recommended For</div>
<div class="card">
    <ul>
        <?php foreach($sightseeing_details['Recommended_For_Ids'] as $key=>$Ids){
        echo '<li>'.$Ids.'</li>';
        }
        ?>
    </ul>
</div>

<?php } ?>

<?php if(isset($sightseeing_details['Description'])){?>
<div class="section-title">Activity Inclusions</div>
<div class="card">
   <?= $sightseeing_details['Description']?>
</div>

<?php } ?>

<div class="section-title">Payment Details</div>
<table class="payment-details">
    <tr>
        <td>Base Fare</td>
        <td align="right"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?><?= number_format($sightseeing_details['Price']['TotalDisplayFare'], 2) ?></td>
    </tr>
    <tr>
        <td class="total">Grand Total</td>
        <td class="total" align="right"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?><?= number_format((@$sightseeing_details['Price']['TotalDisplayFare']), 2) ?></td>
    </tr>
</table>

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
