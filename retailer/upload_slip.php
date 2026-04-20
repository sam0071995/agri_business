<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$get_bank_id = 0;
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>

    <body class="no-skin">
        <?php require_once 'includes/menu.php'; ?>
        <div class="main-container ace-save-state" id="main-container">
            <?php require_once 'includes/left_sidebar.php'; ?>
            <div class="main-content">
                <div class="main-content-inner">
                    <?php require_once 'includes/breadcrumbs.php'; ?>
                    <div class="page-content">
                        <?php require_once 'includes/page-header.php'; ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="header">Add Payment Transaction Details.</h3>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Mode<span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <select class="Retailer_id form-field-select-2 form-control" onchange="getSelectionDetails();" name="transfer_mode" id="transfer_mode" required="required">
                                                        <option value="">--Select Mode--</option>
                                                        <option value="1">BANK DEPOSIT</option>
                                                        <option value="2">CASH TRANSFER</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Bank/Retailer<span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <select class="Retailer_id form-field-select-2 form-control" name="bank_id" id="bank_id" required="required">
                                                        <option value="">--Select--</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Transaction Amount <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="amount" id="amount" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Remarks  <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <textarea class="form-control" name="transaction_remark" placeholder="Please Fill remark carefully. don`t paste `NA`" id="transaction_remark" required="required"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Deposit/Transfer Date <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <input class="form-control date-picker" id="" required="required" name="trans_date"  type="text" value="<?php
                                                    if (isset($_POST['trans_date'])) {
                                                        echo $_POST['trans_date'];
                                                    } else {
                                                        echo date('d-m-Y');
                                                    }
                                                    ?>" data-date-format="dd-mm-yyyy" />
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Transaction Slip : </label>
                                                <div class="col-sm-4">
                                                    <input type="file" class="form-control" name="trans_slip" id="trans_slip" />
                                                </div>
                                            </div>

                                            <div class="clearfix form-actions">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" name="submit" class="btn btn-info">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Add
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.row -->
                                <hr/>

                                <h3 class="header">Payment Transaction Details Report.</h3>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <form class="form-inline center" action="" method="POST">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-xs-14">
                                                        <b>From Date :</b>
                                                        <div class="input-group">
                                                            <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                            if (isset($_POST['date_1'])) {
                                                                echo $_POST['date_1'];
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar bigger-110"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-14">
                                                        <b>To Date :</b>
                                                        <div class="input-group">
                                                            <input class="form-control date-picker" id="" name="date_2"  type="text" value="<?php
                                                            if (isset($_POST['date_2'])) {
                                                                echo $_POST['date_2'];
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar bigger-110"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-14">
                                                        <div class="input-group">
                                                            <select class="form-control" name="selection">
                                                                <option value="0" <?php
                                                                if (isset($_POST['selection'])) {
                                                                    if ($_POST['selection'] == 0) {
                                                                        echo 'selected';
                                                                    }
                                                                }
                                                                ?>>Pending for approval by central office</option>
                                                                <option value="1" <?php
                                                                if (isset($_POST['selection'])) {
                                                                    if ($_POST['selection'] == 1) {
                                                                        echo 'selected';
                                                                    }
                                                                }
                                                                ?>>Approved by central office</option>
                                                                <option value="2" <?php
                                                                if (isset($_POST['selection'])) {
                                                                    if ($_POST['selection'] == 2) {
                                                                        echo 'selected';
                                                                    }
                                                                }
                                                                ?>>Rejected by central office</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-14">
                                                        <div class="input-group">
                                                            <input type="submit" class="btn btn-success" value="Filter" name="filter">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                                if (isset($_POST['submit'])) {
                                    $amount = $_POST['amount'];
                                    $transaction_remark = $_POST['transaction_remark'];
                                    if (empty($transaction_remark) && $transaction_remark = 'NA') {
                                        echo "<script>alert('Please Enter Valid remarks.');window.location='upload_slip.php?menu=29';</script>";
                                        exit;
                                    }
                                    $bank_id = $_POST['bank_id'];
                                    $transfer_mode = $_POST['transfer_mode'];
                                    $trans_date = date("Y-m-d", strtotime($_POST['trans_date']));
                                    $trans_slip = "";
                                    if (isset($_FILES['trans_slip']['name'])) {
                                        $trans_slip = $_FILES['trans_slip']['name'];
                                    } else {
                                        echo "<script>alert('Item Already Added');window.location='upload_slip.php?menu=29';</script>";
                                        exit;
                                    }
                                    if ($transfer_mode == 1 && $bank_id == 145) {
                                        $dupcheck = 0;
                                    } else {
                                        $dupcheck = 0;
//                                        $dupcheck = getCheckUploadedSlip($_SESSION['id'], $trans_date, $transfer_mode, $bank_id);
//                                        $dupcheck = count($dupcheck);
                                    }


                                    if ($dupcheck == 0) {
                                        $target_dir = "slip/";
                                        if (!empty($_FILES["trans_slip"]["name"])) {
                                            $target_file = $target_dir . basename($_FILES["trans_slip"]["name"]);
                                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
                                            $check = getimagesize($_FILES["trans_slip"]["tmp_name"]);
                                            if ($check == false) {
                                                echo "<script>alert('File is not an image.');window.location='upload_slip.php?menu=29';</script>";
                                                exit;
                                            }

// Check if file already exists
                                            if (file_exists($target_file)) {
                                                echo "<script>alert('Sorry, file already exists.');window.location='upload_slip.php?menu=29';</script>";
                                                exit;
                                            }

// Check file size
                                            if ($_FILES["trans_slip"]["size"] > 2097152) {
                                                echo "<script>alert('Sorry, your file is too large.');window.location='upload_slip.php?menu=29';</script>";
                                                exit;
                                            }

// Allow certain file formats
                                            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                                                echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');window.location='upload_slip.php?menu=29';</script>";
                                                exit;
                                            }
                                            if (move_uploaded_file($_FILES["trans_slip"]["tmp_name"], $target_file)) {
                                                
                                            } else {
                                                echo "<script>alert('Sorry, there was an error uploading your file.');window.location='upload_slip.php?menu=29';</script>";
                                                exit;
                                            }
                                        }
                                        $insd = array();
                                        $insd['retailer_id'] = $_SESSION['id'];
                                        $insd['company_id'] = getRetailerCompanyIdById($_SESSION['id']);
                                        $insd['bank_id'] = $bank_id;
                                        $insd['mode'] = $transfer_mode;
                                        $insd['transaction_remark'] = $transaction_remark;
                                        $insd['amount'] = $amount;
                                        $insd['transaction_date'] = $trans_date;
                                        $insd['slip'] = $trans_slip;
                                        $insd['datetime'] = date('Y-m-d H:i:s');
                                        $ins = insert('transaction_details', $insd);
                                        if ($ins) {
                                            echo "<script>alert('Transaction Added Successfully.');window.location='upload_slip.php?menu=29';</script>";
                                            exit;
                                        }
                                    } else {
                                        echo "<script>alert('Transaction Already Added for selected transaction date.');window.location='upload_slip.php?menu=29';</script>";
                                        exit;
                                    }
                                }
                                ?>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div>
                                                <table id="dynamic-table" class="table table-bordered table-hover">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th width="8%" align="left">#</th>
                                                            <th width="8%" align="left">Deposit Bank/Retailer Name</th>
                                                            <th width="15%" align="left">Transaction Amount</th>
                                                            <th width="15%" align="left">Transaction Date</th>
                                                            <th width="15%" align="left">Transaction No</th>
                                                            <th width="15%" align="left">Slip</th>
                                                            <th width="25%" align="left">Uploaded Date</th>
                                                            <th width="25%" align="left">Status</th>
                                                            <th width="25%" align="left">Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $date_1 = date("Y-m-d");
                                                        $date_2 = date("Y-m-d");
                                                        $selection = 0;
                                                        if (isset($_POST['selection'])) {
                                                            $selection = $_POST['selection'];
                                                            $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                            $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                        }
                                                        $ind = 0;
                                                        foreach (getTransationSlipDetails($_SESSION['id'], $date_1, $date_2, $selection) as $datatwo) {
                                                            if ($datatwo->status == 1) {
                                                                $statusmsg = "<b style='color:green;'>Approved by central office.</b>";
                                                            } else if ($datatwo->status == 2) {
                                                                $statusmsg = "<b style='color:red;'>Rejected by central office.</b>";
                                                            } else {
                                                                $statusmsg = "<b style='color:blue;'>pending for approval by central office.</b>";
                                                            }
                                                            ?>
                                                            <tr class="tr_table_<?php echo $datatwo->status; ?>">
                                                                <td><?php echo ++$ind; ?></td>
                                                                <td><?php
                                                                    if ($datatwo->mode == 1) {
                                                                        echo getBankNameById($datatwo->bank_id);
                                                                    } else if ($datatwo->mode == 2) {
                                                                        echo getRetailerNameById($datatwo->bank_id);
                                                                    } else {
                                                                        echo "NA";
                                                                    }
                                                                    ?></td>
                                                                <td><?php echo $datatwo->amount; ?></td>
                                                                <td><?php echo date("d M Y", strtotime($datatwo->transaction_date)); ?></td>
                                                                <td><?php
                                                                    if ($datatwo->transaction_no != 0) {
                                                                        echo $datatwo->transaction_no;
                                                                    } else {
                                                                        echo $datatwo->transaction_remark;
                                                                    }
                                                                    ?></td>
                                                                <td>
                                                                    <?php if (!empty($datatwo->slip)) { ?>
                                                                        <a target="_blank" href="slip/<?php echo $datatwo->slip; ?>">Download</a>
                                                                        <?php
                                                                    } else {
                                                                        echo 'NA';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?php echo date("d M Y H:i", strtotime($datatwo->datetime)); ?></td>
                                                                <td><?php echo $statusmsg; ?></td>
                                                                <td><?php echo $datatwo->remarks; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.row -->

                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">
                function getSelectionDetails() {
                    var transfer_mode = document.getElementById('transfer_mode').value;
                    if (transfer_mode != '') {
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $ajax_page; ?>',
                            data: {
                                transfer_mode: transfer_mode,
                                'request_type': 'get_bank_retailer_selection'
                            },
                            success: function (result) {
                                document.getElementById('bank_id').innerHTML = result;
                            }
                        });
                    }
                }

                function getRetailerItem() {
                    var retailer_id = document.getElementById('retailer_id').value;
                    // alert(retailer_id);
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            retailer_id: retailer_id,
                            'request_type': 'retailer_item_by_id'
                        },
                        success: function (result) {

                            document.getElementById('item_id').innerHTML = result;

                        }
                    });

                }

                function deletedata(id) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            id: id,
                            'request_type': 'delete_stock_trans_data'
                        },
                        success: function (result) {
                            if (result == 0) {
                                alert('Data Remove Successfully...');
                                window.location = window.location;
                            } else {
                                alert('Data Remove Error...');
                                window.location = window.location;
                            }
                        }
                    });
                }

                function confirmOrder(retailer_id) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            retailer_id: retailer_id,
                            'request_type': 'confirm_stock_tras_request'
                        },
                        success: function (result) {
                            if (result == 0) {
                                alert('Request Placed Successfully...');
                                window.location = window.location;
                            } else {
                                alert('Request Placed Error...');
                                window.location = window.location;
                            }
                        }
                    });
                }
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>