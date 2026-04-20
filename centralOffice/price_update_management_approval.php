<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
if (isset($_POST['show'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
    $app_flag_type = $_POST['app_flag_type'];
} else {
    $date_1 = date("Y-m-d");
    $app_flag_type =  0;
}
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
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Item can not be insert.";
                                            break;
                                        default:
                                            $msg = "Something Wrong.";
                                            break;
                                    }
                                    ?>
                                    <div class="alert alert-block alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check red form-error-msg"></i>
                                        <?php echo $msg; ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_GET['success'])) { ?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check green form-error-msg"></i>
                                        <?php echo "Product Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <hr/>
                                <a href="sales_management_approval.php?menu=437"><button class="btn btn-danger">Sales Data</button></a>
                                <a href="price_update_management_approval.php?menu=437"><button class="btn btn-warm">Price Update</button></a>
                                <a href="expense_management_approval.php?menu=437"><button class="btn btn-primary">Expense</button></a>
                                <a href="purchase_management_approval.php?menu=437"><button class="btn btn-success">Purchase</button></a>
                                <hr/>
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Price Update - Management Approval.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline center" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group col-xs-2">
                                                            <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                            if (isset($_POST['date_1'])) {
                                                                echo $_POST['date_1'];
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>" data-date-format="dd-mm-yyyy" />





                                                        </div>
                                                        <div class="form-group col-xs-2">
                                                            Select : 
                                                            <select name="app_flag_type" >
                                                                <option value="">-- Select --</option>
                                                                <option value="0">Pending</option>
                                                                <option value="Approve">Approve</option>
                                                                <option value="Reject">Reject</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-xs-2">
                                                            <button class="btn btn-info" type="submit" name="show" value="show">
                                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                                Show
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">

                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <form name="app_form" method="post" enctype="multipart/form-data">
                                            <table id="dynamic-tabl" class="table table-bordered table-hover">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <?php 
                                                        if ($app_flag_type == '0') { ?>
                                                            <th> 
                                                                Select All <br><input type="checkbox" id="all_checked" name="all_checked[]" />
                                                            </th>
                                                        <?php } ?>
                                                        <th>SrNo</th>
                                                        <th>Update Date</th>
                                                        <th>Item Name</th>
                                                        <th>Retailer Name</th>
                                                        <th>Last Basic</th>   
                                                        <th>Updated Basic</th>   
                                                        <th>Update By</th>
                                                        <th>Remarks</th>
                                                        <th>ApprovalStatus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
//                                                    var_dump($app_flag_type);
                                                    $index = 1;
                                                    $products = getProductPriceUpdateHistoryData($date_1, $app_flag_type);
                                                    
                                                    foreach ($products as $product) {
                                                        $apprl_ststus = '';
                                                        if ($product->approval_flag == 'Approve') {
                                                            $apprl_ststus .= "<b style='color:green'>" . $product->approval_flag . "</b>";
                                                        } else if ($product->approval_flag == 'Reject') {
                                                            $apprl_ststus .= "<b style='color:red'>" . $product->approval_flag . "</b>";
                                                        } else {
                                                            $apprl_ststus .= "<b>UpdatePending</b>";
                                                        }
                                                        ?> 
                                                        <tr>
                                                            <?php if ($app_flag_type == '0') { ?>
                                                                <td><input type="checkbox" name="checked[]" value="<?php echo $product->id; ?>"  class="checked" /></td>
                                                            <?php } else{
                                                                
                                                            } ?>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo date('d M Y', strtotime($product->date)); ?></td>
                                                            <td><?php echo getItemNameByItemId($product->item_id); ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo $product->old_price; ?></td>
                                                            <td><?php echo $product->new_price; ?></td>
                                                            <td><?php echo $product->user_name; ?></td>
                                                            <td><?php echo $product->remarks; ?></td>
                                                            <td><?php echo $apprl_ststus; ?></td>
                                                        </tr>
                                                        <?php
                                                        $index++;
                                                    }
                                                    ?>
                                                </tbody>
                                                <?php if ($app_flag_type == '0') { ?>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="11">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                Select : 
                                                                <select name="app_flag" required="">
                                                                    <option value="">-- Select --</option>
                                                                    <option value="Approve">Approve</option>
                                                                    <option value="Reject">Reject</option>
                                                                </select>
                                                            </td>
                                                            <td colspan="8">
                                                                <input type="submit" name="update_data" class="btn btn-success btn-sm" value="Update" />
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                <?php } ?>
                                            </table>
                                        </form>
                                    </div>


                                    <?php
                                    if (isset($_POST['update_data'])) {
//                                        print_r($_SESSION);
//                                        exit();
                                        $upd_flag = $_POST['app_flag'];
                                        $data_ids = $_POST['checked'];


                                        for ($i = 0; $i <= count($data_ids); $i++) {
                                            $upd_arr = array();
                                            $upd_arr['approval_flag'] = $upd_flag;
                                            $upd_arr['approval_date'] = date('Y-m-d H:i:s');
                                            $upd_arr['approved_by'] = $_SESSION['username'];
                                            $whr = "id = '" . $data_ids[$i] . "'";
                                            $up = update('history_for_inventory_master', $upd_arr, $whr);
                                        }
                                        echo "<script>window.location=window.location;</script>";
                                    }
                                    ?>





                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
            <script type="text/javascript">

                $("#all_checked").click(function () {
                    if ($("#all_checked").is(':checked')) {
                        $(".checked").prop("checked", true);
                    } else {
                        $(".checked").prop("checked", false);
                    }
                });


                $('#Retailer_id').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: true
                });
                $('#item_code').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Item --', // text to use in dummy input
                    },
                    selectAll: true
                });
            </script>
        </div>
    </body>
</html>

