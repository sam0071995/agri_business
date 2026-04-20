<?php
include './includes/session.php';
include './includes/db_slave.class';

$bdd = new dbSlave();
$purchase_item = '';
$def_item = '';
$order_item_flg = '';
$active = '';
$ex_in_status = '';
$item_type = '';
$item_uom = '';
$status = '';
if (isset($_GET['item_code'])) {
    $item_code = $_GET['item_code'];
    $itemData = $bdd->getItemByItemCodeDetails(base64_decode($item_code));
    $order_item_flg = $itemData->order_item_flg;
    $def_item = $itemData->def_flag;
    $active = $itemData->active;
    $ex_in_status = $itemData->ex_in_status;
    $item_type = $itemData->item_type;
    $item_uom = $itemData->uom;
    $group_id = $itemData->group_id;
    $status = $itemData->status;

    $total1 = ($itemData->sgst_rate + $itemData->cgst_rate) * $itemData->basic_price / 100;
    $total = $itemData->total;
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
                                <h3 class="form-header">New Inventory Items.</h3>

                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="add_item_validation.php?menu=1" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Item Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="item" placeholder="Item Name" id="item" class="form-control" required="required" value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->item_desc;
                                            }
                                            ?>" />
                                                   <?php if (isset($_GET['item_code'])) { ?>
                                                <input type="hidden" id="item_code_id" name="item_id" value="<?php
                                                if (isset($_GET['item_code'])) {
                                                    echo $itemData->id;
                                                }
                                                ?>"><?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> HSN Code<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <?php
//                                            if (isset($_GET['item_code'])) {
//                                                $readonly = "readonly='readonly'";
//                                            } else {
//                                                $readonly = " ";
//                                            }
                                            ?>
                                            <input type="text" name="hsn_code" id="hsn_code" placeholder="Enter HSN Code" class="input_class form-control" required="required" <?php echo $readonly; ?> value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->hsn_code;
                                            }
                                            ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Opening Stock<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="opening_stock" id="opening_stock" placeholder="Item Opening Stock" class="input_class form-control" required="required" value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->opening_stock;
                                            } else {
                                                echo '0';
                                            }
                                            ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Receipts<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="total_stock" id="total_stock" placeholder="Item Receipts" class="input_class form-control" required="required" value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->total_stock;
                                            } else {
                                                echo '0';
                                            }
                                            ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Issued Stock<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="issued_stock" id="issued_stock" placeholder="Item Issued Stock" class="input_class form-control" required="required" value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->issued_stock;
                                            } else {
                                                echo '0';
                                            }
                                            ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Item Current Stock<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="current_stock" id="current_stock" placeholder="Item Code" class="input_class form-control" required="required" value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->current_stock;
                                            } else {
                                                echo '0';
                                            }
                                            ?>" />
                                        </div>
                                    </div>

                                    <!--//price-->
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Basic Price<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="basic_price" id="basic_price" placeholder="Basic Price" class="input_class form-control" required="required" value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->basic_price;
                                            } else {
                                                echo '0';
                                            }
                                            ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> SGST<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">

                                            <select class="chosen-select form-control" id="sgst_value" onchange="sgst(this.value)" name="sgst_value" data-placeholder="Choose a SGST...">
                                                <option value="">-- Select SGST ---</option>
                                                <option value="0" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->sgst_rate == '0') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>0</option>
                                                <option value="2.5" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->sgst_rate == '2.5') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>2.5</option>
                                                <option value="6" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->sgst_rate == '6') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>6</option>
                                                <option value="9" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->sgst_rate == '9') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>9</option>
                                                <option value="14" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->sgst_rate == '14') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>14</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> CGST<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">

                                            <select class="chosen-select form-control" id="cgst_value" onchange="cgst(this.value)" name="cgst_value" 
                                                    data-placeholder="Choose a SGST...">
                                                <option value="">-- Select CGST ---</option>
                                                <option value="0" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->cgst_rate == '0') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>0</option>
                                                <option value="2.5" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->cgst_rate == '2.5') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>2.5</option>
                                                <option value="6" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->cgst_rate == '6') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>6</option>
                                                <option value="9" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->cgst_rate == '9') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>9</option>
                                                <option value="14" <?php
                                                if (isset($_GET['item_code'])) {
                                                    if ($itemData->cgst_rate == '14') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>14</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> TOTAL<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="total" id="total" placeholder="Total" class="input_class form-control" required="required" value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $total;
                                            } else {
                                                echo '0';
                                            }
                                            ?>" />
                                        </div>
                                    </div>
                                    <!--//price end-->

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Location : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="location" id="location" placeholder="Enter Location" class="input_class form-control"  value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->location;
                                            }
                                            ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Serial/Lot No : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="serial_no" id="serial_no" placeholder="Serial/Lot No" class="input_class form-control" value="<?php
                                            if (isset($_GET['item_code'])) {
                                                echo $itemData->serial_no;
                                            }
                                            ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">  Select Type<span style="color:red">*</span> :  </label>
                                        <div class="col-sm-6">
                                            <select class="chosen-select form-control" id="type" name="type" data-placeholder="Choose a Item Type...">
                                                <option value="">-- Select Type ---</option>
                                                <option value="1" <?php
                                                if ($status == '1') {
                                                    echo "selected";
                                                }
                                                ?>>1</option>
                                                <option value="2" <?php
                                                if ($status == '2') {
                                                    echo "selected";
                                                }
                                                ?>>2</option>
                                                <option value="3" <?php
                                                if ($status == '3') {
                                                    echo "selected";
                                                }
                                                ?>>3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="p_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Item UOM<span style="color:red">*</span> :  </label>
                                        <div class="col-sm-6">
                                            <select class="chosen-selec form-control" id="itemCode" name="item_uom" data-placeholder="Choose a Item UOM..." >
                                                <option value="">-- Select Item UOM ---</option>
                                                <?php foreach ($bdd->getAllActiveUomDetails() as $itemUOM) { ?>
                                                    <option value="<?php echo $itemUOM->lang_uom_desc; ?>" <?php
                                                    if ($item_uom == $itemUOM->lang_uom_desc) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php echo $itemUOM->lang_uom_desc; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="p_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Item Type<span style="color:red">*</span> :  </label>
                                        <div class="col-sm-6">
                                            <select class="chosen-select form-control" id="item_type" name="item_type" data-placeholder="Choose a Item UOM..." >
                                                <option value="">-- Select Item Type ---</option>
                                                <option value="regular" <?php
                                                if ($item_type == 'regular') {
                                                    echo "selected";
                                                }
                                                ?>>Regular</option>
                                                <option value="miscellaneous" <?php
                                                if ($item_type == 'miscellaneous') {
                                                    echo "selected";
                                                }
                                                ?>>Miscellaneous</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="p_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Excisable Status<span style="color:red">*</span> :  </label>
                                        <div class="col-sm-6">
                                            <select class="chosen-select form-control" id="item_type" name="ex_in_status" data-placeholder="Choose a Item UOM...">
                                                <option value="">-- Select Excisable Status ---</option>
                                                <option value="1" <?php
                                                if ($ex_in_status == '1') {
                                                    echo "selected";
                                                }
                                                ?>>Excisable</option>
                                                <option value="0" <?php
                                                if ($ex_in_status == '0') {
                                                    echo "selected";
                                                }
                                                ?>>Non Excisable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="p_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Status<span style="color:red">*</span> :  </label>
                                        <div class="col-sm-6">
                                            <select class="chosen-selec form-control" id="status" name="status" data-placeholder="Choose a Item UOM..." onchange="checkcurrentstock();" required="" >
                                                <option value=""><-- Select Status --></option>
                                                <option value="1" <?php
                                                if ($itemData->active == 1) {
                                                    echo "selected";
                                                }
                                                ?>>Active</option>
                                                <option value="0" <?php
                                                if ($itemData->active == 0) {
                                                    echo "selected";
                                                }
                                                ?>>In-Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Item Group<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">

                                            <select class="chosen-select form-control" id="item_group"  name="item_group" data-placeholder="Choose a Item Group...">
                                                <option value=""><-- Select Item Group ---></option>
                                                <?php foreach ($bdd->getInventoryGroupList() as $group_list) { ?>
                                                    <option value="<?php echo $group_list->id; ?>" <?php
                                                    if ($group_list->id == $group_id) {
                                                        echo 'selected';
                                                    }
                                                    ?>><?php echo $group_list->group_desc; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="p_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"></label>
                                        <div class="col-sm-6">
                                            <input type="checkbox" name="order_item_flg" value="1" <?php
                                            if ($order_item_flg == '1') {
                                                echo "checked";
                                            }
                                            ?>> if Dies Order Item?
                                        </div>
                                    </div>
                                    <div class="form-group" id="p_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"></label>
                                        <div class="col-sm-6">
                                            <input type="checkbox" name="purchase_item" value="1" <?php
                                            if ($purchase_item == '1') {
                                                echo "checked";
                                            }
                                            ?>> if Purchase Order Item?
                                        </div>
                                    </div>
                                    <div class="form-group" id="p_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"></label>
                                        <div class="col-sm-6">
                                            <input type="checkbox" name="def_item" value="1" <?php
                                            if ($def_item == '1') {
                                                echo "checked";
                                            }
                                            ?>> if DEF Item?
                                        </div>
                                    </div>
                                    <input type="hidden" name="percent_bkp" id="percent_bkp" value="">
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-info">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Submit
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
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <!--END MAIN WRAPPER -->
            <script type="text/javascript">
                function sgst(value)
                {
                    var cgst = $("#cgst_value").val();
                    var basic_price = $("#basic_price").val()
                    //$total1=$itemData->sgst_rate+$itemData->cgst_rate*100/100;
                    //$total=$itemData->basic_price+$total1;
                    var total1 = (Number(cgst) + Number(value)) * basic_price / 100;
                    //+Number(basic_price)
                    var total = Number(basic_price) + total1;
                    $("#total").val(total)


                }

                function cgst(value)
                {
                    var sgst = $("#sgst_value").val();
                    var basic_price = $("#basic_price").val()
                    var total1 = (Number(sgst) + Number(value)) * basic_price / 100;
                    //+Number(basic_price)
                    var total = Number(basic_price) + total1;
                    $("#total").val(total)
                }
                //Find 25% of 10 (remember 'of' means 'times').
                //25/100 × 10

                function checkcurrentstock() {
                    var current_stock_val = document.getElementById('current_stock').value;
                    var status = document.getElementById('status').value;

                    if (current_stock_val !== '0' && status == '0') {
                        alert('Please check current stock value then In-Active this item.');
                        document.getElementById('status').value = '';
//                        alert(status);
                        status.focus();
                        return false;
                    }
                }

            </script>
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>
<!--  <input type="text" name="sgst_value" id="sgst_value" placeholder="SGST" class="input_class form-control" required="required" value="<?php
// if (isset($_GET['item_code'])) {
//     echo $itemData->sgst_value;
// } else {
//     echo '0';
// }
?>" />  -->

<!-- <input type="text" name="cgst_value" id="cgst_value" placeholder="CGST" class="input_class form-control" required="required" value="<?php
// if (isset($_GET['item_code'])) {
//     echo $itemData->cgst_value;
// } else {
//     echo '0';
// }
?>" />  -->
