<style type="text/css">
    .discount-offer-wrapper {
        display: flex;
        flex-wrap: wrap;
        margin: 30px auto;
        justify-content: space-between;
    }
    .discount-offer-card {
        width: 100%;
        max-width: 600px;
        margin: 20px 0;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        background-color: #f9f9f9;
        overflow: hidden;
        font-family: 'Arial', sans-serif;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .discount-offer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    .offer-header {
        background-color: #ff6f61;
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 1.75rem;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .offer-body {
        display: flex;
        padding: 20px;
        align-items: center;
    }
    .offer-details {
        flex: 1;
        margin-right: 20px;
    }
    .offer-details p {
        margin: 8px 0;
        font-size: 14px;
        color: #333;
    }
    .offer-details p strong {
        color: #ff6f61;
    }
    .offer-image {
        flex-shrink: 0;
        width: 140px;
        height: 140px;
    }
    .offer-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .offer-footer {
        padding: 15px 20px;
        text-align: right;
        background-color: #fff;
        border-top: 1px solid #eee;
    }
    .status {
        font-weight: bold;
        font-size: 1.1rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
    .status.active {
        background-color: #4CAF50;
        color: white;
    }
    .status.inactive {
        background-color: #f44336;
        color: white;
    }

    .tracker-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .tracker-table th, .tracker-table td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .tracker-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    .no-offer {
        width: 100%;
        max-width: 600px;
        margin: 30px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
        text-align: center;
        font-family: 'Arial', sans-serif;
        color: #666;
        font-size: 1.25rem;
        font-weight: bold;
    }

    .no-offer:before {
        content: "⚠️";
        display: block;
        font-size: 2rem;
        margin-bottom: 10px;
        color: #f44336;
    }
</style>

<div class="container">
    <div class="discount-offer-wrapper">
        <div class="col-sm-6">
            <?php if($promo['status'] == true): ?>
                <div class="discount-offer-card">
                    <div class="offer-header">
                        Special Discount Offer
                    </div>

                    <div class="offer-body">
                        <div class="offer-details">
                            <p><strong>Promo Code:</strong>
                                <?= $promo['promo_code']; ?>
                            </p>
                            <p><strong>Description:</strong>
                                <?= $promo['description']; ?>
                            </p>
                            <p><strong>Discount Value:</strong>
                                <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
                                    <?=isset($promo['value'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($promo['value'])):0 . ' ' . ($promo['value_type'] == 'plus' ? 'Off' : '% Off'); ?>
                            </p>
                            <p><strong>Minimum Purchase Amount:</strong>
                                <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
                                    <?php echo isset($promo['minimum_amount'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($promo['minimum_amount']) ):0; ?>
                            </p>
                            <p><strong>Total Value:</strong>
                                <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
                                    <?php echo isset($promo['promo_code_total_value'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($promo['promo_code_total_value']) ):0; ?>
                            </p>
                            <p><strong>Remaining Value:</strong>
                                <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
                                    <?php echo isset($promo['promo_code_remaining_value'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($promo['promo_code_remaining_value']) ):0; ?>
                            </p>
                            <p><strong>Expiry Date:</strong>
                                <?= date('d M Y', strtotime($promo['promo_code_expiry_date'])); ?>
                            </p>
                        </div>

                        <div class="offer-image">
                            <img src="<?php echo base_url()?>extras/system/template_list/template_v1/images/promocode/<?= $promo['promo_code_image']; ?>" alt="<?= $promo['alt_text']; ?>">
                        </div>
                    </div>

                    <div class="offer-footer">
                        <span class="status <?= $promo['promo_code_remaining_value'] != 0 ? 'active' : 'inactive'; ?>">
                        <?= $promo['status'] == 1 ? 'Active' : 'Inactive'; ?>
                    </span>
                    </div>
                </div>
                <?php else: ?>
                    <div class="no-offer">
                        No discount coupon found.
                    </div>
                    <?php endif; ?>
        </div>

        <div class="col-sm-6">
            <table class="tracker-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Module</th>
                        <th>Used Value</th>
                        <th>Used At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    if (!empty($promocodeTrackerData)):
                        foreach ($promocodeTrackerData as $value): ?>
                        <tr>
                            <td>
                                <?= $i++; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($value['module']); ?>
                            </td>
                            <td>
                                <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
                                    <?php echo isset($value['promo_code_used_value'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($value['promo_code_used_value']) ):0; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($value['created_date_time']); ?>
                            </td>
                        </tr>
                        <?php endforeach; 
                    else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">No data found</td>
                            </tr>
                            <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>