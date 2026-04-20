<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
?>
<!DOCTYPE html><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kisan Offer Coupon - <?php echo getCompanyNameById($coupon_detail->company_id); ?></title>
        <style>
            /* General Page and Font Styles */
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* A clean sans-serif font like Roboto is also good */
                padding: 0;
                background-color: #f0f2f5; /* Light grey page background */
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;

                margin: 0 ;
                min-height: 0;
                page-break-after: auto;
                position: relative;
                width: 100%;
            }
            .mainbody {
            }
            /* simulated browser chrome */
            .browser-chrome {
                width: 100%;
                background-color: #f1f3f4;
                border-bottom: 1px solid #dcdcdc;
                padding: 5px 0;
                display: flex;
                align-items: center;
                font-size: 13px;
                color: #5f6368;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            }

            .browser-tab {
                margin-left: 0;
                display: flex;
                align-items: center;
                padding: 5px;
                background-color: #fff;
                border-radius: 8px 8px 0 0;
                box-shadow: 0 -1px 2px rgba(0,0,0,0.05);
                font-weight: bold;
            }

            .tab-icon {
                margin-right: 5px;
                color: #1a73e8;
            }



            /* Main Page Container */
            .page-container {
                width: 100%;
                max-width: 900px;
                margin: 0;
                padding: 0;
                background-color: #fff; /* White content area */
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            /* Coupon Card Styles */
            .coupon-card {
                width: 100%;
                border: 1px solid #d3d3d3;
                padding: 20px;
                box-sizing: border-box;
                background-color: #fff;
                position: relative;
            }

            .header-copy {
                font-size: 11px;
                color: #999;
                margin: 0;
            }

            .brand-name {
                font-size: 20px;
                font-weight: bold;
                color: #222;
                margin: 5px 0 0;
            }

            .offer-title {
                font-size: 14px;
                text-transform: uppercase;
                color: #222;
                margin: 5px 0 15px;
            }

            /* unique coupon code block */
            .coupon-code-block {
                background-color: #001a33; /* Dark navy background */
                color: #fff;
                padding: 10px 0;
                text-align: center;
                margin-bottom: 20px;
            }

            .coupon-code-heading {
                font-size: 12px;
                text-transform: uppercase;
                margin: 0;
                color: #ccc;
            }

            .coupon-code-value {
                font-size: 26px;
                font-weight: 700;
                letter-spacing: 2px;
                margin: 5px 0 0;
                font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Stronger font */
            }

            /* offer details */
            .offer-details {
                text-align: center;
                margin-bottom: 20px;
                color: #444;
            }

            .discount-off {
                font-size: 24px;
                font-weight: bold;
                color: #e63946; /* Bright red/orange */
                margin: 0;
            }

            .validity-text {
                font-size: 13px;
                margin: 5px 0;
            }

            .center-text {
                font-size: 13px;
                margin: 5px 0;
                font-weight: bold;
            }

            .terms-text {
                font-size: 12px;
                margin: 10px 0 0;
                color: #666;
            }

            /* QR Code Placeholder */
            .qr-code {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }
            .qr-code-pattern {
                width: 80px;
                height: 80px;
                background-image: 
                    linear-gradient(45deg, #000 25%, transparent 25%),
                    linear-gradient(-45deg, #000 25%, transparent 25%),
                    linear-gradient(45deg, transparent 75%, #000 75%),
                    linear-gradient(-45deg, transparent 75%, #000 75%);
                background-size: 10px 10px;
                background-position: 0 0, 0 5px, 5px -5px, -5px 0px;
                background-color: #fff;
                border: 1px solid #000;
            }

            /* Perforated lines and scissor icon */
            .dashed-line {
                width: 100%;
                height: 1px;
                border-top: 1px dashed #999;
                position: relative;
                margin: 20px 0;
            }

            .scissor-icon {
                position: absolute;
                right: 10px;
                top: -10px;
                color: #666;
                font-size: 14px;
            }

            /* Executive Copy Section */
            .executive-section {
                width: 100%;
                padding: 0 20px;
                box-sizing: border-box;
                color: #444;
            }

            .executive-header {
                font-size: 11px;
                color: #999;
                margin: 0 0 10px;
            }

            .form-fields {
                margin-bottom: 20px;
                display: inline-block;
                flex-direction: column;
                text-align: left;
            }

            .form-label {
                font-size: 13px;
                margin-bottom: 5px;
                display: inline-block;
            }

            .input-line {
                border: none;
                border-bottom: 1px dashed #999;
                font-size: 14px;
                padding: 5px 0;
                width: 50%;
                max-width: 900px;
                display: inline-block;
            }

            .checkbox-fields {
                display: flex;
                align-items: center;
                margin-bottom: 20px;
                font-size: 13px;
                text-align: center;
            }

            .checkbox-label {
                margin-right: 15px;
            }
            .checkbox-option {
                margin-right: 10px;
                display: flex;
                align-items: center;
                text-align: center;
            }
            .checkbox-sim {
                width: 14px;
                height: 14px;
                border: 1px solid #666;
                display: inline-block;
                margin-right: 5px;
                text-align: center;
            }

            .details-fields {
                display: flex;
                gap: 20px;
                font-size: 12px;
                margin-top: 10px;
            }
            .details-label {
                font-weight: bold;
            }

            /* Bottom copy start */
            .bottom-copy-container {
                margin-top: 0;
                padding: 0;
            }
            .bottom-brand {
                margin-top: 0;
            }
            @media print {
                * {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                body {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .width100 {
                    width: 100% !important;
                }

                tr {
                    width: 100% !important;
                }

                td {
                    /* font-weight: bold; */
                    font-size: 12px;
                }
            }
        </style>
    </head>
    <body>

        <?php
        $coupon_details = getAllCouponDataByCouponNumber($coupon_no);
        foreach ($coupon_details as $coupon_detail) {
            if (isset($coupon_detail->id)) {
                ?>
                <div class="page-container">
                    <div class="coupon-card">
                        <p class="header-copy">CUSTOMER COPY</p>
                        <h1 class="brand-name"><?php echo getCompanyUnitNameById($coupon_detail->company_id); ?></h1>
                        <h2 class="offer-title">KISAN OFFER</h2>

                        <div class="coupon-code-block">
                            <p class="coupon-code-heading">UNIQUE COUPON CODE</p>
                            <p class="coupon-code-value"><?php echo $coupon_detail->discount_code; ?></p>
                        </div>

                        <div class="offer-details">
                            <p class="discount-off"><?php echo $coupon_detail->price; ?> RS OFF</p>
                            <p class="validity-text">Valid until: <strong><?php echo date("d M Y", strtotime($coupon_detail->valid_till_date)); ?></strong></p>
                            <p class="validity-text">Issued: <?php echo date("d M Y H:i:s", strtotime($coupon_detail->coupon_generate_date)); ?></p>
                            <p class="center-text">Centre: <?php echo getRetailerNameById($coupon_detail->retailer_id); ?></p>
                            <p><span class="details-label">Seq:</span> <?php echo getCompanypPrefixById($coupon_detail->company_id) . '-' . FiveDigit($coupon_detail->id); ?></p>
                            <p class="terms-text">Not valid with other offers. One per customer.</p>
                        </div>

                        <div class="dashed-line">
                            <span class="scissor-icon"></span>
                        </div>

                        <div class="executive-section">
                            <p class="executive-header">Executive Copy</p>
                            <div class="form-fields">
                                <label class="form-label" for="farmer-name">Farmer Name:</label>
                                <input class="input-line" type="text" id="farmer-name" name="farmer-name">
                            </div>
                            <br/>
                            <br/>
                            <div class="form-fields">
                                <label class="form-label" for="mobile-no">Mobile No.:</label>
                                <input class="input-line" type="text" id="mobile-no" name="mobile-no">
                            </div>
                            <br/>
                            <br/>
                            <div class="checkbox-fields">
                                <span class="checkbox-label">WhatsApp (Y/N):</span>
                                <span class="checkbox-option"><span class="checkbox-sim"></span>[Y]</span>
                                <span class="checkbox-option"><span class="checkbox-sim"></span>[N]</span>
                            </div>
                            <br/>
                            <div class="details-fields">
                                <div><span class="details-label">Coupon No.:</span> <?php echo $coupon_detail->discount_code; ?></div>
                                <br/>
                                <div><span class="details-label">Seq:</span> <?php echo getCompanypPrefixById($coupon_detail->company_id) . '-' . FiveDigit($coupon_detail->id); ?></div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
            }
        }
        ?>
    </body>
</html>
