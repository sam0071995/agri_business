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
    if (isset($_POST['item_code'])) {
        $item_code = $_POST['item_code'];
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
                                            <h4 class="widget-title">Retailer | Product wise Sales Report.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline center" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group col-xs-3">
                                                            <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                            if (isset($_POST['date_1'])) {
                                                                echo $_POST['date_1'];
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                        </div>

                                                        <div class="form-group col-xs-3">
                                                            <input class="form-control date-picker" id="" name="date_2"  type="text" value="<?php
                                                            if (isset($_POST['date_2'])) {
                                                                echo $_POST['date_2'];
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                        </div>

                                                        <div class="form-group col-xs-2">
                                                            <select class="form-control" multiple name="Retailer_id[]" id="Retailer_id">
                                                                <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                    <option value="<?php echo $active_sellers->id; ?>"><?php echo $active_sellers->name; ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>

                                                        <div class="form-group col-xs-2">
                                                            <select class="form-control" name="category_id" id="category_id">
                                                                <option value="">All category</option>
                                                                <?php foreach (getParentActiveCategories() as $category) { ?>
                                                                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                                <?php } ?>
                                                            </select>

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
                                <div class="modal-body">
                                    <?php if (isset($_POST['show'])) { ?>
                                                                                                                                                                                                                                                                                                <!--                                        <h5 class="red">Total sale amount between <?php // echo $date_1;                                                                          ?> and <?php // echo $date_2;                                                                          ?> is : <b class="blue"><?php // echo IND_money_format(getProductSalesTotalAmtByRetailerTempTable($date_1, $date_2, $retailer_id, $company_id_in));                                                                          ?> Rs.</b></h5>-->
                                    <?php } ?>
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Retailer Name</th>
                                                    <th>Customer Name</th>
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
                                                    <th>Unit</th>
                                                    <th>Taxable Value</th>
                                                    <th>CGST</th>
                                                    <th>SGST</th>
                                                    <th>IGST</th>
                                                    <th>Total</th>
                                                    <th>Fincial Year</th>   
                                                    <th>BillDate</th>
                                                    <th>BillTime</th>
                                                    <th>Order Type</th>
                                                    <th>Credit Note</th>
                                                    <th>Return Qty</th>
                                                    <!--<th>Batch Qty</th>-->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $final_total = 0;
                                                $final_basic = 0;
                                                $final_sgst = 0;
                                                $final_cgst = 0;
                                                $final_qty = 0;
                                                $final__return_qty = 0;
                                                if (isset($_POST['show'])) {
                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));

                                                    if (isset($_POST['Retailer_id'])) {
                                                        $retailer_id_aray = $_POST['Retailer_id'];
                                                    } else {
                                                        print '<script>alert("Select Retailer");window.location="product_wise_sales_report.php' . $menuURL . '";</script>';
                                                        exit;
                                                    }
                                                    $category_id = $_POST['category_id'];

                                                    $retailer_id_string = "'";
                                                    $retailer_id_string .= implode("','", $retailer_id_aray);
                                                    $retailer_id_string .= "'";

                                                    $index = 1;


                                                    $products = getProductSalesByRetailerTempTableCatIN($date_1, $date_2, $retailer_id_string, $company_id_in, $category_id);
                                                    foreach ($products as $product) {
                                                        if ($product->payment_type == 0) {
                                                            $payment_type = "CASH";
                                                        } else if ($product->payment_type == 1) {
                                                            $payment_type = "ONLINE";
                                                        } else {
                                                            $payment_type = "Cheque/DD";
                                                        }
                                                        $item_detail = getproductDetailsByCode($product->item_code); 

                                                        $final_total = $final_total + $product->total_price;
                                                        $final_basic = $final_basic + $product->basic;
                                                        $final_sgst = $final_sgst + $product->sgst;
                                                        $final_cgst = $final_cgst + $product->cgst;
                                                        $final_qty = $final_qty + $product->qty;
                                                        $final__return_qty = $final__return_qty + $product->return_qty;
                                                        ?> 
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo $product->cus_name; ?></td>
                                                            <td><?php echo $product->order_no; ?></td>
                                                            <!--<td><?php // echo $product->item_name; ?></td>-->
                                                            <td><?php echo getItemNameByItemCode($product->item_code); ?></td>
                                                            <td><?php echo getItemHSNCODEByItemCode($product->item_code); ?></td>
                                                            <td><?php echo getCategoryNameById($item_detail->main_category_id); ?></td>
                                                            <td><?php echo getCategoryNameById($item_detail->sub_category_id); ?></td>
                                                            <td><?php echo $product->batch_no; ?></td>
                                                            <td><?php echo getExpiryDateByItemCode($product->retailer_id, $product->item_code, $product->batch_no); ?></td>
                                                            <td>
                                                                <?php echo $payment_type; ?><?php
                                                                if (!empty($product->transaction_no)) {
                                                                    echo "<hr/>Transaction No : <b class='red'>" . $product->transaction_no . "</b>";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $product->sgst_rate + $product->cgst_rate; ?></td>
                                                            <td><?php echo round($product->qty, 2); ?></td>
                                                            <td><?php echo round($product->total_price / $product->qty, 2); ?></td>
                                                            <td><?php echo getItemUnitByItemCode($product->item_code); ?></td>
                                                            <td><?php echo round($product->basic, 2); ?></td>
                                                            <td><?php echo round($product->cgst, 2); ?></td>
                                                            <td><?php echo round($product->sgst, 2); ?></td>
                                                            <td><?php echo 0; ?></td>
                                                            <td><?php echo round($product->total_price, 2); ?></td>
                                                            <td><?php echo $product->fin_year; ?></td>
                                                            <td><?php echo date('d M Y', strtotime($product->added_date)); ?></td>
                                                            <td><?php echo date('H:i', strtotime($product->added_datetime)); ?></td>
                                                            <td><?php
                                                                if ($product->b2b_flg == 0) {
                                                                    echo 'B2C';
                                                                } else {
                                                                    echo 'B2B';
                                                                    echo '<br/>';
                                                                    echo $product->gstin_no;
                                                                }
                                                                ?></td>
                                                            <td><?php echo $product->credit_note_no; ?></td>
                                                            <td><?php echo $product->return_qty; ?></td>
                                                            <!--<td><?php // echo getToalSalesBatchBlockedQty($product->retailer_id, $product->batch_no, $product->item_code, $product->order_no);                                          ?></td>-->
                                                        </tr>

                                                        <?php
                                                        $index++;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td></td>
                                                    <td>Final Total</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?php echo IND_money_format($final_qty); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?php echo IND_money_format($final_basic); ?></td>
                                                    <td><?php echo IND_money_format($final_sgst); ?></td>
                                                    <td><?php echo IND_money_format($final_cgst); ?></td>
                                                    <td></td>
                                                    <td><?php echo IND_money_format($final_total); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?php echo IND_money_format($final__return_qty); ?></td>
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

