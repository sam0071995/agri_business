<!DOCTYPE html>
<html lang="en-US">
    <style type="text/css">
        label{
            color:#e1e1e1;
        }
        span{
            color:red;
            font-family: serif;
            font-size: 14px;
        }
    </style>
    <?php include "head.php"; ?>
    <body data-spy="scroll" data-target=".inner-link" data-offset="60">
        <?php
        $vahan_category_array = ['2WN', '2WT', '2WIC'];

        function numberToRomanRepresentation($roman) {
            $romans = array(
                'M' => 1000,
                'CM' => 900,
                'D' => 500,
                'CD' => 400,
                'C' => 100,
                'XC' => 90,
                'L' => 50,
                'XL' => 40,
                'X' => 10,
                'IX' => 9,
                'V' => 5,
                'IV' => 4,
                'I' => 1,
            );

            $result = 0;

            foreach ($romans as $key => $value) {
                while (strpos($roman, $key) === 0) {
                    $result += $value;
                    $roman = substr($roman, strlen($key));
                }
            }
            return $result;
        }

        $oem_id = $db_OEM->DecrPassMethod($db_OEM->removeDBkiss($_POST['oem_id']));
        $state_id_new = $db_OEM->DecrPassMethod($db_OEM->removeDBkiss($_POST['state_id']));
        $cus_pincode = $db_OEM->DecrPassMethod($db_OEM->removeDBkiss($_POST['cus_pincode']));
        $cus_pincode = $db_OEM->removeDBkiss($cus_pincode);
        // Validate the pincode
        if (!preg_match('/^\d{6}$/', $cus_pincode)) {
            echo "<script>alert('Invalid pincode. Please enter a 6-digit numeric pincode');"
            . "window.location.href='book_your_hsrp.php?error=5';"
            . "</script>";
            exit;
        }

        $dealer_id = $db_OEM->removeDBkiss($_POST['dealer_id']);
        $fitment_location = $db_OEM->DecrPassMethod($db_OEM->removeDBkiss($_POST['fitment_location']));
        if ($fitment_location == 2) {
            $pincode_data = $db_OEM->getEcDataByPincode($cus_pincode);
            $pincode_ec_id = $pincode_data->ec_id;
        } else {
            $pincode_ec_id = $db_OEM->getDealerEcByDealerId($db_OEM->removeDBkiss($_POST['dealer_id']));
        }
        $order_type = $db_OEM->DecrPassMethod($db_OEM->removeDBkiss($_POST['order_type']));
        $reg_no = strip_tags($db_OEM->DecrPassMethod($db_OEM->removeDBkiss($_POST['reg_no'])));



        $check_reg_no_order = $db_OEM->checkSlipOldNumberDataByRegNoApp_type($reg_no, $db_OEM->getAppTypeShortNameById($order_type));
        if (isset($check_reg_no_order->reg_no)) {
            echo "<script>alert('Order Alert: An order has already been placed for the entered vehicle details. Please print the HSRP Payment Slip or proceed with a new order under the `Damage Order` HSRP Type.');window.location.href='re_print.php';</script>";
            exit;
        }

        $city_name = $db_OEM->removeDBkiss($_POST['city_name']);
        $app_date = $db_OEM->removeDBkiss($_POST['app_date']);
        $app_slot = $db_OEM->removeDBkiss($_POST['app_slot']);
        $reg_no_2_char = substr($reg_no, 0, 2);
        if ($state_id_new == 11) {
            if ($reg_no_2_char == "GJ") {
                echo "<script>alert('Please enter only DD,DN registration numbers for Daman & Diu Locations..!');"
                . "window.location.href='book_your_hsrp.php?error=5';"
                . "</script>";
                exit;
            }
        }
        if ($state_id_new == 10) {
            if ($reg_no_2_char == "GJ") {
                echo "<script>alert('Please enter only DD,DN registration numbers for Daman & Diu Locations..!');"
                . "window.location.href='book_your_hsrp.php?error=5';"
                . "</script>";
                exit;
            }
        }


        $chassis_no = strip_tags($db_OEM->DecrPassMethod($db_OEM->removeDBkiss($_POST['chassis_no'])));
        $chassis_no_5 = substr($chassis_no, -5);
        $engine_no = strip_tags($db_OEM->DecrPassMethod($db_OEM->removeDBkiss($_POST['engine_no'])));
        $engine_no_5 = substr($engine_no, -5);

        $checkData = $db_OEM->checkSlipOldNumberDataByRegNoApp_type($reg_no, $order_type);
        if (isset($checkData->reg_no) && $order_type == 2) {
            echo "<script>alert('Order Already placed for entered vehicle details. kindly check your HSRP status.');window.location.href='tracking.php';</script>";
            exit;
        }


        $user_id = 'HSRPteamFTA';

        $data_array = array('regnNO' => $reg_no, 'chasisNo' => $chassis_no_5, 'userId' => $user_id, 'engineNo' => $engine_no_5);
        $json_data = json_encode($data_array);



//        $vahan_all_status = $db_OEM->getVahanDataFullDetails($json_data);
        $enc_vahan_string = $db_OEM->removeDBkiss($_POST['vahan_string']);

        $vh_al_details = json_decode($db_OEM->DecrPassMethod($enc_vahan_string));

        $vehicleDetails = $db_OEM->getVahanOem_id($vh_al_details->maker);
        $oem_listed_array = array();
        foreach ($vehicleDetails as $vehicleDetail) {
            $oem_listed_array[] = $vehicleDetail->oem_id;
        }
//        if (!in_array($oem_id, $oem_listed_array)) {
//            echo "<script>alert('Please Select correct manufacturer for your registered vehicle. As per Vahan Details your vehicler manufacturer is : " . $vh_al_details->maker . "');window.location.href='book_your_hsrp.php?error=6';</script>";
//            exit;
//        }

        $maker_name = trim(strtoupper(str_replace(' ', '', $vh_al_details->maker))); //HEROHONDAMOTORSLTD
        $vahan_vchType = $vh_al_details->vchType;
        $vahan_vchCatg = $vh_al_details->vchCatg;
        $stateCd = $vh_al_details->stateCd;
        $offCd = $vh_al_details->offCd;
        $allow_state_array = array('MH');

        if (empty($stateCd)) {
            echo "<script>alert('The Vahan is not responding. Kindly try again later.');window.location.href='book_your_hsrp.php?error=6';</script>";
            exit;
        }

        if (!in_array($stateCd, $allow_state_array)) {
            echo "<script>alert('We can not process your HSRP Order for State " . $stateCd . "');window.location.href='book_your_hsrp.php?error=6';</script>";
            exit;
        }
        if ($oem_id == 5 && $vahan_vchType == "Transport") {
            echo "<script>alert('Please select TATA MOTORS LTD - COM from Listed OEMs.');"
            . "window.location.href='book_your_hsrp.php?error=5';"
            . "</script>";
            exit;
        }

        if ($oem_id == 25 && $vahan_vchType == "Non-Transport") {
            echo "<script>alert('Please select TATA MOTORS LTD from Listed OEMs.');"
            . "window.location.href='book_your_hsrp.php?error=5';"
            . "</script>";
            exit;
        }

        if (empty($reg_no)) {
            echo "<script>window.location.href='book_your_hsrp.php?error=5';</script>";
            exit;
        }
        if (empty($chassis_no)) {
            echo "<script>window.location.href='book_your_hsrp.php?error=6';</script>";
            exit;
        }
        if (empty($engine_no)) {
            echo "<script>window.location.href='book_your_hsrp.php?error=7';</script>";
            exit;
        }

        $veh_type = $db_OEM->getOEMVehTypeByOemId($oem_id);
        $veh_type_ele = $db_OEM->getOEMTypeByOemId($oem_id);
        $all_holidays = $db_OEM->getHolidays();

        $block_dates = '';
        foreach ($all_holidays as $hdays) {
            $block_dates .= date('d.m.Y', strtotime($hdays->date)) . '_';
        }

        $block_dates = rtrim($block_dates, '_');
        $app_date_decode = $db_OEM->DecrPassMethod($app_date);
        $dealer_id_decode = $db_OEM->DecrPassMethod($dealer_id);
        $dealer_oem_id = $db_OEM->getDealerOemIdByDealerId($dealer_id_decode);
        $norms = $vh_al_details->norms;
        $norms_array = explode(" ", $norms);
        $engine_type_romen = numberToRomanRepresentation($norms_array[2]);

        if (date("Y-m-d", strtotime($vh_al_details->regnDate)) > '2019-04-01') {
            echo "<script>alert('Registration Date should be before 1st April 2019.');"
            . "window.location.href='book_your_hsrp.php?error=5';"
            . "</script>";
            exit;
        }
        if (date("Y-m-d", strtotime($vh_al_details->regnDate)) > '2019-04-01') {
            echo "<script>alert('Registration Date should be before 1st April 2019.');"
            . "window.location.href='book_your_hsrp.php?error=5';"
            . "</script>";
            exit;
        }
        if (empty($vh_al_details->regnDate)) {
            echo "<script>alert('Registration Date should be before 1st April 2019.');"
            . "window.location.href='book_your_hsrp.php?error=5';"
            . "</script>";
            exit;
        }

        // if($reg_no == 'MH41D3199'){
        //     echo "test hii";
        //     exit;
        // }
        ?>

        <main>
            <div class="loading" id="preloader">
                <div class="h-100 d-flex align-items-center justify-content-center">
                    <div class="loader-box">
                        <div class="loader">
                        </div>
                    </div>
                </div>
            </div>

            <?php include "menu.php"; ?>

            <section class="py-9 overflow-hidden" style="padding-top: 7rem!important;position: unset;overflow: auto;overflow: auto;position: unset;">
                <div class="background-holder overlay overlay-1 parallax" style="background-image:url(assets/images/background-car.jpg);
                     height: 100%;"></div><!--/.background-holder-->
                <div class="container">
                    <div class="row" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                        <div class="col text-center">
                            <div class="overflow-hidden">
                                <h1 class="fs-2 fs-sm-6 color-white mb-1" data-zanim='{"delay":0}'> </h1>
                            </div>
                            <div class="overflow-hidden" style="margin-bottom: 2rem">
                                <p class="fs-2 fw-300 ls color-8 text-uppercase" data-zanim='{"delay":0.1}'>Enter Your Vehicle Details</p>
                            </div>
                        </div>
                    </div><!--/.row-->
                </div><!--/.container-->

                <div class="container">
                    <div class="">
                        <div class="col-lg-12">
                            <?php
                            if ($_GET['error']) {
                                if ($_GET['error'] == '8') {
                                    $er_msg = 'Please Select Vehicle Type';
                                }
                                if ($_GET['error'] == '9') {
                                    $er_msg = 'Please Select Vehicle Class';
                                }
                                if ($_GET['error'] == '10') {
                                    $er_msg = 'Please Select Fuel Type';
                                }
                                if ($_GET['error'] == '11') {
                                    $er_msg = 'Please Enter Your Name';
                                }
                                if ($_GET['error'] == '12') {
                                    $er_msg = 'Please Enter Your Address';
                                }
                                if ($_GET['error'] == '13') {
                                    $er_msg = 'Please Enter Your Pincode';
                                }
                                ?>
                                <div class="form-group" style="color:red;font-weight: bold;">
                                    <label>Error :&nbsp;&nbsp;</label><label><?php echo $er_msg; ?></label>
                                </div>
                            <?php } ?>
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <div class="">
                                    <form method="POST" enctype="multipart/form-data" action="data_verify_book_hsrp.php">
                                        <input type="hidden" name="vahan_string" class="vahan_string" id="vahan_string" value="<?php echo $enc_vahan_string; ?>" />
                                        <input type="hidden" name="oem_id" class="oem_id" id="oem_id" value="<?php echo base64_encode($oem_id) ?>" />
                                        <input type="hidden" name="state_id" class="state_id" id="state_id" value="<?php echo base64_encode($state_id_new); ?>" />
                                        <input type="hidden" name="fitment_location" class="fitment_location" id="fitment_location" value="<?php echo base64_encode($fitment_location); ?>" />
                                        <input type="hidden" name="order_type" class="order_type" id="order_type" value="<?php echo base64_encode($order_type); ?>" />
                                        <input type="hidden" name="cus_pincode" value="<?php echo base64_encode($cus_pincode); ?>" />
                                        <input type="hidden" name="reg_no" value="<?php echo base64_encode($reg_no); ?>" />
                                        <input type="hidden" name="chassis_no" value="<?php echo base64_encode($chassis_no); ?>" />
                                        <input type="hidden" name="engine_no" value="<?php echo base64_encode($engine_no); ?>" />
                                        <input type="hidden" name="city_name" value="<?php echo base64_encode($_POST['city_name']); ?>" />
                                        <input type="hidden" name="dealer_id" value="<?php echo base64_encode($_POST['dealer_id']); ?>" />
                                        <input type="hidden" name="app_date" value="<?php echo base64_encode($_POST['app_date']); ?>" />
                                        <input type="hidden" name="app_slot" value="<?php echo base64_encode($_POST['app_slot']); ?>" />
                                        <input type="hidden" name="state_id_new" value="<?php echo base64_encode($state_id_new); ?>" />
                                        <input type="hidden" name="vahan_vchType" value="<?php echo base64_encode($vahan_vchType); ?>" />
                                        <input type="hidden" name="vahan_vchCatg" value="<?php echo base64_encode($vahan_vchCatg); ?>" />
                                        <input type="hidden" name="state_code_from_vahan" id="state_code_from_vahan" value="<?php echo base64_encode($stateCd); ?>" />
                                        <input type="hidden" name="offCd_from_vahan" id="offCd_from_vahan" value="<?php echo base64_encode($offCd); ?>" />
                                        <input type="hidden" name="slot_ec_id" id="slot_ec_id" value="<?php echo base64_encode($pincode_ec_id); ?>" />
                                        <input type="hidden" name="mobile_no" id="mobile_no" value="<?php echo $_POST['mobile_no']; ?>" />
                                        <input type="hidden" name="mobileNumberValidation" id="mobileNumberValidation" value="<?php echo $_POST['mobileNumberValidation']; ?>" />
                                        <div class="row align-items-center" > 
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style="margin-top: 0rem!important">
                                                <label><?php echo $db_OEM->getLabel("vehicle_registration_date", "en"); ?> / <?php echo $db_OEM->getLabel("vehicle_registration_date", "mr"); ?> </label>&nbsp;<span>*</span>
                                                <input type="hidden" value="<?php echo base64_encode($_POST['order_type']); ?>" name="app_type" id="app_type" />
                                                <input type="text" class="form-control" readonly="readonly" name="reg_date" id="reg_date" value="<?php echo date("Y-m-d", strtotime($vh_al_details->regnDate)); ?>"/>
                                                <span id="chassis-no-error-message" class="error">(Applicable for vehicles registered before April 1, 2019, only)</span>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style="margin-top: 0rem!important">
                                                <label><?php echo $db_OEM->getLabel("select_vehicle_type", "en"); ?> / <?php echo $db_OEM->getLabel("select_vehicle_type", "mr"); ?> </label>&nbsp;<span>*</span>
                                                <select class="form-control" name="vehicle_type" id="vehicle_type" required="">
                                                    <?php if (in_array($vh_al_details->vchCatg, $vahan_category_array)) { ?>
                                                        <option class="hidden" value="" selected disabled>Select</option>
                                                    <?php } ?>
                                                    <?php
                                                    $vahanvchCatg = $db_OEM->getVahanVehicleTypesCategory($vh_al_details->vchCatg);
                                                    $vahanvtids = $db_OEM->getVahanVehicleTypeiDS($vh_al_details->vchCatg);
                                                    $category_array = $db_OEM->getVehicleTypesMasterByVahanCatTypeIds($veh_type, $vahanvchCatg, $vahanvtids);
                                                    if (count($category_array) > 1) {
                                                        ?>
                                                        <option class="hidden" value="" selected disabled>Select</option>

                                                        <?php
                                                    }
                                                    foreach ($category_array as $ve_type) {
                                                        ?>
                                                        <option value="<?php echo $ve_type->vehicle_type_desc; ?>"><?php echo $ve_type->order_vehicle_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span id="chassis-no-error-message" class="error">(Please select your vehicle type as per the RC book records)</span>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style="margin-top: 1rem!important">
                                                <label><?php echo $db_OEM->getLabel("vehicle_class", "en"); ?> / <?php echo $db_OEM->getLabel("vehicle_class", "mr"); ?></label>&nbsp;<span>*</span>
                                                <input type="text" class="form-control" readonly="readonly" id="vehicle_class" name="vehicle_class" value="<?php echo $vh_al_details->vchType; ?>" >
                                                <input type="hidden" id="selected_class" value="<?php echo $vh_al_details->vchType; ?>" >
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style="margin-top: 1rem!important;">
                                                <label><?php echo $db_OEM->getLabel("fuel_type", "en"); ?> / <?php echo $db_OEM->getLabel("fuel_type", "mr"); ?></label>&nbsp;<span>*</span>
                                                <input type="text" class="form-control" readonly="readonly" id="fuel_type" name="fuel_type" value="<?php echo $vh_al_details->fuel; ?>" >
                                            </div>
                                            <?php if ($vh_al_details->norms == "Not Available") { ?>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style="margin-top: 1rem!important;">
                                                    <label><?php echo $db_OEM->getLabel("engine_type", "en"); ?> / <?php echo $db_OEM->getLabel("engine_type", "mr"); ?></label>&nbsp;<span>*</span>
                                                    <select class="form-control engine_type" name="engine_type" id="engine_type" >
                                                        <option value="">-- select --</option>
                                                        <?php foreach ($db_OEM->get_emission_norms() as $emission_norms) { ?>
                                                            <option value="<?php echo $emission_norms->short_name; ?>"><?php echo $emission_norms->description; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style="margin-top: 1rem!important;">
                                                    <label><?php echo $db_OEM->getLabel("engine_type", "en"); ?> / <?php echo $db_OEM->getLabel("engine_type", "mr"); ?></label>&nbsp;<span>*</span>
                                                    <input class="form-control" type="text" id="engine_type" name="engine_type" value="<?php echo $norms; ?>" readonly="readonly" />
                                                </div>
                                            <?php } ?>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style="margin-top: 1rem!important;">
                                                <label><?php echo $db_OEM->getLabel("registered_vehicle_owner_name", "en"); ?> / <?php echo $db_OEM->getLabel("registered_vehicle_owner_name", "mr"); ?></label>
                                                <input type="text" name="cus_name" id="cus_name" class="form-control" placeholder="Enter Your name" value="" autocomplete="off" />
                                            </div>
                                            <div class="col-lg-12">&nbsp;</div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-top: 1rem!important;">
                                                <label style="color: #ff0000;"><input type="checkbox" value="1" name="is_gst_invoice" id="is_gst_invoice" /> <?php echo $db_OEM->getLabel("is_required_gst_invoice", "en"); ?> / <?php echo $db_OEM->getLabel("is_required_gst_invoice", "mr"); ?></label>&nbsp;<span class="is_gst_span"></span>
                                                <input type="text" name="gstin_no" id="gstin_no" placeholder="Enter GSTIN Number" class="form-control gstin_no" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-top: 1rem!important;">
                                                <label><?php echo $db_OEM->getLabel("billing_state", "en"); ?> / <?php echo $db_OEM->getLabel("billing_state", "mr"); ?> : </label>&nbsp;<span>*</span>
                                                <select class="form-control cus_state" name="cus_state" id="cus_state" required="required">
                                                    <option class="hidden" value="" selected disabled>Please select State</option>
                                                    <?php foreach ($db_OEM->stateListStateId(4) as $StateData) { ?>
                                                        <option value="<?php echo $StateData->id; ?>"><?php echo $StateData->caps_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="submit_button" style="margin-top: 2rem!important;">
                                                <button type="submit"  class="btn btn-sm btn-danger" id="cus_deta" name="cus_deta" value="">Next</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.container-->
            </section>
            <?php include "footer.php"; ?>
            <?php include "script.php"; ?>
            <script>
                document.getElementById('is_gst_invoice').addEventListener('change', function () {
                    let gstinInput = document.getElementById('gstin_no');
                    if (this.checked) {
                        $("#gstin_no").val("");
                        gstinInput.removeAttribute('disabled');
                        gstinInput.setAttribute('required', 'required');
                        $(".is_gst_span").html('*');
                    } else {
                        $("#gstin_no").val("");
                        gstinInput.setAttribute('disabled', 'disabled');
                        gstinInput.removeAttribute('required');
                        $(".is_gst_span").html('');
                    }
                });
            </script>
            <script>
                function gst_num_verify() {
//                    var inputvalues = $('#cus_gstin').val();
//                    var gstinformat = new RegExp('^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$');
//                        alert(gstinformat.test(inputvalues));
//                    if (inputvalues.length != 15) {
//                        alert('Please Enter Valid 15 Digit GSTIN Number');
//                        $("#cus_gstin").val(inputvalues);
//                        $("#cus_gstin").focus();
//                    } else if (gstinformat.test(inputvalues)) {
//                        return true;
//                    } else {
//                        alert('Please Enter Valid GSTIN Number');
//                        $("#cus_gstin").val('');
//                        $("#cus_gstin").focus();
//                    }
//
//                    $.ajax({
//                        url: 'get_ajax_data.php',
//                        type: 'post',
//                        data: {
//                            request_type: 'check_gst_in_dealer_master',
//                            inputvalues: inputvalues
//                        },
//                        success: function (result) {
//                            if (result == 'notavailable') {
//                                return true;
//                            } else {
//                                alert('Please Enter Valid GSTIN Number');
//                                $("#cus_gstin").val('');
//                                $("#cus_gstin").focus();
//                            }
//                        }
//                    });


                }

// 
                $(document).ready(function () {
                    $('.gstin_no').on('change', function () {
                        let gstin_no = $(this).val().trim(); // Get the changed input field value

                        if ($('#is_gst_invoice').is(':checked')) { // Check if GST Invoice is selected
                            if (gstin_no === '') {
                                alert("GSTIN is required.");
                                $(this).focus();
                                return false;
                            }
                            let gstinPattern = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1}$/;
                            if (!gstinPattern.test(gstin_no)) {
                                alert("Please enter a valid GSTIN number or uncheck the 'Is GST Invoice' option.");
                                $(this).focus();
                                return false;
                            }

                            // Check if the first two characters are '27'
                            if (!gstin_no.startsWith('27')) {
                                alert("Please enter a GSTIN registered in the state of Maharashtra only.");
                                $(this).focus();
                                $(this).value('');
                                return false;
                            }
                        }
                    });


//                    $('.gstin_no').on('change', function () {
//                        let gstin_no = $("#gstin_no").val();
//                        if (document.getElementById('is_gst_invoice').checked) {
//                            if (gstin_no != '') {
//                                let gstin = gstin_no.trim();
//                                let gstinPattern = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1}$/;
//                                if (gstin === "") {
//                                    alert("GSTIN is required.");
//                                    return false;
//                                } else if (!gstinPattern.test(gstin)) {
//                                    alert("Please enter a valid GSTIN number or uncheck the `Is GST Invoice` option.");
//                                    return false;
//                                } else {
//                                    return true;
//                                }
//                            } else {
//                                alert("Please Enter GSTIN Number.");
//                            }
//                        }
//                    });
                    $("#cus_gstin").change(function () {
                        $(".loader").css("display", "block");
                        var cus_gstin = $(this).val();
                        if (cus_gstin != '') {
                            $(".customer_data").css("display", "flex");
                            $('#cus_address').prop('required', true);
                            $('#cus_state').prop('required', true);
                            $('#cus_pincode').prop('required', true);
                        } else {
                            $(".customer_data").css("display", "none");
                            $('#cus_address').prop('required', false);
                            $('#cus_state').prop('required', false);
                            $('#cus_pincode').prop('required', false);
                        }


                        var inputvalues = $('#cus_gstin').val();
                        var gstinformat = new RegExp('^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$');
//                        alert(gstinformat.test(inputvalues));
                        if (inputvalues.length != 15) {
                            alert('Please Enter Valid 15 Digit GSTIN Number');
                            $("#cus_gstin").val(inputvalues);
                            $("#cus_gstin").focus();
                            $(".customer_data").css("display", "none");
                            $('#cus_address').prop('required', false);
                            $('#cus_state').prop('required', false);
                            $('#cus_pincode').prop('required', false);
                        } else if (gstinformat.test(inputvalues)) {
                            return true;
                            $(".customer_data").css("display", "flex");
                            $('#cus_address').prop('required', true);
                            $('#cus_state').prop('required', true);
                            $('#cus_pincode').prop('required', true);
                        } else {
                            alert('Please Enter Valid GSTIN Number');
                            $("#cus_gstin").val('');
                            $("#cus_gstin").focus();
                            $(".customer_data").css("display", "none");
                            $('#cus_address').prop('required', false);
                            $('#cus_state').prop('required', false);
                            $('#cus_pincode').prop('required', false);
                        }

                        $.ajax({
                            url: 'get_ajax_data.php',
                            type: 'post',
                            data: {
                                request_type: 'check_gst_in_dealer_master',
                                inputvalues: inputvalues
                            },
                            success: function (result) {
                                if (result == 'notavailable') {
                                    return true;
                                } else {
                                    alert('Please Enter Valid GSTIN Number');
                                    $("#cus_gstin").val('');
                                    $("#cus_gstin").focus();
                                }
                            }
                        });


                    });

                    $("#vehicle_type").change(function () {
                        $(".loader").css("display", "block");
                        var vehicle_type = $(this).val();
                        var selected_class = $("#selected_class").val();
                        var state_code_from_vahan = $("#state_code_from_vahan").val();
                        // alert(oem_id);
                        $.ajax({
                            url: 'get_ajax_data.php',
                            type: 'post',
                            data: {
                                request_type: 'get_veh_class_by_type',
                                vehicle_type: vehicle_type,
                                selected_class: selected_class,
                                state_code_from_vahan: state_code_from_vahan
                            },
                            success: function (result) {
                                $(".loader").css("display", "none");
                                $('#vehicle_class').html(result);
                            }
                        });
                    });
                });
            </script>
        </main>
    </body>
</html>