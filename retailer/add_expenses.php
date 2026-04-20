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
                                <h3 class="header">Add Expenses Details.</h3>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Expense Title <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" name="expense_title" id="expense_title" required="required">
                                                        <option value="">--select--</option>
                                                        <option value="FREIGHT CHARGES">FREIGHT CHARGES</option>
                                                        <option value="FREIGHT CHARGES (STORE TO STORE)">FREIGHT CHARGES (STORE TO STORE)</option>
                                                        <option value="LOADING CHARGES">LOADING CHARGES</option>
                                                        <option value="UNLOADING CHARGES">UNLOADING CHARGES</option>
                                                        <option value="STORE MISC">STORE MISC</option>
                                                        <option value="REPAIR AND MAINTENANCE">REPAIR AND MAINTENANCE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Expense Date <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <input class="form-control date-picker" id="" required="required" name="expense_date"  type="text" value="<?php
                                                    if (isset($_POST['expense_date'])) {
                                                        echo $_POST['expense_date'];
                                                    } else {
                                                        echo date('d-m-Y');
                                                    }
                                                    ?>" data-date-format="dd-mm-yyyy" />
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Total Expense Amount <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="amount" id="amount" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Expense Slip <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <input type="file" class="form-control" name="expense_slip" id="expense_slip" required="required" >
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Remarks : </label>
                                                <div class="col-sm-4">
                                                    <textarea class="form-control" name="expense_remarks" ></textarea>
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

                                <h3 class="header">Expenses Details Report.</h3>
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
                                    $expense_title = $_POST['expense_title'];
                                    $expense_date = date("Y-m-d", strtotime($_POST['expense_date']));
                                    $expense_remarks = $_POST['expense_remarks'];
                                    $trans_slip = "";
                                    if (isset($_FILES['expense_slip']['name'])) {
                                        $trans_slip = $_FILES['expense_slip']['name'];
                                    } else {
                                        echo "<script>alert('Item Already Added');window.location='add_expenses.php?menu=29';</script>";
                                        exit;
                                    }
                                    $target_dir = "expense_slip/";
                                    $target_file = $target_dir . basename($_FILES["expense_slip"]["name"]);
                                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
                                    $check = getimagesize($_FILES["expense_slip"]["tmp_name"]);
                                    if ($check == false) {
                                        echo "<script>alert('File is not an image.');window.location='add_expenses.php?menu=29';</script>";
                                        exit;
                                    }

// Check if file already exists
                                    if (file_exists($target_file)) {
                                        echo "<script>alert('Sorry, file already exists.');window.location='add_expenses.php?menu=29';</script>";
                                        exit;
                                    }

// Check file size
                                    if ($_FILES["expense_slip"]["size"] > 1000000) {
                                        echo "<script>alert('Sorry, your file is too large.');window.location='add_expenses.php?menu=29';</script>";
                                        exit;
                                    }

// Allow certain file formats
                                    if ($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "JPEG" && $imageFileType != "gif") {
                                        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');window.location='add_expenses.php?menu=29';</script>";
                                        exit;
                                    }
                                    if (move_uploaded_file($_FILES["expense_slip"]["tmp_name"], $target_file)) {
                                        
                                    } else {
                                        echo "<script>alert('Sorry, there was an error uploading your file.');window.location='add_expenses.php?menu=29';</script>";
                                        exit;
                                    }


                                    $insd = array();
                                    $insd['retailer_id'] = $_SESSION['id'];
                                    $insd['company_id'] = getRetailerCompanyIdById($_SESSION['id']);
                                    $insd['expense_title'] = $expense_title;
                                    $insd['amount'] = $amount;
                                    $insd['transaction_date'] = $expense_date;
                                    $insd['slip'] = $trans_slip;
                                    $insd['store_remarks'] = $expense_remarks;
                                    $insd['datetime'] = date('Y-m-d H:i:s');
                                    $ins = insert('expense_details', $insd);
                                    if ($ins) {
                                        echo "<script>alert('Expense Added Successfully.');window.location='add_expenses.php?menu=29';</script>";
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
                                                            <th width="15%" align="left">Title</th>
                                                            <th width="15%" align="left">Expanse Amount</th>
                                                            <th width="15%" align="left">Date</th>
                                                            <th width="15%" align="left">Slip</th>
                                                            <th width="25%" align="left">Uploaded Date</th>
                                                            <th width="25%" align="left">Status</th>
                                                            <th width="25%" align="left">Store Remarks</th>
                                                            <th width="25%" align="left">Account Remarks</th>
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
                                                        foreach (getExpenseSlipDetails($_SESSION['id'], $date_1, $date_2, $selection) as $datatwo) {
                                                            if ($datatwo->status == 1) {
                                                                $statusmsg = "<b style='color:green;'>Approved by central office.</b>";
                                                            } else if ($datatwo->status == 2) {
                                                                $statusmsg = "<b style='color:red;'>Rejected by central office.</b>";
                                                            } else {
                                                                $statusmsg = "<b style='color:blue;'>pending for approval by central office.</b>";
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo ++$ind; ?></td>
                                                                <td><?php echo $datatwo->expense_title; ?></td>
                                                                <td><?php echo $datatwo->amount; ?></td>
                                                                <td><?php echo date("d M Y", strtotime($datatwo->transaction_date)); ?></td>
                                                                <td><a target="_blank" href="expense_slip/<?php echo $datatwo->slip; ?>">Download</a></td>
                                                                <td><?php echo date("d M Y H:i", strtotime($datatwo->datetime)); ?></td>
                                                                <td><?php echo $statusmsg; ?></td>
                                                                <td><?php echo $datatwo->store_remarks; ?></td>
                                                                <td><?php echo $datatwo->account_remarks; ?></td>
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