<?php 
    
    $search_data = json_decode($hotel_details['search_data'],true);

    //debug($search_data); exit();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hotel Quotation</title>
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


<div class="section-title">Hotel Quotation</div>

<?php
    $Refundable_lab = ($hotel_details['IsRefundable'] == false ? 'Non-Refundable' : 'Refundable');
    ?>
<div class="card">
    <strong>
Hotel - <?= $Refundable_lab ?></strong>
    <table class="car-details">
    <tr>
        <td>
             <?php if(empty($hotel_details['HotelPicture']) ==false)
                   {
                   ?>
               <img style="width:200px; height:130px; object-fit:cover;" src="<?=$hotel_details['HotelPicture'];?>" />
                 <?php 
                    }else{?>
                    <img style="width:200px; height:130px; object-fit:cover;" src="<?=$GLOBALS['CI']->template->template_images("default_hotel_img.jpg");?>" />
                 <?php };?>
        </td>
        <td style="padding-left: 15px; vertical-align: top;">
            <strong style="font-size: 18px;"><?=$hotel_details['HotelName']?></strong><br />
            <?=$hotel_details['HotelLocation']?><br />
            <?php
                $stars = intval($hotel_details['StarRating']); 
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $stars) {
                        echo '<span style="color: gold; font-size: 16px;">&#9733;</span>'; 
                    } else {
                        echo '<span style="color: lightgray; font-size: 16px;">&#9733;</span>';
                    }
                }
                ?>
            <br /><br />
            <strong>Check In:</strong> <?= date("D, d M Y", strtotime($search_data['from_date']))?><br />
            <strong>Check Out:</strong> <?= date("D, d M Y", strtotime($search_data['to_date']))?><br />
            <strong><?=$hotel_details['no_of_nights']?> Nights</strong><br /><br />
            <strong>Guests:</strong> <?= $hotel_details['room_count']?> Room(s), <?= array_sum($search_data['adult_config'])?> Adult(s) <?php echo array_sum($search_data['child_config'])>0? ", ".array_sum($search_data['child_config'])."Child(s)":"";?> 
        </td>
    </tr>
    <tr><td colspan="2" style="padding-top: 20px;"><hr></td></tr>
    <tr>
        <td colspan="2">
            <strong>No. of Rooms:</strong> <?=$hotel_details['room_count']?> Room(s)<br />
            <strong>Room Type:</strong> <?=$hotel_details['room_type']?><br />
            <strong>Room Inclusions:</strong> <?=$hotel_details['Inclusion']?><br />
            <em style="font-size: 12px; color: gray;">* Room inclusions are subject to change with Hotels.</em>
        </td>
    </tr>
    </table>
</div>

<div class="section-title">Payment Summary</div>
<table class="payment-details">
    <tr>
        <td>Room Price</td>
        <td align="right"><?= $hotel_details['currency_symbol'] ?><?= number_format($hotel_details['Price']['RoomBasePrice'], 2) ?></td>
    </tr>
    <tr>
        <td>Taxes & Fees</td>
        <td align="right"><?= $hotel_details['currency_symbol'] ?><?= number_format($hotel_details['Price']['Tax'], 2) ?></td>
    </tr>
    <tr>
        <td class="total">Grand Total</td>
        <td class="total" align="right"><?= $hotel_details['currency_symbol'] ?><?= number_format((@$hotel_details['Price']['PublishedPrice']), 2) ?></td>
    </tr>
</table>

<div class="note">
    <strong>IMPORTANT NOTE</strong><br>
    The details provided above are intended for reference only and are not yet confirmed.
    Rates may change upon booking confirmation.
</div>

<div class="contact">
    <strong>Customer Contact Details</strong><br>
    E-mail: <?= $this->entity_email ?> <br>
    Contact No: +<?= $this->entity_country_code ?> <?= $this->entity_phone ?>
</div>
</body>
</html>

