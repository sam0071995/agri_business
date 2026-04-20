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
                                <h3 class="header">Customer Payment Pending Report.</h3>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form class="form-inline center" action="" method="POST">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-xs-14">
                                                        <b>From Date :</b>
                                                        <div class="input-group">
                                                            <select class="form-field-select-2 form-control" multiple name="Retailer_id[]" id="Retailer_id" required="required">
                                                                <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                    <option value="<?php echo $active_sellers->id; ?>"><?php echo $active_sellers->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                            <input class="form-control date-picker" id="id-" name="date_2" type="text" value="<?php
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
                                                        <div class="col-md-offset-3 col-md-5">
                                                            <button class="btn btn-info" type="submit" name="show" value="show">
                                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                                Show
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.row -->
                                <hr/>
                                <?php
                                if (isset($_POST['show'])) {
                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="row">
                                            <div class="row clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                                <div>
                                                    <table id="dynamic-table" class="table table-bordered table-hover">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th width="8%" align="left">#</th>
                                                                <th width="8%" align="left">Retailer Name</th>
                                                                <th width="8%" align="left">InvoiceNo</th>
                                                                <th width="8%" align="left">CusName</th>
                                                                <th width="8%" align="left">CusMobile</th>
                                                                <th width="8%" align="left">CusAddress</th>
                                                                <th width="15%" align="left">Date</th>
                                                                <th width="15%" align="left">Amount</th>
                                                                <th width="15%" align="left">borrowedAmount</th>
                                                                <th width="15%" align="left">CreditAmount</th>
                                                                <th width="15%" align="left">PendingAmount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $index = 1;
                                                            $retailer_id_Array = $_POST['Retailer_id'];
                                                            $retailer_id_string = implode("','", $retailer_id_Array);
                                                            $retailer_id_string = "'" . $retailer_id_string . "'";
                                                            $orderDetails = getBookSaleOrderBetweenDateByOrderId($date_1, $date_2, $retailer_id_string);
                                                            foreach ($orderDetails as $orderDetail) {
                                                                $pendingAmount = amount($orderDetail->pending_amount - $orderDetail->credit_amount);
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $index; ?></td>
                                                                    <td><?php echo getRetailerNameById($orderDetail->retailer_id); ?></td>
                                                                    <td><?php echo $orderDetail->po_no; ?></td>
                                                                    <td><?php echo $orderDetail->cus_name; ?></td>
                                                                    <td><?php echo $orderDetail->cus_ph; ?></td>
                                                                    <td><?php echo $orderDetail->cus_add; ?></td>
                                                                    <td><?php echo date("d M Y H:i:s", strtotime($orderDetail->added_datetime)); ?></td>
                                                                    <td><?php echo amount($orderDetail->total_price); ?></td>
                                                                    <td><?php echo amount($orderDetail->pending_amount); ?></td>
                                                                    <td><?php echo $orderDetail->credit_amount; ?></td>
                                                                    <td><?php echo $pendingAmount; ?></td>
                                                                </tr>
                                                                <?php
                                                                $index++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.row -->
                                <?php } ?>
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
            <script type="text/javascript">
                $('#Retailer_id').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: true
                });
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>