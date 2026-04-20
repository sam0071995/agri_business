<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = "";
if (isset($_POST['status'])) {
    $status = $_POST['status'];
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
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h3 class="header-text">Book Sale Report.</h3>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <form class="form-inline center" action="" method="POST">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>From Order Date :</b>
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
                                                                        <b>To Order Date :</b>
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
                                                                        <b>Select Status :</b>
                                                                        <div class="input-group">
                                                                            <select class="form-control col-xs-3" name="status" id="status">
                                                                                <option <?php
                                                                                if ($status == 1) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                                ?> value="1">Success</option>
                                                                                <option <?php
                                                                                if ($status == 2) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                                ?> value="2">Rejected</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix form-actions">
                                                                    <div class="col-md-offset-3 col-md-5">
                                                                        <button class="btn btn-info" type="submit" name="show" value="show">
                                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                                            Show
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div><!-- /.row -->

                                                <div class="row">
                                                    <div class="modal-body">
                                                        <div class="row clearfix">
                                                            <div class="pull-right tableTools-container"></div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th width="8%" align="left">#</th>
                                                                    <th width="15%" align="left">Purchase Date</th>
                                                                    <th width="15%" align="left">Purchase No</th>
                                                                    <th width="15%" align="left">Item</th>
                                                                    <th width="15%" align="left">ItemCount</th>
                                                                    <th width="15%" align="left">Amount</th>
                                                                    <th width="15%" align="left">DiscountAmount</th>
                                                                    <th width="15%" align="left">CouponCode</th>
                                                                    <th width="15%" align="left">PaymentType</th>
                                                                    <th width="15%" align="left">Transaction No</th>
                                                                    <th width="15%" align="left">OredrBy</th>
                                                                    <th width="15%" align="left">OredrType</th>
                                                                    <th width="15%" align="left">FinYear</th>
                                                                    <th width="15%" align="left">Cus Name</th>
                                                                    <th width="15%" align="left">Cus Mobile</th>
                                                                    <th width="15%" align="left">Cus Adhar</th>
                                                                    <th width="15%" align="left">Cus Village</th>
                                                                    <th width="15%" align="left">Cus Address</th>
                                                                    <th width="25%" align="left">Status</th>
                                                                    <th width="25%" align="left">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $status = 0;
                                                                $i = 1;
                                                                if (isset($_POST['show'])) {
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $status = 'All';
                                                                    if (isset($_POST['status'])) {
                                                                        $status = $_POST['status'];
                                                                    }

                                                                    $purchaseOrder = getBookSaleOrdersByRetailerIdBetweenDates($date_1, $date_2, $status, $_SESSION['id']);
                                                                    foreach ($purchaseOrder as $row) {
                                                                        if ($row->bdm_id != 0) {
                                                                            $oredr_by = "BDM ( " . getBDMdataById($row->bdm_id)->name . " )";
                                                                        } else {
                                                                            $oredr_by = "SELF";
                                                                        }
                                                                        $deleted = "";
                                                                        $deleted_css = "";
                                                                        if ($row->status == 7) {
                                                                            $deleted_css = "color:red;";
                                                                            $deleted = "Deleted";
                                                                        }
                                                                        if ($row->b2b_flg == 0) {
                                                                            $oredr_type = "B2C";
                                                                        } else {
                                                                            $oredr_type = "B2B";
                                                                        }
                                                                        if ($row->payment_type == 0) {
                                                                            $payment_type = "CASH";
                                                                        } else if ($row->payment_type == 1) {
                                                                            $payment_type = "ONLINE";
                                                                        } else {
                                                                            $payment_type = "Cheque/DD";
                                                                        }
                                                                        ?>
                                                                        <tr style="<?php echo $deleted_css; ?>">
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo $row->added_date; ?></td>
                                                                            <td><?php echo $row->po_no; ?></td>
                                                                            <td><?php echo getRetailerItemNameByItemCode($row->item_code); ?></td>
                                                                            <td><?php echo $row->qty; ?></td>
                                                                            <td><?php echo $row->price; ?></td>
                                                                            <td><?php echo $row->discount_amount; ?></td>
                                                                            <td><?php echo $row->coupon_code; ?></td>
                                                                            <td><?php echo $payment_type; ?></td>
                                                                            <td><?php echo $row->transaction_no; ?></td>
                                                                            <td><?php echo $oredr_by; ?></td>
                                                                            <td><?php echo $oredr_type; ?></td>
                                                                            <td><?php echo $row->fin_year; ?></td>
                                                                            <td><?php echo $row->cus_name; ?></td>
                                                                            <td><?php echo $row->cus_ph; ?></td>
                                                                            <td><?php echo $row->cus_adhar; ?></td>
                                                                            <td><?php echo getVillageNameById($row->cus_village); ?></td>
                                                                            <td><?php echo $row->cus_add; ?></td>
                                                                            <td><?php echo $deleted; ?></td>
                                                                            <td><a href="book_sale_invoice.php?menu=1&btn_no=<?php echo base64_encode($row->po_no); ?>" target="_blank" class="btn btn-success btn-xs">Print</a> </td>
                                                                        </tr>
                                                                        <?php
                                                                        $i++;
                                                                    }
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.row -->
                                    </div>
                                </div><!-- /.row -->
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">
                function inward_item(id) {
                    alert(id);
                    if (confirm("Are you sure you want to Inward this?")) {
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $ajax_inward; ?>',
                            data: {
                                'id': id,
                                'request_type': 'inward_grn'
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 0) {
                                    ;
                                    alert('Your Item Inward Successfully...!!');
                                } else {
                                    alert('Item Inward Error...!!');
                                }
                            }
                        });
                    }
                }
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>