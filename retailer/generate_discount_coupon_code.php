<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
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
                                            $msg = "Order Not Has been Rejected.";
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
                                        <?php echo "Successfully Rejected."; ?>
                                    </div>
                                <?php } ?>
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Generate Discount Coupon Code.</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row clearfix">
                                                <div class="pull-right tableTools-container"></div>
                                            </div>
                                            <div>
                                                <div class="widget-header">
                                                    <a href="coupon_print_all.php?menu=1" target="_blank"><button>Print All</button></a>
                                                </div>
                                                <table id="dynamic-table" class="table table-bordered table-hover">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Retailer</th>
                                                            <th>ItemName</th>
                                                            <th>CouponName</th>
                                                            <th>CouponCode</th>
                                                            <th>Valid Days</th>
                                                            <th>Valid Till Date</th>
                                                            <th>Price</th>
                                                            <th>GenerateDate</th>
                                                            <th>Used On</th>
                                                            <th>Order No</th>
                                                            <th>Status</th>
                                                            <th></th>
                                                        </tr>

                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        //get all recors of coupons=============================
                                                        $products = getCouponData($_SESSION['id']);
                                                        $index = 1;
                                                        foreach ($products as $product) {
                                                            $status = "";
                                                            if ($product->status == 0 && $product->valid_till_date < date("Y-m-d")) {
                                                                $status = "<b class='red'>Expired</b>";
                                                                $q = mysqli_query($conn, "UPDATE tbl_discount_coupon set status=7 where status='0' and id='" . $product->id . "'");
                                                            } else if ($product->status == 7) {
                                                                $status = "<b class='red'>Expired</b>";
                                                            } else if ($product->status == 0) {
                                                                $status = "<b class='green'>Free</b>";
                                                            } else {
                                                                $status = "<b class='blue'>Used</b>";
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $index; ?></td>
                                                                <td><?php echo getRetailerById($product->retailer_id)->name; ?></td>
                                                                <td><?php echo $product->item_code; ?></td>
                                                                <td><?php echo $product->coupon_name; ?></td>
                                                                <td><?php echo $product->discount_code; ?></td>
                                                                <td><?php echo $product->valid_days; ?></td>
                                                                <td><?php echo $product->valid_till_date; ?></td>
                                                                <td><?php echo $product->price; ?></td>
                                                                <td><?php echo $product->coupon_generate_date; ?></td>
                                                                <td><?php echo $product->coupon_used_date; ?></td>
                                                                <td><?php echo $product->coupon_used_order_no; ?></td> 
                                                                <td><?php echo $status; ?></td> 
                                                                <td><a href="coupon_print.php?menu=1&coupon_no=<?php echo base64_encode($product->discount_code); ?>" target="_blank"><?php echo "Print"; ?></a></td>
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
                            </div><!-- /.page-content -->
                        </div>
                    </div><!-- /.main-content -->
                    <!--END MAIN WRAPPER -->
                    <script type="text/javascript">

                    </script>
                    <?php require_once 'includes/footer.php'; ?>
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
                </div>
                </body>

                </html>