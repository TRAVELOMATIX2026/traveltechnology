 <!-- Additional Information -->
    <!-- <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;"> -->
        <p style="margin: 0 0 15px 0; color: #333; font-size: 14px; line-height: 1.6;"><strong>Dear Valued Client,</strong></p>
        
        <p style="margin: 0 0 15px 0; color: #333; font-size: 14px; line-height: 1.6;">Please find attached the quotation details for your reference.</p>
        
        <p style="margin: 0 0 15px 0; color: #333; font-size: 14px; line-height: 1.6;">Kindly note that the information provided, including rates and itinerary or service details, is for reference purposes only. No bookings or reservations have been made or confirmed at this time.</p>
        
        <p style="margin: 0 0 15px 0; color: #333; font-size: 14px; line-height: 1.6;">Rates are subject to change without prior notice and are not guaranteed until the booking is finalized.</p>
        
        <p style="margin: 0 0 15px 0; color: #333; font-size: 14px; line-height: 1.6;">If you wish to proceed with the booking or have any questions, please feel free to contact me directly. I'll be happy to assist you further.</p>
        
        <p style="margin: 0; color: #333; font-size: 14px; line-height: 1.6;"><strong>Thank you for trusting us with your travel needs.</strong></p>
    <!-- </div> -->
<br>
<br>
<br>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice - <?php echo $merchant_name ?></title>
</head>
<body style="margin: 0; padding: 20px; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        
        <!-- Header -->
        <div style="background-color: #28a745; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Payment Invoice</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;"><?php echo $merchant_name ?></p>
        </div>

        <!-- Content -->
        <div style="padding: 30px;">
            
            <!-- Bill To and Invoice Details -->
            <div style="display: table; width: 100%; margin-bottom: 30px;">
                <div style="display: table-cell; vertical-align: top; width: 50%;">
                    <h3 style="color: #28a745; margin: 0 0 10px 0; font-size: 16px;">Bill To:</h3>
                    <p style="margin: 0; color: #666; font-size: 14px;"><?= $username ?></p>
                </div>
                <div style="display: table-cell; vertical-align: top; width: 50%; text-align: right;">
                    <h3 style="color: #28a745; margin: 0 0 10px 0; font-size: 16px;">Invoice Details:</h3>
                    <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;"><strong>Date:</strong> <?php echo $created ?></p>
                    <!-- <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;"><strong>Due Date:</strong> Jun 28, 2025, 03:39 PM</p> -->
                    <p style="margin: 0; color: #999; font-size: 12px;">Ref: <?= $app_refrence ?></p>
                </div>
            </div>

            <!-- Amount Due Box -->
            <div style="border: 2px solid #ffc107; border-radius: 8px; padding: 25px; text-align: center; margin-bottom: 30px; background-color: #fffbf0;">
                <h3 style="color: #ffc107; margin: 0 0 15px 0; font-size: 18px;">Amount Due</h3>
                <div style="color: #ff8c00; font-size: 36px; font-weight: bold; margin-bottom: 20px;"><?php echo $currency.' '.$totalamount; ?></div>
                <a href="<?php echo $link ?>" style="background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; font-size: 16px;">Pay Now</a>
            </div>

            <!-- Order Summary -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #333; margin: 0 0 20px 0; font-size: 18px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Order Summary</h3>
                
                <!-- Table Header -->
                <div style="background-color: #28a745; color: white; padding: 12px; display: table; width: 100%; box-sizing: border-box;">
                    <div style="display: table-cell; width: 50%; font-weight: bold; font-size: 14px;">Description</div>
                    <div style="display: table-cell; width: 25%; text-align: center; font-weight: bold; font-size: 14px;">Qty</div>
                    <div style="display: table-cell; width: 25%; text-align: right; font-weight: bold; font-size: 14px;">Amount</div>
                </div>
                
                <!-- Table Row -->
                <div style="padding: 12px; border-bottom: 1px solid #eee; display: table; width: 100%; box-sizing: border-box;">
                    <div style="display: table-cell; width: 50%; font-size: 14px; color: #333;">Top-up Payment</div>
                    <div style="display: table-cell; width: 25%; text-align: center; font-size: 14px; color: #333;">1</div>
                    <div style="display: table-cell; width: 25%; text-align: right; font-size: 14px; color: #333;"><?php echo $currency.' '.$totalamount; ?></div>
                </div>
                
                <!-- Subtotal -->
                <div style="padding: 8px 12px; display: table; width: 100%; box-sizing: border-box;">
                    <div style="display: table-cell; width: 75%; font-size: 14px; color: #666;">Subtotal:</div>
                    <div style="display: table-cell; width: 25%; text-align: right; font-size: 14px; color: #666;"><?php echo $currency.' '.$amount ?></div>
                </div>
                
                <!-- Merchant Fee -->
                <div style="padding: 8px 12px; display: table; width: 100%; box-sizing: border-box;">
                    <div style="display: table-cell; width: 75%; font-size: 14px; color: #666;">Merchant Fee:</div>
                    <div style="display: table-cell; width: 25%; text-align: right; font-size: 14px; color: #666;"><?= $topup_marchantfee ?></div>
                </div>
                
                <!-- Total -->
                <div style="padding: 12px; background-color: #f8f9fa; border-top: 2px solid #28a745; display: table; width: 100%; box-sizing: border-box;">
                    <div style="display: table-cell; width: 75%; font-size: 16px; font-weight: bold; color: #ff8c00;">Total Amount Due:</div>
                    <div style="display: table-cell; width: 25%; text-align: right; font-size: 16px; font-weight: bold; color: #ff8c00;"><?php echo $currency.' '.$totalamount; ?></div>
                </div>
            </div>


        </div>

        <!-- Footer -->
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;">
            <p style="margin: 0; color: #666; font-size: 12px;">© 2025 Starlegends Adventures Inc. All rights reserved.</p>
        </div>

    </div>
</body>
</html>
