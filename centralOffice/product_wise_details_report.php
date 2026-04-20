<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
if (isset($_POST['po_type'])) {
    $po_type = $_POST['po_type'];
} else {
    $po_type = "";
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
                                    <?php
                                }
//                                echo "<pre />";
//                                print_r($_SESSION);
                                ?>
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Purchase Details Report.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline center" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>Inward From Date :</b>
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
                                                                <b>Inward To Date :</b>
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
                                                                <b>Select PO :</b>
                                                                <div class="input-group">
                                                                    <select class="form-control col-xs-3 chosen-select" name="po_no" id="po_no" >
                                                                        <option value="all">--- Select PO NO ---</option>
                                                                        <?php foreach (getAllPONOListByUserId($_SESSION['id']) as $active_sellers) { ?>
                                                                            <option value="<?php echo $active_sellers->po_no; ?>" ><?php echo $active_sellers->po_no; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <br />

                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>Select Item :</b>
                                                                <div class="input-group">
                                                                    <select class="form-control chosen-select col-xs-3" name="item_code" id="item_code" >
                                                                        <option value="00">-- Select Item --</option>
                                                                        <?php foreach (getProductsList() as $itemr) { ?>
                                                                            <option style="text-align:left;" value="<?= $itemr->item_code; ?>"><?= $itemr->item_desc; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <br />
                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>Select PO Type:</b>
                                                                <div class="input-group">
                                                                    <select class="text" name="po_type" id="po_type" required="required">
                                                                        <option value="0" <?php
                                                                        if ($po_type == 0) {
                                                                            echo 'selected="selected"';
                                                                        }
                                                                        ?>>Both</option>
                                                                        <option value="1" <?php
                                                                        if ($po_type == 1) {
                                                                            echo 'selected="selected"';
                                                                        }
                                                                        ?>>Purchase Order</option>
                                                                        <option value="2" <?php
                                                                        if ($po_type == 2) {
                                                                            echo 'selected="selected"';
                                                                        }
                                                                        ?>>Credit Note</option>
                                                                        <option value="3" <?php
                                                                        if ($po_type == 3) {
                                                                            echo 'selected="selected"';
                                                                        }
                                                                        ?>>STOCK TRANSFER</option>
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
                                        </div>
                                    </div>
                                </div>
                                <?php if (isset($_POST['show'])) { ?>
                                    <div class="modal-body">

                                        <div class="row clearfix">
                                            <div class="pull-right tableTools-container"></div>
                                        </div>
                                        <div>
                                            <table id="dynamic-table" class="table table-bordered table-hover">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>SrNo</th>
                                                        <th>PurchaseNo</th>
                                                        <th>BillNo</th>
                                                        <th>PurchaseDate</th>
                                                        <th>InvoiceDate</th>
                                                        <th>InwardDate</th>
                                                        <th>QuotationNo</th>
                                                        <th>QuotationDate</th>
                                                        <th>SupplierName</th>   
                                                        <th>SupplierGSTIN</th>   
                                                        <th>RetailerName</th>   
                                                        <th>PO Type</th>
                                                        <th>PO Taxable</th>
                                                        <th>PO Discount</th>
                                                        <th>Freight</th>
                                                        <th>Grand Total</th>
                                                        <th>Round Off</th>
                                                        <th>ItemCat</th>
                                                        <th>ItemName</th>
                                                        <th>HSN Code</th>
                                                        <th>UNIT</th>
                                                        <th>Qty</th>
                                                        <th>Rate</th>
                                                        <th>ItemDiscount</th>
                                                        <th>IGST Rate</th>
                                                        <th>SGST Rate</th>
                                                        <th>CGST Rate</th>
                                                        <th>IGST Value</th>
                                                        <th>SGST Value</th>
                                                        <th>CGST Value</th>
                                                        <th>BatchNo</th>
                                                        <th>Expiry</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $total_po_total = 0;
                                                    $total_po_discount = 0;
                                                    $total_po_freight = 0;
                                                    $total_po_round_off = 0;
                                                    $total_po_qty = 0;
                                                    $total_po_item_discount = 0;
                                                    $total_po_cgst = 0;
                                                    $total_po_sgst = 0;
                                                    $total_po_basic = 0;
                                                    if (isset($_POST['show'])) {
                                                        $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                        $date_2 = date("Y-m-d", strtotime($_POST['date_2']));

                                                        $po_no = $_POST['po_no'];
                                                        $po_type = $_POST['po_type'];
                                                        $item_code = $_POST['item_code'];
                                                        $query_po_type = "";
                                                        if ($po_type != 0) {
                                                            $query_po_type .= " AND c.po_type='$po_type'";
                                                        }
                                                        if ($item_code != '00') {
                                                            $query_po_type .= " AND b.item_id='$item_code'";
                                                        }
                                                        $where = "a.id = b.id AND a.id = c.`po_id` AND b.item_id = c.`item_desc` AND DATE(c.retailer_inwd_date) BETWEEN '$date_1' AND '$date_2' and c.retailer_inwd_flg='1' and a.company_id in ($company_id_in)" . $query_po_type;
                                                        if ($po_no !== 'all') {
                                                            $where .= " and  a.po_no = '$po_no'";
                                                        }
//                                                        ECHO $where;
//                                                        EXIT;
                                                        $podata = getPoDataByCondition($where);
                                                        $index = 1;
                                                        $temp_po = "A";
                                                        $grand_total = 0;
                                                        $freight = 0;
                                                        $po_discount = 0;
                                                        foreach ($podata as $data) {
//                                                            echo $temp_po . " | " . $data->po_no;
                                                            if ($temp_po != $data->po_no) {
//                                                                echo 'YES';
                                                                $grand_total = $data->grand_total;
                                                                $freight = $data->freight;
                                                                $po_discount = $data->discount;
                                                            } else {
//                                                                echo 'NO';
                                                                $grand_total = 0;
                                                                $freight = 0;
                                                                $po_discount = 0;
                                                            }
//                                                            echo '<br/>';
                                                            $temp_po = $data->po_no;
                                                            $main_category_id = getItemMainCategoryIdByItemCode($data->item_id);
                                                            $vendor_gstin = getVendorGstinNoById($data->vendor_id);
                                                            $vendor_gstin_2 = substr($vendor_gstin, 0, 2);

                                                            $company_gstin = getCompanyGSTINById($data->company_id);
                                                            $company_gstin_2 = substr($company_gstin, 0, 2);
                                                            $gst_rate = $data->gst_rate;
                                                            $gst_amount = $data->gst_amount;
                                                            if ($vendor_gstin_2 == $company_gstin_2) {
                                                                $igst_amount = 0;
                                                                $sgst_amount = $gst_amount / 2;
                                                                $cgst_amount = $gst_amount / 2;
                                                                $igst_rate = 0;
                                                                $gst_amount = $sgst_amount + $cgst_amount;
                                                                $sgst_rate = $gst_rate / 2;
                                                                $cgst_rate = $gst_rate / 2;
                                                            } else {
                                                                $igst_amount = $gst_amount;
                                                                $gst_amount = $igst_amount;
                                                                $sgst_amount = 0;
                                                                $cgst_amount = 0;
                                                                $igst_rate = $gst_rate;
                                                                $sgst_rate = 0;
                                                                $cgst_rate = 0;
                                                            }
                                                            if ($data->po_type == 1) {
                                                                $po_type_desc = "Purchase Order";
                                                            } else if ($data->po_type == 2) {
                                                                $po_type_desc = "Credit Note";
                                                            } else {
                                                                $po_type_desc = "";
                                                            }

                                                            $total_po_total = IND_money_format($total_po_total + round(round((($data->qty * $data->rate) + $gst_amount), 2) - $data->discount_amt + $freight - $po_discount, 2));
                                                            $total_po_discount = IND_money_format($total_po_discount + $po_discount);
                                                            $total_po_freight = IND_money_format($total_po_freight + $freight);
                                                            $total_po_round_off = IND_money_format($total_po_round_off + round(round(($data->qty * $data->rate) + $gst_amount) - round((($data->qty * $data->rate) + $gst_amount), 2), 2));
                                                            $total_po_qty = IND_money_format($total_po_qty + $data->qty);
                                                            $total_po_item_discount = IND_money_format($total_po_item_discount + $data->discount_amt);
                                                            $total_po_cgst = IND_money_format($total_po_cgst + $sgst_amount);
                                                            $total_po_sgst = IND_money_format($total_po_sgst + $sgst_amount);
                                                            $total_po_basic = IND_money_format($total_po_basic + round($data->qty * $data->rate, 2));
                                                            ?> 
                                                            <tr>
                                                                <td><?php echo $index; ?></td>
                                                                <td><?php echo $data->po_no; ?></td>
                                                                <td><?php echo $data->invoice_v_no; ?></td>
                                                                <td><?php echo date('Y-m-d', strtotime($data->po_date)); ?></td>
                                                                <td><?php echo date('Y-m-d', strtotime($data->invoice_date)); ?></td>
                                                                <td><?php echo date('Y-m-d', strtotime($data->retailer_inwd_date)); ?></td>
                                                                <td><?php echo $data->quotation_no; ?></td>
                                                                <td><?php echo date('Y-m-d', strtotime($data->quotation_date)); ?></td>
                                                                <td><?php echo $data->supplier_name; ?></td>
                                                                <td><?php echo getVendorGstinNoById($data->vendor_id); ?></td>
                                                                <td><?php echo getRetailerNameById($data->retailer_id); ?></td>
                                                                <td><?php echo $po_type_desc; ?></td>
                                                                <td><?php echo round(($data->qty * $data->rate) - $data->discount_amt, 2); ?></td>
                                                                <td><?php echo $po_discount; ?></td>
                                                                <td><?php echo $freight; ?></td>
                                                                <td><?php echo round(round((($data->qty * $data->rate) + $gst_amount), 2) - $data->discount_amt + $freight - $po_discount, 2); ?></td>
                                                                <td><?php echo round(round(($data->qty * $data->rate) + $gst_amount) - round((($data->qty * $data->rate) + $gst_amount), 2), 2); ?></td>
                                                                <td><?php echo getCategoryNameById($main_category_id); ?></td>
                                                                <td><?php echo getItemNameByItemCode($data->item_id); ?></td>
                                                                <td><?php echo getItemHSNCODEByItemCode($data->item_id); ?></td> 
                                                                <td><?php echo getItemUNITByItemCode($data->item_id); ?></td>
                                                                <td><?php echo $data->qty; ?></td>
                                                                <td><?php echo round($data->rate, 2); ?></td>
                                                                <td><?php echo $data->discount_amt; ?></td>
                                                                <td><?php echo $igst_rate; ?></td>
                                                                <td><?php echo $cgst_rate; ?></td>
                                                                <td><?php echo $sgst_rate; ?></td>
                                                                <td><?php echo round($igst_amount, 2); ?></td>
                                                                <td><?php echo round($sgst_amount, 2); ?></td>
                                                                <td><?php echo round($cgst_amount, 2); ?></td>
                                                                <td><?php echo ($data->batch_number) ? $data->batch_number : 0; ?></td>
                                                                <td><?php echo (!empty($data->expire_date)) ? date('Y-m-d', strtotime($data->expire_date)) : 'NA'; ?></td>
                                                            </tr>
                                                            <?php
                                                            $index++;
                                                        }
                                                    }
                                                    ?>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>   
                                                        <th></th>   
                                                        <th></th>   
                                                        <th></th>
                                                        <th><?php echo $total_po_basic; ?></th>
                                                        <th><?php echo $total_po_discount; ?></th>
                                                        <th><?php echo $total_po_freight; ?></th>
                                                        <th><?php echo $total_po_total; ?></th>
                                                        <th><?php echo $total_po_round_off; ?></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th><?php echo $total_po_qty; ?></th>
                                                        <th></th>
                                                        <th><?php echo $total_po_item_discount; ?></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th><?php echo $total_po_cgst; ?></th>
                                                        <th><?php echo $total_po_sgst; ?></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr> 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>
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

