<?php
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
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <h3 class="header-text">Goods Received Note</h3>
                                            <div class="col-xs-12">
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
                                                                    <th width="15%" align="left">Purchase No</th>
                                                                    <th width="15%" align="left">Item Name</th>
                                                                    <th width="25%" align="left">Supplier / Distributer</th>
                                                                    <th width="15%" align="left">PO Qty</th>
                                                                    <th width="15%" align="left">BalancedQty</th>
                                                                    <th width="25%" align="left"></th>
                                                                    <th width="15%" align="left">Rematks</th>
                                                                    <th width="25%" align="left"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $status = 0;
                                                                $i = 1;
                                                                $purchaseOrder = getInventoryGrnDetailsById($status, $_SESSION['id']);
                                                                if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                    foreach ($purchaseOrder as $row) {
                                                                        if ($row->dispatch_retailer_id == 0) {
                                                                            $supname = $row->supplier_name;
                                                                        } else {
                                                                            $supname = getRetailerDataById($row->dispatch_retailer_id)->name;
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td>
                                                                                PO No : <?php echo $row->po_no; ?><br/>
                                                                                PO Date : <?php echo $row->po_date; ?>
                                                                            </td>
                                                                            <td><?php echo getInventoryItemNameByCode($row->item_desc); ?></td>
                                                                            <td>
                                                                                <?php echo $supname; ?><hr/>
                                                                                Name of person : <?php echo $row->name_of_person; ?><hr/>
                                                                                Vehicle No : <?php echo $row->Vehicle_Number; ?>
                                                                            </td>
                                                                            <td><?php echo $row->billed_qty; ?></td>
                                                                            <td><?php echo numberDecimal($row->billed_qty - $row->inward_qty); ?></td>
                                                                            <td width='200'>
                                                                                Item Qty: <input type="text" name="inward_qty<?php echo $row->id; ?>" class="inward_qty_<?php echo $row->id; ?>" placeholder="Inward Qty" /><br/>
                                                                                <?php
                                                                                if ($row->dispatch_retailer_id == 0) {
                                                                                    $readonly = '';
                                                                                    ?>
                                                                                    Batch Number: <input type="text" name="batch_number<?php echo $row->id; ?>" class="batch_number_<?php echo $row->id; ?>" placeholder="Batch Number" /><br/>
                                                                                    Manufacturing Date : <input type="text" name="manufacturing_date_<?php echo $row->id; ?>" data-date-format="dd-mm-yyyy" class="date-picker manufacturing_date_<?php echo $row->id; ?>" value="<?php echo date("d-m-Y"); ?>" /><br/>
                                                                                    Expire Date : <br/><input type="text" name="expire_date_<?php echo $row->id; ?>" data-date-format="dd-mm-yyyy" class="date-picker expire_date_<?php echo $row->id; ?>" value="<?php echo date("d-m-Y"); ?>" />
                                                                                    <?php
                                                                                } else {
                                                                                    $readonly = 'readonly="readonly"';
                                                                                    if (empty($row->manufacture_date)) {
                                                                                        $manufacture_date_readonly = '';
                                                                                    } else {
                                                                                        $manufacture_date_readonly = '';
                                                                                        $manufacture_date_readonly = 'readonly="readonly"';
                                                                                    }
                                                                                    if (empty($row->expire_date)) {
                                                                                        $expire_date_readonly = '';
                                                                                    } else {
                                                                                        $expire_date_readonly = 'readonly="readonly"';
                                                                                        $expire_date_readonly = '';
                                                                                    }
                                                                                    ?>
                                                                                    Batch Number: <input <?php echo $readonly; ?> type="text" name="batch_number<?php echo $row->id; ?>" class="batch_number_<?php echo $row->id; ?>" value="<?php echo $row->batch_number; ?>" placeholder="Batch Number" /><br/>
                                                                                    Manufacturing Date : <input <?php // echo $readonly;                       ?> type="text" name="manufacturing_date_<?php echo $row->id; ?>" value="<?php echo date("d-m-Y", strtotime($row->manufacture_date)); ?>"  class="date-picker manufacturing_date_<?php echo $row->id; ?>" /><br/>
                                                                                    Expire Date : <br/><input <?php echo $readonly; ?> type="text" name="expire_date_<?php echo $row->id; ?>" value="<?php echo date("d-m-Y", strtotime($row->expire_date)); ?>"  class="expire_date_<?php echo $row->id; ?>" />
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td width='50'>
                                                                                <textarea name="remarks<?php echo $row->id; ?>" class="remarks_<?php echo $row->id; ?>"></textarea><hr/>
                                                                                <input type="text" name="name_of_person" class="name_of_person_<?php echo $row->id; ?>" placeholder="Name of person" /><hr/>
                                                                                <input type="text" name="Vehicle_Number" class="Vehicle_Number_<?php echo $row->id; ?>" placeholder="Vehicle Number" />
                                                                            </td>
                                                                            <td width='15'>
                                                                                <button type='button' class='button btn-success' style='cursor:pointer' onclick="inward_item('<?php echo $row->id; ?>');" title='Inward'>Inward</button><br/><br/>
                                                                                <?php if ($row->inward_qty == 0) { ?>
                                                                                    <button type='button' class='button btn-danger' style='cursor:pointer' onclick="inward_reject('<?php echo $row->id; ?>');" title='Reject'>Reject</button>
                                                                                <?php } ?>
                                                                            </td>
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

                function getCurrentDate() {
                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
                    var yyyy = today.getFullYear();
                    return dd + '-' + mm + '-' + yyyy;
                }


                function inward_reject(id) {
                    if (confirm("Are you sure you want to Reject this All Qty?")) {
                        var remarks = $(".remarks_" + id).val();
                        if (remarks == '') {
                            $(".remarks_" + id).focus()
                            alert("Enter Remarks");
                            return false;
                        }

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $ajax_inward; ?>',
                            data: {
                                'id': id,
                                'remarks': remarks,
                                'request_type': 'inward_reject',
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 0) {
                                    alert('Your Inward rejected...!!');
                                    window.location = window.location;
                                } else {
                                    alert('Item Inward rejectuon Error...!!');
                                }
                            }
                        });
                    }

                }

                function parseDate(dateStr) {
                    var parts = dateStr.split('-');
                    return new Date(parts[2], parts[1] - 1, parts[0]);
                }

                function inward_item(id) {
                    if (confirm("Are you sure you want to Inward this?")) {
                        $(".loader").css("display", "block");
                        var inward_qty = $(".inward_qty_" + id).val();
                        if (inward_qty == '') {
                            $(".inward_qty_" + id).focus()
                            alert("Enter Inward Qty");
                            $(".loader").css("display", "none");
                            return false;
                        }
                        var batch_number = $(".batch_number_" + id).val();
                        if (batch_number == '') {
                            $(".batch_number_" + id).focus()
                            alert("Enter Batch Number");
                            $(".loader").css("display", "none");
                            return false;
                        }
                        var manufacturing_date = $(".manufacturing_date_" + id).val();
                        if (manufacturing_date == '') {
                            $(".manufacturing_date_" + id).focus()
                            alert("Enter Manufacturing Date");
                            $(".loader").css("display", "none");
                            return false;
                        }
                        var expire_date = $(".expire_date_" + id).val();
                        if (expire_date == '') {
                            $(".expire_date_" + id).focus()
                            alert("Enter Expire Date");
                            $(".loader").css("display", "none");
                            return false;
                        }

//                        if (expire_date <= manufacturing_date) {
//                            $(".expire_date_" + id).focus();
//                            alert("Expire Date must be greater than Manufacturing Date");
//                            $(".loader").css("display", "none");
//                            return false;
//                        }

                        var today = getCurrentDate();

//                        if (manufacturing_date >= today) {
//                            alert("Manufacturing Date must be greater than today");
//                            $(".loader").css("display", "none");
//                            return false;
//                        }
//
//                        if (expire_date <= today) {
//                            alert("Expiry Date must be greater than today");
//                            $(".loader").css("display", "none");
//                            return false;
//                        }

                        var name_of_person = $(".name_of_person_" + id).val();
                        if (name_of_person == '') {
                            $(".name_of_person_" + id).focus()
                            alert("Enter name of person");
                            $(".loader").css("display", "none");
                            return false;
                        }

                        var Vehicle_Number = $(".Vehicle_Number_" + id).val();
                        if (Vehicle_Number == '') {
                            $(".Vehicle_Number_" + id).focus()
                            alert("Enter Vehicle Number");
                            $(".loader").css("display", "none");
                            return false;
                        }
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $ajax_inward; ?>',
                            data: {
                                'id': id,
                                'request_type': 'inward_grn',
                                'batch_number': batch_number,
                                'manufacturing_date': manufacturing_date,
                                'inward_qty': inward_qty,
                                'Vehicle_Number': Vehicle_Number,
                                'name_of_person': name_of_person,
                                'expire_date': expire_date
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 2) {
                                    alert('Inward Qty should be less than Billed Qty.');
                                } else if (result == 3) {
                                    alert('Enter Valid Qty.');
                                } else if (result == 4) {
                                    alert('Manufacturing date should be less than current date.');
                                } else if (result == 5) {
                                    alert('Expiry & Manufacturing date can not be same.');
                                } else if (result == 7) {
                                    alert('Enter Valid Expiry date.');
                                } else if (result == 8) {
                                    alert('Enter Valid Manufacturing date.');
                                } else if (result == 6) {
                                    alert('Expiry date should be greater than current date.');
                                } else if (result == 9) {
                                    alert('Expiry date should be greater than current date.');
                                } else if (result == 0) {
                                    alert('Your Item Inward Successfully...!!');
                                    window.location = window.location;
                                } else {
                                    alert('Item Inward Error...!!');
                                }
                                $(".loader").css("display", "none");
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