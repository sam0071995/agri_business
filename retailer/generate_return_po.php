<?php
echo '<script>window.location.href="generate_return_po_new.php?menu=392";</script>';
exit;
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$fin_year = date('y') . '-' . date('y', strtotime('+1Year'));
if (ltrim(date('m')) > 3) {
    $cd = date('y');
    $dd = $cd + 1;
} else {
    $dd = date('y');
    $cd = $dd - 1;
}
$fin_year_latest = $cd . '' . $dd;

$inc_no = getLastpurchaseOrderIncNo($fin_year_latest, $_SESSION['id']);
if (isset($inc_no) && !empty($inc_no)) {
    $po_no_int = $inc_no;
} else {
    $po_no_int = 0;
}
$po_no_increase = $po_no_int + 1;
$vendor_id = "";
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
                                    <div class="align-right">
                                        <a href="generate_return_po_report.php?menu=392"><button class="btn btn-success">Report</button></a>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php
                                        if (isset($_POST['btn_submit'])) {
                                            $data = array();
                                            $data['po_no'] = $_POST['txt_po_no'];
                                            $data['inc_no'] = $po_no_increase;
                                            $data['po_date'] = date('Y-m-d', strtotime($_POST['txt_po_date']));
                                            $data['retailer_id'] = $_SESSION['id'];
                                            $data['vendor_id'] = $_POST['txt_vendor'];
                                            $data['supplier_id'] = getVendorNameById($_POST['txt_vendor']);
                                            $data['supplier_name'] = getVendorNameById($_POST['txt_vendor']);
                                            $data['supplier_contact_person'] = $_POST['txt_person'];
                                            $data['supplier_contact_no'] = $_POST['txt_number'];
                                            $data['supplier_address'] = $_POST['txt_address'];
                                            $data['user_id'] = $_SESSION['id'];
                                            // $data['company_id'] = getRetailerCompanyIdById($_POST['Retailer_id']);
                                            $data['quotation_no'] = $_POST['quotation_no'];
                                            $data['quotation_date'] = date('Y-m-d', strtotime($_POST['quotation_date']));
                                            $data['financial_yr'] = $fin_year_latest;
                                            $data['tot_qty'] = $_POST['total_qty'];
                                            $data['pnf'] = $_POST['txt_pf'];
                                            $data['net_total'] = $_POST['txt_nettotal'];
                                            $data['cgst_per'] = $_POST['txt_cgst_per'];
                                            $data['cgst_amt'] = $_POST['txt_tot_cgst'];
                                            $data['sgst_per'] = $_POST['txt_sgst_per'];
                                            $data['sgst_amt'] = $_POST['txt_tot_sgst'];
                                            $data['freight'] = $_POST['txt_freight'];
                                            $data['grand_total'] = $_POST['txt_grandTotal'];

                                            //                                            echo '<pre/>';
                                            //                                            print_r($_POST);
                                            //                                            exit;

                                            if (isset($_POST['txt_remarks']) && !empty($_POST['txt_remarks'])) {
                                                $data['remarks'] = $_POST['txt_remarks'];
                                            }

                                            $data['sub_total'] = $_POST['txt_subTotal'];
                                            $data['amount'] = $_POST['txt_amt'];
                                            if (isset($_POST['txt_term_delivery']) && !empty($_POST['txt_term_delivery'])) {
                                                $data['term_delivery'] = $_POST['txt_term_delivery'];
                                            }
                                            if (isset($_POST['txt_term_payment']) && !empty($_POST['txt_term_payment'])) {
                                                $data['term_payment'] = $_POST['txt_term_payment'];
                                            }
                                            $table_name = "purchase_order_return";
                                            $pur_id = $_POST['pur_id'];
                                            $where = "id='$pur_id'";

                                            $reslt = ($_POST['frm_type'] == "new") ? insert($table_name, $data) : update($table_name, $data, $where);
                                            if ($reslt) {
                                                $where = "id='$pur_id'";
                                                ($_POST['frm_type'] == "new") ? "" : delete('purchase_order_return_detail', $where);
                                                $i = 0;
                                                $j = 0;

                                                $table_name_detail = "purchase_order_return_detail";
                                                for ($j = 1; $j <= $_POST['pur_count']; $j++) {
                                                    if (isset($_POST[($j * 1000 + 1)])) {
                                                        if ($pur_id == '' && empty($pur_id)) {
                                                            $lst_po_id = getLastpurchaseOrderId();
                                                            $data_detail["id"] = getLastpurchaseOrderId();
                                                        } else {
                                                            $lst_po_id = $pur_id;
                                                            $data_detail["id"] = $pur_id;
                                                        }
                                                        $data_detail["item_id"] = $_POST[($j * 1000 + 1)];
                                                        $data_detail["qty"] = $_POST[($j * 1000 + 4)];
                                                        $data_detail["rate"] = $_POST[($j * 1000 + 5)];
                                                        $data_detail["total_basic"] = numberDecimal($_POST[($j * 1000 + 4)] * $_POST[($j * 1000 + 5)]);
                                                        $data_detail["amount"] = $_POST[($j * 1000 + 6)];
                                                        $data_detail["delivery_date"] = date('Y-m-d', strtotime($_POST[($j * 1000 + 7)]));
                                                        $pur_det_insert = insert($table_name_detail, $data_detail);

                                                        $current_stock = getCurrentStockByRetailerIdAndItemCode($_SESSION['id'], $_POST[($j * 1000 + 1)]);
                                                        $issued_stock = getIssuedStockByRetailerIdAndItemCode($_SESSION['id'], $_POST[($j * 1000 + 1)]);

                                                        $stck_arr = array();
                                                        $stck_arr['issued_stock'] = ($issued_stock + $_POST[($j * 1000 + 4)]);
                                                        $stck_arr['current_stock'] = ($current_stock - $_POST[($j * 1000 + 4)]);
                                                        $stckwhr = "item_code = '" . $_POST[($j * 1000 + 1)] . "' and retailer_id = '" . $_SESSION['id'] . "'";
                                                        $updstck = update('retailer_inventory_master', $stck_arr, $stckwhr);

                                                        $hiss_arr = array();
                                                        $hiss_arr['item_code'] = $_POST[($j * 1000 + 1)];
                                                        $hiss_arr['retailer_id'] = $_SESSION['id'];
                                                        $hiss_arr['po_id'] = $lst_po_id;
                                                        $hiss_arr['po_no'] = $_POST['txt_po_no'];
                                                        $hiss_arr['qty'] = $_POST[($j * 1000 + 4)];
                                                        $hiss_arr['current_stock'] = $current_stock;
                                                        $hiss_arr['issued_stock'] = $issued_stock;
                                                        $hiss_arr['update_date'] = date('Y-m-d H:i:s');
                                                        $hiss_arr['status'] = 0;
                                                        insert('purchase_order_return_stock_history', $hiss_arr);
                                                    }
                                                }
                                                echo '<script>alert("Return PO Saved");window.location.href="generate_return_po.php?menu=40&success=1";</script>';
                                            } else {
                                                echo '<script>window.location.href="generate_return_po.php?menu=40&error=1";</script>';
                                            }
                                        }
                                        if (isset($_GET['purchase_id'])) {
                                            $x_id = base64_decode($_GET['purchase_id']);
                                            $r4 = getPurchaseOrdergetItemCountById($x_id);
                                            $vendor_id = $r4->vendor_id;
                                            $ItemCount = getItemCount($r4->id);
                                        } else {
                                            $pur_count = 0;
                                        }
                                        $get_retailer_id = 0;
                                        if (isset($r4->po_no)) {
                                            $get_retailer_id = $r4->retailer_id;
                                        }
                                        ?>
                                        <div>
                                            <form class="form-horizontal" method="post" action="" id="pur_entry">
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000">
                                                    <tr>
                                                        <td colspan="4" align="center"><i>
                                                                <font color="#336633" size="+3">Return Purchase Goods Order </font>
                                                            </i></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" align="center">==============================================================================</td>
                                                    </tr>
                                                    <!--readonly="readonly"-->
                                                    <tr align="center">
                                                        <th align="left">P.O. No. <br>
                                                            <input name='txt_po_no' id="txt_po_no" class="text" type="hidden" style="height:30px" value="<?php
                                                            if (!isset($x_id))
                                                                echo "PORET" . $_SESSION['id'] . "" . $fin_year_latest . "" . $po_no_increase;
                                                            else
                                                                echo $r4->po_no;
                                                            ?>">
                                                            <b class="red"><?php
                                                                if (!isset($x_id))
                                                                    echo "PORET" . $_SESSION['id'] . "" . $fin_year_latest . "" . $po_no_increase;
                                                                else
                                                                    echo $r4->po_no;
                                                                ?></b>
                                                        </th>

                                                        <th align="left">P.O. Date <br>
                                                            <input type="text" class="date-picker" id="id-date-picker-1" name="txt_po_date" value="<?php
                                                            if (!isset($x_id))
                                                                echo date('d-m-Y');
                                                            else
                                                                echo date('d-m-Y', strtotime($r4->po_date));
                                                            ?>" />
                                                        </th>
                                                        <th align="left">Supplier Name <br>
                                                            <select name="txt_vendor" id="txt_vendor" class="text" onchange="getVendorDetails();">
                                                                <option value="">--select vendor--</option>
                                                                <?php foreach (getVendorActiveDetails() as $vendor) { ?>
                                                                    <option value="<?php echo $vendor->vendor_id; ?>" <?php
                                                                    if ($vendor_id == $vendor->vendor_id) {
                                                                        echo 'selected="elected"';
                                                                    }
                                                                    ?>><?php echo $vendor->vendor_name; ?></option>
                                                                        <?php } ?>
                                                            </select>
                                                            <!--                                                            <input type="text" name="txt_vendor" class="text" value="<?php
                                                            //                                                            if (isset($r4->po_no)) {
                                                            //                                                                echo $r4->supplier_name;
                                                            //                                                            }
                                                            ?>"/>-->
                                                        </th>
                                                    </tr>
                                                    <tr id="vendor_details">
                                                    <div id="div_detail"></div>
                                                    <th align="left">Supplier Contact Person<br>
                                                        <input type="text" id="txt_person" name="txt_person" value="<?php
                                                        if (isset($r4->po_no)) {
                                                            echo $r4->supplier_contact_person;
                                                        }
                                                        ?>" class="text" readonly="readonly" style="width:90%; height:30px" />
                                                    </th>
                                                    <th align="left">Supplier Contact Number<br>
                                                        <input type="text" id="txt_number" name="txt_number" value="<?php
                                                        if (isset($r4->po_no)) {
                                                            echo $r4->supplier_contact_no;
                                                        }
                                                        ?>" class="text" readonly="readonly" style="width:90%; height:30px" />
                                                    </th>
                                                    <th align="left" colspan="2">Supplier Address<br>
                                                        <textarea name='txt_address' readonly="readonly" id="txt_address" style="width:80%;" rows='3'><?php
                                                            if (isset($r4->po_no)) {
                                                                echo $r4->supplier_address;
                                                            }
                                                            ?></textarea>
                                                    </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                        </td>
                                                    </tr>
                                                </table>

                                                <!------------Add Item------------>
                                                <table align="center" border="0" width="100%" bgcolor="#ccc" bordercolor="#000000">
                                                    <tr>
                                                        <th>Item<br>
                                                            <select class="chosen-select" name="txt_item" id="txt_item" onchange="item_unit();" style="height:30px;width:200px;">
                                                                <option value='0'>-- SELECT ITEM --</option>
                                                                <?php
                                                                foreach (getActiveItemsList() as $row) {
                                                                    echo "<option value='$row->item_code'>$row->item_desc</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </th>
                                                        <th>SKU<br>
                                                            <input type="text" id="txt_sku" name="txt_sku" class="text" style="text-align:center; height:30px" value="" readonly="readonly" placeholder="Unit">
                                                        </th>
                                                        <th>QTY<br>
                                                            <input type="text" id="txt_qty" name="txt_qty" class="text" value="0" onchange="cal_item_detail()">
                                                        </th>
                                                        <th>Unit Price<br>
                                                            <input type="text" id="txt_price" name="txt_price" class="text" value="0" onchange="cal_item_detail()">
                                                        </th>
                                                        <th>Net Amount<br>
                                                            <input type="text" id="txt_total" name="txt_total" class="text" value="0" readonly="readonly">
                                                        </th>
                                                        <th>Return Date<br>
                                                            <input type="date" id="delivry_date" name="delivry_date" class="text" value="<?php echo date('Y-m-d'); ?>">
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th align="left"><br>
                                                            <input type="button" value="Add Item" onclick="addRow()" id="btn_add" class="button btn btn-primary" style="" />
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                            <hr />
                                                        </td>
                                                    </tr>
                                                </table>

                                                <!------------List of Items------------>
                                                <table border="0" width="100%" bgcolor="#d2b4bc" bordercolor="#000000" style="margin-top:3%" id="pur_detail">

                                                    <tr>
                                                        <td colspan="6" align="center"><i><u>
                                                                    <font color="#336633" size="+2">List of Items</font>
                                                                </u></i></td>
                                                    </tr>

                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                            <hr />
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <!-- <th align="left">Item ID</th> -->
                                                        <th></th>
                                                        <th align="left">Item</th>
                                                        <th align="left">SKU</th>
                                                        <th align="left">QTY</th>
                                                        <th align="left">Unit Price</th>
                                                        <th align="left">Total Amount</th>
                                                        <th align="left">Delivery Date</th>
                                                        <th align="left">Action</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        if (isset($x_id)) {
                                                            $i = 1;
                                                            if (count(getPurchaseOrderDetailsByPurchasId($x_id)) > 0) {
                                                                foreach (getPurchaseOrderDetailsByPurchasId($x_id) as $r5) {
                                                                    echo "
                                    <tr id='$i'>
                                    <td></td>
                                        <td id=" . ($i * 100 + 1) . ">" . getItemNameByItemCode($r5->item_id) . " <input type='hidden' id=" . ($i * 1000 + 1) . " name=" . ($i * 1000 + 1) . " value=" . $r5->item_id . "></td>
                                        <td id=" . ($i * 100 + 3) . ">" . getItemUOMByItemCode($r5->item_id) . " <input type='hidden' id=" . ($i * 1000 + 3) . " name=" . ($i * 1000 + 3) . " value=" . getItemUOMByItemCode($r5->item_id) . "></td>
                                        <td id=" . ($i * 100 + 4) . ">" . $r5->qty . " <input type='hidden' id=" . ($i * 1000 + 4) . " name=" . ($i * 1000 + 4) . " value=" . $r5->qty . "></td>
                                        <td id=" . ($i * 100 + 5) . ">" . $r5->rate . " <input type='hidden' id=" . ($i * 1000 + 5) . " name=" . ($i * 1000 + 5) . " value=" . $r5->rate . "></td>
                                        <td id=" . ($i * 100 + 6) . ">" . $r5->amount . " <input type='hidden' id=" . ($i * 1000 + 6) . " name=" . ($i * 1000 + 6) . " value=" . $r5->amount . "></td>
                                        <td id=" . ($i * 100 + 7) . ">" . $r5->delivery_date . " <input type = 'hidden' id = " . ($i * 1000 + 7) . " name = " . ($i * 1000 + 7) . " value = " . $r5->delivery_date . "></td>
                                                                   <td> <button class = 'btn btn-danger btn-xs' onclick = 'javascript: del_purchase($i)'>Delete</button>
                                                                    </td>
                                                                    </tr>
                                                                    ";
                                                                    $i++;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </tr>

                                                </table>

                                                <!------------Calculation of items------------>
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000" style="margin-top:1%">
                                                    <tr>
                                                        <th>QTY<br>
                                                            <input type="text" id="total_qty" name="total_qty" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->tot_qty;
                                                            else
                                                                echo "0";
                                                            ?>" style="text-align:center; width:50%; height:30px" readonly>
                                                        </th>
                                                        <th>Sub Total<br>
                                                            <input type="text" style="text-align:center; width:65%; height:30px" id="txt_subTotal" name="txt_subTotal" onchange="cal_net_amt()" readonly="readonly" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->sub_total;
                                                            else
                                                                echo "0";
                                                            ?>">
                                                        </th>
                                                        <th>P & F<br>
                                                            <input type="text" style="text-align:center; width:50%; height:30px" id="txt_pf" name="txt_pf" class="text" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->pnf;
                                                            else
                                                                echo "0";
                                                            ?>" onchange="cal_net_amt()">
                                                        </th>
                                                        <th>Net Total<br>
                                                            <input type="text" id="txt_nettotal" name="txt_nettotal" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->net_total;
                                                            else
                                                                echo "0";
                                                            ?>" readonly="readonly" style="text-align:center; width:70%; height:30px">
                                                        </th>
                                                        <th>CGST (%)<br>
                                                            <select name="txt_cgst_per" id="txt_cgst_per" style="width:35%; height:30px" onchange="cal_net_amt()">
                                                                <?php
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 0) {
                                                                    echo "<option value='0' selected='selected'>0</option>";
                                                                } else {
                                                                    echo "<option value='0'>0</option>";
                                                                }
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 2.5) {
                                                                    echo "<option value='2.5' selected='selected'>5</option>";
                                                                } else {
                                                                    echo "<option value='2.5'>2.5</option>";
                                                                }
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 6) {
                                                                    echo "<option value='6' selected='selected'>6</option>";
                                                                } else {
                                                                    echo "<option value='6'>6</option>";
                                                                }
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 9) {
                                                                    echo "<option value='9' selected='selected'>9</option>";
                                                                } else {
                                                                    echo "<option value='9'>9</option>";
                                                                }
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 14) {
                                                                    echo "<option value='14' selected='selected'>14</option>";
                                                                } else {
                                                                    echo "<option value='14'>14</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            <input type="text" id="txt_tot_cgst" readonly="readonly" name="txt_tot_cgst" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->cgst_amt;
                                                            else
                                                                echo "0";
                                                            ?>" style="width:50%;text-align:center; height:30px" onchange="cal_net_amt()">
                                                        </th>
                                                        <th>SGST (%)<br>
                                                            <select name="txt_sgst_per" id="txt_sgst_per" style="width:35%; height:30px" onchange="cal_net_amt()">
                                                                <?php
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 0) {
                                                                    echo "<option value='0' selected='selected'>0</option>";
                                                                } else {
                                                                    echo "<option value='0'>0</option>";
                                                                }
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 2.5) {
                                                                    echo "<option value='2.5' selected='selected'>2.5</option>";
                                                                } else {
                                                                    echo "<option value='2.5'>2.5</option>";
                                                                }
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 6) {
                                                                    echo "<option value='6' selected='selected'>6</option>";
                                                                } else {
                                                                    echo "<option value='6'>6</option>";
                                                                }
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 9) {
                                                                    echo "<option value='9' selected='selected'>9</option>";
                                                                } else {
                                                                    echo "<option value='9'>9</option>";
                                                                }
                                                                if (isset($r4->cgst_per) && $r4->cgst_per == 14) {
                                                                    echo "<option value='14' selected='selected'>14</option>";
                                                                } else {
                                                                    echo "<option value='14'>14</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            <input type="text" id="txt_tot_sgst" readonly="readonly" name="txt_tot_sgst" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->sgst_amt;
                                                            else
                                                                echo "0";
                                                            ?>" style="width:50%;text-align:center; height:30px" onchange="cal_net_amt()">
                                                        </th>

                                                        <th>Amount<br>
                                                            <input type="text" id="txt_amt" name="txt_amt" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->amount;
                                                            else
                                                                echo "0";
                                                            ?>" readonly="readonly" onchange="cal_net_amt()" style="text-align:center; width:65%; height:30px">
                                                        </th>

                                                        <th>Freight<br>
                                                            <input type="text" id="txt_freight" name="txt_freight" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->freight;
                                                            else
                                                                echo "0";
                                                            ?>" onchange="cal_net_amt()" style="text-align:center; width:50%; height:30px">
                                                        </th>
                                                        <th>Grand Total<br>
                                                            <input type="text" id="txt_grandTotal" name="txt_grandTotal" value="<?php
                                                            if (isset($x_id))
                                                                echo $r4->grand_total;
                                                            else
                                                                echo "0";
                                                            ?>" readonly="readonly" style="text-align:center; width:70%; height:30px">
                                                        </th>
                                                    </tr>
                                                </table>
                                                <input name="pur_count" id="pur_count" type="hidden" value="<?php
                                                if (!isset($x_id))
                                                    echo "0";
                                                else
                                                    echo $ItemCount;
                                                ?>" />

                                                <!------------Calculation of items------------>
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000">
                                                    <tr>
                                                        <th align="left">
                                                            Quotation No & Date: <br />
                                                            <input type="text" id="" name="quotation_no" required="required" placeholder="Quotation No" style="width:30%; height:30px" value="<?php
                                                            if (isset($x_id)) {
                                                                echo $r4->quotation_no;
                                                            } else {
                                                                echo "";
                                                            }
                                                            ?>">
                                                            <input type="text" class="date-picker" id="id-date-picker-1" required="required" name="quotation_date" value="<?php
                                                            if (isset($x_id) && !empty($r4->quotation_date)) {
                                                                echo date('d-m-Y', strtotime($r4->quotation_date));
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>">
                                                            <br />
                                                            Terms of Delivery : <br />
                                                            <input type="text" id="" name="txt_term_delivery" required="required" style="width:100%; height:30px" value="<?php
                                                            if (isset($x_id)) {
                                                                echo $r4->term_delivery;
                                                            } else {
                                                                echo "FREE DOOR DELIVERY AT OUR FACTORY WITHIN 7 DAYS.";
                                                            }
                                                            ?>"><br />
                                                            Terms of payment : <br />
                                                            <input type="text" id="" name="txt_term_payment" required="required" style="width:100%; height:30px" value="<?php
                                                            if (isset($x_id)) {
                                                                echo $r4->term_payment;
                                                            } else {
                                                                echo "WITHIN 30 DYS.";
                                                            }
                                                            ?>"><br />
                                                            Remarks<br>
                                                            <textarea class="remark" required="required" id="txt_remarks" name="txt_remarks"><?php
                                                                if (isset($x_id)) {
                                                                    echo $r4->remarks;
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?></textarea>
                                                        </th>
                                                        <th>
                                                            <input name="frm_type" id="frm_type" type="hidden" value="<?php
                                                            if (!isset($x_id)) {
                                                                echo "new";
                                                            } else {
                                                                echo 'edit';
                                                            }
                                                            ?>" />
                                                            <input name="pur_id" type="hidden" value="<?php
                                                            if (isset($x_id)) {
                                                                echo $x_id;
                                                            }
                                                            ?>" />
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                            <hr />
                                                            <!--<iframe name="bopg" align="middle" frameborder="0" width='100%' height="10px" src=""></iframe>-->
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="">
                                                            <input type="submit" class="button btn btn-primary" <?php if (!isset($x_id)) { ?>value="Save." id="btn_submit" <?php } else { ?>value="Save " <?php } ?> name="btn_submit" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                            <div id="vendorData"></div>
                                        </div>
                                    </div>
                                </div><!-- /.row -->
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">
                show_detail();

                function new_purchase_click() {
                    document.getElementById("purchase_detail").form_type.value = "new";
                    document.getElementById("purchase_detail").purchase_id.value = "";
                    document.purchase_detail.submit();

                }

                function released_purchase_click() {
                    window.location.href = "release_purchase_order_list.php?menu=11";
                }

                function edit_purchase(x) {
                    document.getElementById("purchase_detail").form_type.value = "edit";
                    document.getElementById("purchase_detail").purchase_id.value = x;
                    document.purchase_detail.submit();
                }


                function getVendorDetails() {
                    document.getElementById("txt_person").value = "";
                    document.getElementById("txt_number").value = "";
                    document.getElementById("txt_address").value = "";
                    var vendor_id = document.getElementById('txt_vendor').value;
                    if (vendor_id != '') {
                        $.ajax({
                            type: "POST",
                            url: "ajax_js.php",
                            data: {
                                'request_type': 'getVendorDetils',
                                'vendor_id': vendor_id
                            },
                            success: function (data) {
                                $("#vendorData").html(data);
                            }
                        });
                    }
                }

                function item_unit() {
                    var str = document.getElementById('txt_item').value;
                    var res = str.split("(^)");

                    document.getElementById("div_detail").innerHTML = "";

                    $.ajax({
                        type: "POST",
                        url: "ajax_js.php",
                        data: {
                            'request_type': 'getSkuDetails',
                            id: res[0]
                        },
                        success: function (data) {
                            $("#txt_sku").val(data);
                        }
                    });

                    // document.getElementById("txt_sku").value = document.getElementById("ajax_sku").value;
                }

                function addRow() {
                    cal_item_detail();

                    if (document.getElementById("txt_item").value != "") {
                        if (isNaN(document.getElementById("txt_qty").value)) {
                            document.getElementById("txt_qty").focus();
                        } else if (Number(document.getElementById("txt_qty").value) <= 0) {
                            document.getElementById("txt_qty").focus();
                        } else if (isNaN(document.getElementById("txt_price").value)) {
                            document.getElementById("txt_price").focus();
                        } else if (Number(document.getElementById("txt_price").value) < 0) {
                            document.getElementById("txt_price").focus();
                        }

                        //txt_item		txt_sku		txt_qty		txt_price		txt_total
                        else {

                            var str = document.getElementById('txt_item').value;
                            var item_name = document.getElementById('txt_item').options[document.getElementById('txt_item').selectedIndex].text;
                            var res = str.split("(^)");
                            // console.log(item_name);
                            var table = document.getElementById("pur_detail");
                            var rowcount = table.rows.length; //get table row count

                            if (table.rows[table.rows.length - 1].id == "")
                                rowid = 1;
                            else
                                rowid = Number(table.rows[table.rows.length - 1].id) + 1;

                            var row = table.insertRow(rowcount);

                            row.id = rowid;

                            //item id
                            var cell1 = row.insertCell(0);
                            cell1.id = (rowid) * 100 + 1;
                            // cell1.innerHTML = res[0];
                            var element1 = document.createElement("input");
                            element1.id = (rowid) * 1000 + 1;
                            element1.name = (rowid) * 1000 + 1;
                            element1.size = "1";
                            element1.value = res[0];
                            element1.style.visibility = "hidden";
                            cell1.appendChild(element1);

                            //item nameFTA HSRP Solutions Pvt. Ltd.
                            var cell2 = row.insertCell(1);
                            cell2.id = (rowid) * 100 + 2;
                            // cell2.innerHTML = res[1];
                            cell2.innerHTML = item_name;
                            var element2 = document.createElement("input");
                            element2.id = (rowid) * 1000 + 2;
                            element2.name = (rowid) * 1000 + 2;
                            element2.size = "1";
                            // element2.value = res[1];
                            element2.value = item_name;
                            element2.style.visibility = "hidden";
                            cell2.appendChild(element2);


                            //txt_sku
                            var cell3 = row.insertCell(2);
                            cell3.id = (rowid) * 100 + 3;
                            cell3.innerHTML = document.getElementById("txt_sku").value;
                            var element3 = document.createElement("input");
                            element3.id = (rowid) * 1000 + 3;
                            element3.name = (rowid) * 1000 + 3;
                            element3.size = "1";
                            element3.value = document.getElementById("txt_sku").value;
                            element3.style.visibility = "hidden";
                            cell3.appendChild(element3);

                            //txt_qty
                            var cell4 = row.insertCell(3);
                            cell4.id = (rowid) * 100 + 4;
                            cell4.innerHTML = document.getElementById("txt_qty").value;
                            var element4 = document.createElement("input");
                            element4.id = (rowid) * 1000 + 4;
                            element4.name = (rowid) * 1000 + 4;
                            element4.size = "1";
                            element4.value = document.getElementById("txt_qty").value;
                            element4.style.visibility = "hidden";
                            cell4.appendChild(element4);

                            //txt_price
                            var cell5 = row.insertCell(4);
                            cell5.id = (rowid) * 100 + 5;
                            cell5.innerHTML = document.getElementById("txt_price").value;
                            var element5 = document.createElement("input");
                            element5.id = (rowid) * 1000 + 5;
                            element5.name = (rowid) * 1000 + 5;
                            element5.size = "1";
                            element5.value = document.getElementById("txt_price").value;
                            element5.style.visibility = "hidden";
                            cell5.appendChild(element5);

                            //txt_total
                            var cell6 = row.insertCell(5);
                            cell6.id = (rowid) * 100 + 6;
                            cell6.innerHTML = document.getElementById("txt_total").value;
                            var element6 = document.createElement("input");
                            element6.id = (rowid) * 1000 + 6;
                            element6.name = (rowid) * 1000 + 6;
                            element6.size = "1";
                            element6.value = document.getElementById("txt_total").value;
                            element6.style.visibility = "hidden";
                            cell6.appendChild(element6);

                            //txt_total
                            var cell8 = row.insertCell(6);
                            cell8.id = (rowid) * 100 + 7;
                            cell8.innerHTML = document.getElementById("delivry_date").value;
                            var element7 = document.createElement("input");
                            element7.id = (rowid) * 1000 + 7;
                            element7.name = (rowid) * 1000 + 7;
                            element7.size = "1";
                            element7.value = document.getElementById("delivry_date").value;
                            element7.style.visibility = "hidden";
                            cell8.appendChild(element7);


                            var cell7 = row.insertCell(7);
                            cell7.id = (rowid) * 100 + 8;
                            cell7.innerHTML = "<button class='btn btn-danger btn-xs' onclick='javascript: del_purchase(" + rowid + ")'>Delete</button>";


                            document.getElementById("total_qty").value = ((document.getElementById("total_qty").value == "") ? Number(document.getElementById("txt_qty").value) : Number(document.getElementById("txt_qty").value) + Number(document.getElementById("total_qty").value));

                            document.getElementById("txt_subTotal").value = ((document.getElementById("txt_subTotal").value == "") ? Number(document.getElementById("txt_total").value) : Number(document.getElementById("txt_total").value) + Number(document.getElementById("txt_subTotal").value));

                            document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));






                            document.getElementById("txt_grandTotal").value = Math.round((document.getElementById("txt_nettotal").value == "") ? Number("0") : Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_tot_cgst").value) + Number(document.getElementById("txt_tot_sgst").value) + Number(document.getElementById("txt_freight").value));

                            //txt_item		txt_sku		txt_qty		txt_price		txt_total
                            //total_qty		txt_pf		txt_nettotal	txt_cgst_per	txt_tot_cgst	txt_sgst_per
                            //txt_tot_sgst		txt_freight		txt_grandTotal	txt_amt

                            //Add to all total
                            //txt_nettotal		txt_tot_cgst		txt_tot_sgst		txt_freight															
                            document.getElementById("txt_item").value = "";
                            document.getElementById("txt_sku").value = "";
                            document.getElementById("txt_qty").value = "";
                            document.getElementById("txt_price").value = "";
                            document.getElementById("txt_total").value = "";
                            document.getElementById("pur_count").value = rowcount;

                            document.getElementById("txt_item").focus();

                        }
                    } else
                        document.getElementById("txt_item").focus();
                }

                function cal_item_detail() {
                    if (document.getElementById("txt_qty").value == "")
                        document.getElementById("txt_qty").value = "0";
                    else if (isNaN(document.getElementById("txt_qty").value))
                        document.getElementById("txt_qty").value = "0";

                    if (document.getElementById("txt_price").value == "")
                        document.getElementById("txt_price").value = "0";
                    else if (isNaN(document.getElementById("txt_price").value))
                        document.getElementById("txt_price").value = "0";

                    document.getElementById("txt_total").value = (Number(document.getElementById("txt_qty").value) * Number(document.getElementById("txt_price").value)).toFixed(2);
                }

                function del_purchase(x) {
                    cal_item_detail();
                    var row = document.getElementById(x);
                    if (document.getElementById("txt_pf").value == "")
                        document.getElementById("txt_pf").value = "0";
                    if (document.getElementById("txt_freight").value == "")
                        document.getElementById("txt_freight").value = "0";

                    document.getElementById("total_qty").value = Number(document.getElementById("total_qty").value) - Number(document.getElementById(x * 1000 + 4).value);
                    document.getElementById("txt_subTotal").value = Number(document.getElementById("txt_subTotal").value) - Number(document.getElementById(x * 1000 + 6).value);


                    document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));


                    document.getElementById("txt_tot_cgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;
                    document.getElementById("txt_tot_sgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_sgst_per").value)) / 100;

                    document.getElementById("txt_amt").value = (Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_tot_cgst").value) + Number(document.getElementById("txt_tot_sgst").value)).toFixed(2);

                    document.getElementById("txt_grandTotal").value = (Number(document.getElementById("txt_amt").value) + Number(document.getElementById("txt_freight").value)).toFixed(2);


                    row.parentNode.removeChild(row);

                    var table = document.getElementById("pur_detail");
                    var rowcount = table.rows.length; //get table row count
                    document.getElementById("pur_count").value = rowcount - 1;
                }

                function cal_net_amt() {
                    if (document.getElementById("txt_pf").value == "")
                        document.getElementById("txt_pf").value = "0";
                    if (document.getElementById("txt_freight").value == "")
                        document.getElementById("txt_freight").value = "0";

                    document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));


                    document.getElementById("txt_tot_cgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;
                    document.getElementById("txt_tot_sgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_sgst_per").value)) / 100;

                    document.getElementById("txt_amt").value = (Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_tot_cgst").value) + Number(document.getElementById("txt_tot_sgst").value)).toFixed(2);

                    document.getElementById("txt_grandTotal").value = (Number(document.getElementById("txt_amt").value) + Number(document.getElementById("txt_freight").value)).toFixed(2);

                    //document.getElementById("txt_total").value=(Number(document.getElementById("txt_qty").value)*Number(document.getElementById("txt_price").value)).toFixed(2);
                }

                $('#btn_submit1').click(function () {
                    cal_net_amt();

                    if ($("#pur_count").value == "0") {
                        alert("Select Item");
                        $('#txt_vendor').focus();
                        return false;
                    }

                    if ($('#txt_vendor').val() == '0') {
                        alert("Select vendor");
                        $('#txt_vendor').focus();
                        return false;
                    } else if ($('#txt_item').val() == '0') {
                        alert("Select item");
                        $('#txt_item').focus();
                        return false;
                    } else if ($('#txt_qty').val() == '') {
                        alert("Enter qty");
                        $('#txt_qty').focus();
                        return false;
                    } else if ($('#txt_price').val() == '') {
                        alert("Enter unit price");
                        $('#txt_price').focus();
                        return false;
                    }
                })

                $(document).ready(function () {
                    $(window).keydown(function (event) {
                        if (event.keyCode == 13) {
                            event.preventDefault();
                            return false;
                        }
                    });

                });
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>