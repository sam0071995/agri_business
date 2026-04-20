<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
if (isset($_POST['show'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
    $retailer_id = 'All';
    if (isset($_POST['Retailer_id'])) {
        $retailer_id = $_POST['Retailer_id'];
    }
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
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Retailer | Day wise Sales Report.</h4>
                                        </div>
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
                                                                <b>Select Retailer :</b>
                                                                <div class="input-group">
                                                                    <select class="form-control" multiple name="Retailer_id[]" id="Retailer_id">
                                                                        <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                            <option value="<?php echo $active_sellers->id; ?>"><?php echo $active_sellers->name; ?></option>
                                                                        <?php } ?>
                                                                    </select>
        <!--                                                                    <select class="form-control col-xs-3" name="Retailer_id" id="Retailer_id" required="required">
                                                                                <option value="All">All Retailers</option>
                                                                    <?php // foreach (getAllRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                                        <option value="<?php // echo $active_sellers->id;                      ?>" <?php
//                                                                        if ($retailer_id == $active_sellers->id) {
//                                                                            echo 'selected="selected"';
//                                                                        }
                                                                    ?>><?php // echo $active_sellers->name; ?><?php
//                                                                        if ($active_sellers->status == 0) {
//                                                                            echo '<b class="red"> [Clossed]</b>';
//                                                                        }
                                                                    ?></option>
                                                                    <?php // } ?>
                                                                            </select>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>Select Category :</b>
                                                                <div class="input-group">
                                                                    <select class="form-control col-xs-3" name="category_id" id="category_id">
                                                                        <option value="All">All category</option>
                                                                        <?php foreach (getParentActiveCategories() as $category) { ?>
                                                                            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                                        <?php } ?>
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

                                                                &nbsp; &nbsp; &nbsp;
                                                                <button class="btn" type="reset">
                                                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                    Reset
                                                                </button>
                                                            </div>
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
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Retailer Name</th>
                                                    <th>Category</th>
                                                    <th>Total</th>
                                                    <th>SGST</th>   
                                                    <th>CGST</th>   
                                                    <th>IGST</th>   
                                                    <th>Basic</th>
                                                    <th>Discount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $final_total = 0;
                                                $final_basic = 0;
                                                $final_gst = 0;
                                                $final_sgst = 0;
                                                $final_cgst = 0;
                                                $final_discount_amount = 0;
                                                if (isset($_POST['show'])) {
                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                    $category_id = $_POST['category_id'];
                                                    $index = 1;

                                                    if (isset($_POST['Retailer_id'])) {
                                                        $retailer_id_aray = $_POST['Retailer_id'];
                                                    } else {
                                                        print '<script>alert("Select Retailer");window.location="product_wise_sales_report.php' . $menuURL . '";</script>';
                                                        exit;
                                                    }
                                                    $retailer_id_string = "'";
                                                    $retailer_id_string .= implode("','", $retailer_id_aray);
                                                    $retailer_id_string .= "'";

                                                    $products = getProductSalesBy_IN_RetailerTempTableWiseCat($date_1, $date_2, $retailer_id_string, $company_id_in, $category_id);
                                                    foreach ($products as $product) {
                                                        if ($product->payment_type == 0) {
                                                            $payment_type = "CASH";
                                                        } else if ($product->payment_type == 1) {
                                                            $payment_type = "ONLINE";
                                                        } else {
                                                            $payment_type = "Cheque/DD";
                                                        }
                                                        if ($category_id == "All") {
                                                            $catName = "All";
                                                        } else {
                                                            $catName = getCategoryNameById($category_id);
                                                        }
                                                        $final_total = $final_total + $product->total_price;
                                                        $final_basic = $final_basic + $product->basic;
                                                        $final_gst = $final_gst + $product->sgst + $product->cgst;
                                                        $final_sgst = $final_sgst + $product->sgst;
                                                        $final_cgst = $final_cgst + $product->cgst;
                                                        $final_discount_amount = $final_discount_amount + $product->discount_amount;
                                                        if (validateGSTIN($gstin)) {
                                                            
                                                        } else {
                                                            
                                                        }
                                                        ?>  
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo $catName; ?></td>
                                                            <td><?php echo amount($product->total_price); ?></td>
                                                            <td><?php echo amount($product->sgst); ?></td>
                                                            <td><?php echo amount($product->cgst); ?></td>
                                                            <td><?php echo amount(0); ?></td>
                                                            <td><?php echo amount($product->basic); ?></td>
                                                            <td><?php echo amount($product->discount_amount); ?></td>
                                                        </tr>
                                                        <?php
                                                        $index++;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan=""></td>
                                                    <td colspan=""></td>
                                                    <td colspan="">Final Total</td>
                                                    <td><?php echo IND_money_format($final_total); ?></td>
                                                    <td><?php echo IND_money_format($final_sgst); ?></td>
                                                    <td><?php echo IND_money_format($final_cgst); ?></td>
                                                    <td><?php echo IND_money_format(amount(0)); ?></td>
                                                    <td><?php echo IND_money_format($final_basic); ?></td>
                                                    <td><?php echo IND_money_format($final_discount_amount); ?></td>
                                                </tr>
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

