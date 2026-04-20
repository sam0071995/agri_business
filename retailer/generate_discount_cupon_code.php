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
                                        <h4 class="widget-title"> Discount Coupon Code Report.</h4>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <div class="row clearfix">
                                            <div class="pull-right tableTools-container"></div>
                                        </div>
                                        <div>
                                            <table id="dynamic-table" class="table table-bordered table-hover">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>CouponCode</th>
                                                        <th>DiscountPrice</th>
                                                        <th>GenerateDate</th>
                                                        <th>Status</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <?php
                                                    

                                                    //get all recors of coupons=============================
                                                    $products = getCouponData($_SESSION['id']);
                                                    $index = 1;
                                                    foreach ($products as $product) {
                                                        $status = "";
                                                        if ($product->status == 0) {
                                                            $status = "Not Used";
                                                        } else {
                                                            $status = "Used";
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo $product->discount_code; ?></td>
                                                            <td><?php echo $product->price; ?></td>
                                                            <td><?php echo $product->coupon_generate_date; ?></td>
                                                            <td><?php echo $status; ?></td>

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

            </div>
</body>

</html>