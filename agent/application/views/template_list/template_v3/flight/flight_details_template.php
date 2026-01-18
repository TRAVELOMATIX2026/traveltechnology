<?php 
$SegmentDetails = $flight_details['SegmentDetails']; 
//$itinerary_details = $SegmentDetails[0];
$FareDetails = $flight_details['FareDetails']['b2b_PriceDetails']; 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Flight Quotation</title>
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
        .flight-details, .payment-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .flight-details td, .payment-details td {
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

<div class="section-title">Flight Quotation</div>
<p style="font-size:10pt;">*Please verify flight times with the airlines prior to departure</p>

<?php
    $cur_IsRefundable = $flight_details['Attr']['IsRefundable'];
    $Refundable_lab = ($cur_IsRefundable == false ? 'Non-Refundable' : 'Refundable');
    ?>
<div class="card">

    <?php 

    if (!empty($flight_details['SegmentDetails']) && ($flight_details['SegmentSummary'])) 
    {
    foreach ($flight_details['SegmentSummary'] as $key => $flight_value) 
    {
      ?>

    <strong>
    <img style="width: 30px;" src="<?= SYSTEM_IMAGE_DIR . 'airline_logo/' . $flight_value['AirlineDetails']['AirlineCode'] . '.svg' ?>" /> <?= $flight_value['AirlineDetails']['AirlineCode']."-". $flight_value['AirlineDetails']['FlightNumber']?></strong>

    <?php if(count($flight_details['SegmentSummary']) == 2 && $key == 1){?>
    <p style="text-align:right; font-weight:bold;">Return Flight</p>
    <?php }else{ ?>
      <p style="text-align:right; font-weight:bold;">Onward Flight</p>
    <?php } ?>

    <table class="flight-details">

        <?php
                foreach($flight_details['SegmentDetails'][$key] as $details_key => $value) 
                {
                    ?>
        <tr>
            <td>
                <strong>Departing</strong><br>
                <?= @$value['OriginDetails']['AirportName'] ?> (<?= @$value['OriginDetails']['AirportCode'] ?>)<?php echo empty(@$value['OriginDetails']['Terminal'])==false?", T".$value['OriginDetails']['Terminal']:"";?><br>
                <?= date("D, d M Y", strtotime($value['OriginDetails']['_Date']))?><br>
                <?= date("h:i A", strtotime($value['OriginDetails']['_DateTime']))?>
            </td>
            <td>
                <strong>Duration</strong><br>
                <?= $value['SegmentDuration'] ?><br>
                <?= @$value['AirlineDetails']['AirlineName'] ?>
            </td>
            <td>
                <strong>Arriving</strong><br>
                <?= @$value['DestinationDetails']['AirportName'] ?> (<?= @$value['DestinationDetails']['AirportCode'] ?>)<?php echo empty(@$value['DestinationDetails']['Terminal'])==false?", T".$value['OriginDetails']['Terminal']:""?><br>
                <?= date("D, d M Y", strtotime($value['DestinationDetails']['_Date']))?><br>
                <?= date("h:i A", strtotime($value['DestinationDetails']['_DateTime']))?>
            </td>

            <td>
                <strong><?= $flight_value['TotalStops']?> Stop(s)</strong><br>
                <?= $Refundable_lab ?>
            </td>
        </tr>

         <?php if (isset($value['WaitingTime'])) { ?>
                <tr>
                    <td colspan="4" style="text-align:center; font-style:italic; font-weight:bold;">
                        -------- Layover <?= @$value['WaitingTime'] ?> --------
                    </td>
                </tr>
            <?php } ?>
        
    <?php 
        
        }
        ?>
    </table>

<?php } 
    }
?>

</div>

<div class="section-title">Payment Summary</div>
<table class="payment-details">
    <tr>
        <td>Air Fare</td>
        <td align="right"><?= $FareDetails['CurrencySymbol'] ?><?= number_format($FareDetails['BaseFare'], 2) ?></td>
    </tr>
    <tr>
        <td>Taxes & Fees</td>
        <td align="right"><?= $FareDetails['CurrencySymbol'] ?><?= number_format($FareDetails['TotalTax'], 2) ?></td>
    </tr>
    <tr>
        <td class="total">Grand Total</td>
        <td class="total" align="right"><?= $FareDetails['CurrencySymbol'] ?><?= number_format((@$FareDetails['_TotalPayable']), 2) ?></td>
    </tr>
</table>

<div class="section-title">Flight Inclusions</div>
<ul style="font-size:10pt;">
    <?php if (!empty($flight_details['SegmentDetails'])) {
          foreach ($flight_details['SegmentDetails'] as $key => $flight_value) { 
          foreach ($flight_value as $key => $value) { ?>
    <li><strong>Check-in Baggage:</strong> <?= $value['OriginDetails']['AirportCode'] ?> to <?= $value['DestinationDetails']['AirportCode'] ?> (<?= $value['Baggage'] ?? 'Allowed as per the Airline Policy' ?>)</li>
    <li><strong>Cabin Baggage:</strong> <?= $value['OriginDetails']['AirportCode'] ?> to <?= $value['DestinationDetails']['AirportCode'] ?> (<?= $value['CabinBaggage'] ?? 'Allowed as per the Airline Policy' ?>)</li>
    <?php } } } ?>
</ul>
<p style="font-size:10pt; color: #1e40af;">* Flight inclusions are subject to change with Airlines.</p>

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

