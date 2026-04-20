<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
$buyer_id = '';
if (isset($_POST['buyer_id'])) {
    $buyer_id = $_POST['buyer_id'];
}
if (isset($_POST['show'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
    $buyer_id = 'All';
    if (isset($_POST['buyer_id'])) {
        $buyer_id = $_POST['buyer_id'];
    }
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
                                            <h4 class="widget-title">Retailer | Product wise Sales Report For <b class="red">B2B</b> Orders.</h4>
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

                                                        <br/>
                                                        <br/>

                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>Select Retailer :</b>
                                                                <div class="input-group">
                                                                    <select class="form-control col-xs-3" name="Retailer_id" id="Retailer_id" required="required">
                                                                        <option value="All">All Retailers</option>
                                                                        <?php foreach (getAllRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                            <option value="<?php echo $active_sellers->id; ?>" <?php
                                                                            if ($retailer_id == $active_sellers->id) {
                                                                                echo 'selected="selected"';
                                                                            }
                                                                            ?>><?php echo $active_sellers->name; ?><?php
                                                                                        if ($active_sellers->status == 0) {
                                                                                            echo '<b class="red"> [Clossed]</b>';
                                                                                        }
                                                                                        ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <b>Select Buyer :</b>
                                                                <div class="input-group">
                                                                    <select class="form-control col-xs-3" name="buyer_id" id="buyer_id" required="required">
                                                                        <option value="All">All Buyer</option>
                                                                        <?php foreach (B2BBuyerList($company_id_in) as $active_buyer) { ?>
                                                                            <option value="<?php echo $active_buyer->cus_name; ?>" <?php
                                                                            if ($buyer_id == $active_buyer->cus_name) {
                                                                                echo 'selected="selected"';
                                                                            }
                                                                            ?>><?php echo $active_buyer->cus_name; ?></option>
                                                                                <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <br/>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
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
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="row clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div>

                                    <?php if (isset($_POST['show'])) { ?>
                                        <h4 class="red">Total sale amount between <?php echo $date_1; ?> and <?php echo $date_2; ?> is : <b class="blue"><?php echo IND_money_format(getB2BProductSalesTotalAmtByRetailerTempTable($date_1, $date_2, $retailer_id, $company_id_in)); ?> Rs.</b></h4>
                                    <?php } ?>
                                    <table id="dynamic-table" class="table table-bordered table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Retailer Name</th>
                                                <th>Buyer Name</th>
                                                <th>Order No</th>
                                                <th>Item Name</th>
                                                <th>HSNCODE</th>
                                                <th>Category</th>   
                                                <th>Sub Category</th>   
                                                <th>Batch No</th>
                                                <th>Expiry Date</th>
                                                <th>PaymentType</th>
                                                <th>GST Rate</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>UNIT</th>
                                                <th>Taxable Value</th>
                                                <th>CGST</th>
                                                <th>SGST</th>
                                                <th>IGST</th>
                                                <th>Total</th>
                                                <th>Fincial Year</th>   
                                                <th>BillDate</th>
                                                <th>BillTime</th>
                                                <th>Order Type</th>
                                                <th>GSTIN No</th>
                                                <th>Credit Note</th>
                                                <th>Return Qty</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($_POST['show'])) {
                                                $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                $retailer_id = 'All';
                                                if (isset($_POST['Retailer_id'])) {
                                                    $retailer_id = $_POST['Retailer_id'];
                                                }
                                                $buyer_id = 'All';
                                                if (isset($_POST['buyer_id'])) {
                                                    $buyer_id = $_POST['buyer_id'];
                                                }
                                                $category_id = $_POST['category_id'];

                                                $index = 1;
                                                $products = getProductSalesByRetailerTempTableB2BBuyerCat($date_1, $date_2, $retailer_id, $company_id_in, $buyer_id, $category_id);
                                                foreach ($products as $product) {
                                                    if ($product->payment_type == 0) {
                                                        $payment_type = "CASH";
                                                    } else if ($product->payment_type == 1) {
                                                        $payment_type = "ONLINE";
                                                    } else {
                                                        $payment_type = "Cheque/DD";
                                                    }
                                                    $item_detail = getproductDetailsByCode($product->item_code);
                                                    $class = "";
                                                    $status = "Success";
                                                    if ($product->status == 7) {
                                                        $class = "red";
                                                        $status = "Rejected";
                                                    }
                                                    ?> 
                                                    <tr class="<?php echo $class; ?>">
                                                        <td><?php echo $index; ?></td>
                                                        <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                        <td><?php echo $product->cus_name; ?></td>
                                                        <td><?php echo $product->order_no; ?></td>
                                                        <td><?php echo $product->item_name; ?></td>
                                                        <td><?php echo getItemHSNCODEByItemCode($product->item_code); ?></td>
                                                        <td><?php echo getCategoryNameById($item_detail->main_category_id); ?></td>
                                                        <td><?php echo getCategoryNameById($item_detail->sub_category_id); ?></td>
                                                        <td><?php echo $product->batch_no; ?></td>
                                                        <td><?php echo getExpiryDateByItemCode($product->retailer_id, $product->item_code, $product->batch_no); ?></td>
                                                        <td><?php echo $payment_type; ?><?php
                                                            if (!empty($product->transaction_no)) {
                                                                echo "<hr/>Transaction No : <b class='red'>" . $product->transaction_no . "</b>";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $product->sgst_rate + $product->cgst_rate; ?></td>
                                                        <td><?php echo $product->qty; ?></td>
                                                        <td><?php echo round($product->total_price / $product->qty, 2); ?></td>
                                                        <td><?php echo getItemUNITByItemCode($product->item_code); ?></td>
                                                        <td><?php echo $product->basic; ?></td>
                                                        <td><?php echo $product->cgst; ?></td>
                                                        <td><?php echo $product->sgst; ?></td>
                                                        <td><?php echo 0; ?></td>
                                                        <td><?php echo $product->total_price; ?></td>
                                                        <td><?php echo $product->fin_year; ?></td>
                                                        <td><?php echo date('d M Y', strtotime($product->added_date)); ?></td>
                                                        <td><?php echo date('H:i', strtotime($product->added_datetime)); ?></td>
                                                        <td><?php
                                                            if ($product->b2b_flg == 0) {
                                                                echo 'B2C';
                                                            } else {
                                                                echo 'B2B';
                                                            }
                                                            ?></td>
                                                        <td><?php echo $product->gstin_no; ?></td>
                                                        <td><?php echo $product->credit_note_no; ?></td>
                                                        <td><?php echo $product->return_qty; ?></td>
                                                        <td><?php echo $status; ?></td>
                                                    </tr>
                                                    <?php
                                                    $index++;
                                                }
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
        <?php require_once 'includes/footer.php'; ?>    
    </div>
</body>
</html>

