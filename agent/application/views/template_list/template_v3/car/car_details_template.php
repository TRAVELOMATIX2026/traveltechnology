<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Car Quotation</title>
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

<div class="section-title">Car Rental Details</div>
<div class="card">
    <table class="car-details">
        <tr><td>
        <?php if(empty($car_details['PictureURL']) ==false)
                   {
                   ?>
               <img style="width:200px; height:130px; object-fit:cover;" src="<?=$car_details['PictureURL'];?>" />
                 <?php 
                    }else{?>
                    <img style="width:200px; height:130px; object-fit:cover;" src="<?=$GLOBALS['CI']->template->template_images('no-img.jpg');?>" />
                 <?php };?>
        </td>
        <td> <?= $car_details['VehicleCategoryName']?></td>
        </tr>

        <tr><td><strong>Car Name:</strong> <?= $car_details['Name']?></td>
            <td><strong>Supplier Name:</strong> <?= $car_details['Vendor']?></td>
        </tr>
        <tr><td><strong>Passengers:</strong> <?= $car_details['PassengerQuantity']?></td>
            <td><strong>Doors:</strong> <?= $car_details['DoorCount']?></td>
        </tr>
        <tr><td><strong>Bags:</strong> <?= $car_details['BaggageQuantity']?></td>
            <td><strong>Air Conditioning:</strong> <?= $car_details['AirConditionInd']==true?'Yes':'No'?></td>
        </tr>
        <tr><td><strong>Transmission:</strong> <?= $car_details['TransmissionType']?></td>

            <td><strong>Fuel Type:</strong> <?= $car_details['FuelType']?></td>

        </tr>
        <tr><td><strong>Pickup Date & Time:</strong> <?= date('d-m-Y H:i:a',strtotime($car_details['PickUpDateTime']))?></td>
            <td><strong>Drop Date & Time:</strong> <?= date('d-m-Y H:i:a',strtotime($car_details['ReturnDateTime']))?></td>
        </tr>

        <tr>
            <td><strong>Pickup Location:</strong> <?= isset($car_details['PickULocation'])?$car_details['PickULocation']:$car_details['DropOffLocation'] ?></td>

            <td><strong>Drop Location:</strong> <?= $car_details['DropOffLocation']?></td>

        </tr>
        <tr><td><strong>Pickup Hours:</strong> <?= date('H:i:a',strtotime($car_details['PickUpDateTime']))?></td>
            <td><strong>Drop Hours:</strong> <?= date('H:i:a',strtotime($car_details['ReturnDateTime']))?></td>
        </tr>

    </table>
</div>
<?php if(isset($car_details['PricedCoverage']))
{?>
<div class="section-title">Rental Price Includes</div>
<div class="card">
    <ul>
        <?php foreach($car_details['PricedCoverage'] as $PricedCoverage){
        echo '<li>'.$PricedCoverage['CoverageType'].' - '.$PricedCoverage['Desscription'].'</li>';
        }
        ?>
    </ul>
</div>

<?php } ?>

<?php if(isset($car_details['PaymentRules'])){?>
<div class="section-title">Payment Details</div>
<div class="card">
   <?= $car_details['PaymentRules']['PaymentRule']?>
</div>

<?php } ?>

<?php if($car_details['CancellationPolicy']){?>
<div class="section-title">Cancellation Policy</div>
<div class="card">
    <?= $car_details['CancellationPolicy']?>
</div>

<?php } ?>

<div class="section-title">Car Rental Total Fare</div>
<div class="card">
    <strong>Total Fare:</strong> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?><?= $car_details['TotalCharge']['EstimatedTotalAmount']?>
</div>

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
