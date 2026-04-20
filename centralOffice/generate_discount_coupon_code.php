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

                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form name="myForm" id="myForm" role="form" action="" method="POST"  enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-sm-2">
                                                                <b>Select Retailer :</b>
                                                                <select class="form-field-select-2 form-control" multiple name="retailer_id[]" id="Retailer_id" required="required">
                                                                    <?php foreach (getActiveRetailerDetails($company_id_in) as $retailers) { ?>
                                                                        <option value="<?php echo $retailers->id; ?>"><?php echo $retailers->name; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-2">
                                                                <b>Enter Item Name :</b>
                                                                <input class="form-control" name="item_code" required="required" type="text"  />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-2">
                                                                <b>Coupon Name:</b>
                                                                <input class="form-control" name="copon_name" required="required" type="text" placeholder="FTAT100" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-1">
                                                                <b>Number Of Coupons:</b>
                                                                <select class="form-control" name="no_of_copons" required="required" >
                                                                    <?php for ($i = 1; $i <= 50; $i++) { ?>
                                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-sm-2">
                                                                <b>Valid Days :</b>
                                                                <input class="form-control" name="valid_days" required="required" type="text" value="" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-2">
                                                                <b>Enter Price :</b>
                                                                <input class="form-control" name="price" required="required" type="text" value="" />
                                                            </div>
                                                        </div>

                                                        <button class="btn btn-info btn-sm" type="submit" name="show" value="show">
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            Generate
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
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
                                                        if (isset($_POST['show'])) {
                                                            if (!isset($_POST['price']) && empty($_POST['price'])) {
                                                                echo '<b>Enter Price.</b>';
                                                                exit;
                                                            }
                                                            $price = $_POST['price'];
                                                            $item_code = $_POST['item_code'];
                                                            $copon_name = strtoupper($_POST['copon_name']);
                                                            $valid_days = (int) $_POST['valid_days'];
                                                            $no_of_copons = (int) $_POST['no_of_copons'];
                                                            $expiry_date = date('Y-m-d', strtotime("+$valid_days days"));


                                                            $retailer_id_array = $_POST['retailer_id'];
                                                            foreach ($retailer_id_array as $retailer_id) {

                                                                for ($j = 1; $j <= $no_of_copons; $j++) {
                                                                    $clean_copon_name = preg_replace("/[^a-zA-Z0-9]/", "", $copon_name);
                                                                    $clean_copon_name .= $j;

                                                                    // generate random coupon code=================
                                                                    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                                                                    for ($i = 0; $i < 5; $i++) {
                                                                        $clean_copon_name .= $chars[mt_rand(0, strlen($chars) - 1)];
                                                                    }
                                                                    // generate random coupon code=================
                                                                    $check = getDuplicateCouponCount($clean_copon_name);

                                                                    if (count($check) == 0) {
                                                                        $insarr = array();
                                                                        $insarr['retailer_id'] = $retailer_id;
                                                                        $insarr['valid_days'] = $valid_days;
                                                                        $insarr['valid_till_date'] = $expiry_date;
                                                                        $insarr['company_id'] = getRetailerCompanyIdById($retailer_id);
                                                                        $insarr['discount_code'] = $clean_copon_name;
                                                                        $insarr['price'] = $price;
                                                                        $insarr['item_code'] = $item_code;
                                                                        $insarr['coupon_name'] = $copon_name;
                                                                        $insarr['no_of_coupons'] = $no_of_copons;
                                                                        $insarr['coupon_generate_date'] = date('Y-m-d H:i:s');
                                                                        insert('tbl_discount_coupon', $insarr);
                                                                    }
                                                                }
                                                                $j = 1;
                                                            }

                                                            print '<script>alert("Coupon Code Successfully Added.");window.location="generate_discount_coupon_code.php' . $menuURL . '";</script>';
                                                            exit;
                                                        }

                                                        //get all recors of coupons=============================
                                                        $products = getCouponData();
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
                                                                <td><?php echo getItemNameByItemId($product->item_code); ?></td>
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