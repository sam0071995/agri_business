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
                                            <h3 class="header-text">Pre Purchase Goods Order Report</h3>
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
                                                                    <th width="15%" align="left">Purchase Date</th>
                                                                    <th width="15%" align="left">Item</th>
                                                                    <th width="15%" align="left">Qty</th>
                                                                    <th width="15%" align="left">Supplier</th>
                                                                    <th width="15%" align="left">Store</th>
                                                                    <th width="15%" align="left">Status</th>
                                                                    <th width="30%" align="left">Print</th>
                                                                    <th width="30%" align="left">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
//                                                                print_r($_SESSION);
                                                                $status = 0;
                                                                $i = 1;
                                                                $purchaseOrder = getPurchaseOrderListByStatusForBasicDetails($_SESSION['id']);
//                                                               
                                                                if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                    foreach ($purchaseOrder as $row) {
                                                                        if ($row->status == 0) {
                                                                            $status = "Po Genereted";
                                                                        } else if ($row->status == 1) {
                                                                            $status = "Order Approved & Placed";
                                                                        } else if ($row->status == 2) {
                                                                            $status = "Goods In Transit";
                                                                        } else if ($row->status == 3) {
                                                                            $status = "Goods Recieved";
                                                                        }
                                                                        ?>

                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo $row->po_no; ?></td>
                                                                            <td><?php echo date('d-M-Y', strtotime($row->po_date)); ?></td>
                                                                            <td><?php echo getItemNameByItemCode($row->item); ?></td>
                                                                            <td><?php echo $row->qty; ?></td>
                                                                            <td><?php echo $row->supplier_id; ?></td>
                                                                            <td>
                                                                                <?php
//                                                                                echo getRetailerNameById($row->retailer_id); 
                                                                                $rateilar_string = explode(',', $row->retailer_string);
                                                                                for ($l = 0; $l < count($rateilar_string); $l++) {
                                                                                    echo $l + 1 . ". " . getRetailerNameById($rateilar_string[$l]) . ' <br/>';
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td width='25%'>
                                                                                <b>Status : <?php echo $status; ?> </b>
                                                                                <br>
                                                                                <br>
                                                                                <b>Remark : <?php echo $row->status_remarks; ?> </b>


                                                                            </td>
                                                                            <td width='10%'>


                                                                                <a target='_blank' href='inventory_purchase_report_for_basic.php?menu=426&po_no="<?php echo base64_encode($row->po_no); ?>"'><button type='button' class='btn-warning' style='cursor:pointer' title='Click to Print'>Print</button></a>&nbsp;&nbsp;


                                                                            </td>
                                                                            <td width='40%'>
                                                                                <?php if ($row->invoice_flag == 0) { ?>
                                                                                    <form id="uploadForm_<?php echo $row->po_no; ?>" enctype="multipart/form-data">
                                                                                        <table class="table">
                                                                                            <tr>
                                                                                                <td>Challan/ Invoice No:</td>
                                                                                                <td>
                                                                                                    <input type="hidden" value="<?php echo $row->po_no; ?>" name="po_no" />
                                                                                                    <input type="text" name="invoice_no" class="invoice_no" />
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>Remarks:</td>
                                                                                                <td><input type="text" name="invoice_remarks" class="invoice_remarks" /></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>Upload Invoice:</td>
                                                                                                <td><input type="file" name="invoice_upload" class="invoice_upload" /></td>
                                                                                            </tr>

                                                                                        </table>
                                                                                    </form>
                                                                                    <table class="table">
                                                                                        <tr>
                                                                                            <td></td>
                                                                                            <td><input type="button" id="<?php echo $row->po_no; ?>" class="uploadForm btn btn-primary" name="submit" value="submit" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    Invoice No : <?php echo $row->upload_invoice_no; ?><br/>
                                                                                    Remarks : <?php echo $row->invoice_remarks; ?><br/>
                                                                                    Upload Date : <?php echo $row->invoice_upload_date; ?><br/>
                                                                                    Print : <a href="<?php echo $row->invoice_upload; ?>" target="_blank">Click Here</a>
                                                                                    <?php
                                                                                }
                                                                                ?>
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
            <script>
                $(document).ready(function () {
//                $('.uploadForm').on('submit', function (event) {
                    $(".uploadForm").click(function (event)
                    {
                        var id = this.id;
                        event.preventDefault(); // Prevent the form from submitting the traditional way
                        var formElement = document.getElementById('uploadForm_' + id);

                        var formData = new FormData(formElement);
                        $.ajax({
                            url: 'upload.php', // PHP file to handle the upload
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response == 1) {
                                    alert("Please Enter Invoice No");
                                } else if (response == 2) {
                                    alert("Please Enter Invoice Remarks");
                                } else if (response == 3) {
                                    alert("Please Select Invoice copy");
                                } else if (response == 4) {
                                    alert("Error into upload invoice copy.");
                                } else if (response == 5) {
                                    alert("Data insertion problem.");
                                } else {
                                    alert("Data Successfully uploaded.");
                                    location.reload();
                                }
                                return false;
                            },
                            error: function (xhr, status, error) {
                                $('#result').html('An error occurred: ' + error);
                            }
                        });
                    });
                });
            </script>
            <script type="text/javascript">

                function delete_purchase__(purchase_id)
                {
                    if (confirm("Are you sure you want to Delete this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'delete_purchase_order_for_basic.php?menu=425',
                            data: {'purchase_id': purchase_id},
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    window.location.href = "purchase_order_for_basic.php?menu=425&success=delete";
                                } else {
                                    alert('Something Wrong Try Again.');
                                    window.location.href = "purchase_order_for_basic.php?menu=425&failure=1";
                                }
                            }
                        });
                    }
                }

                function UpdateCurrentStatus(idd, ponoo) {
                    var statusval = document.getElementById('status_val_' + idd).value;
                    var status_remark = document.getElementById('status_remark_' + idd).value;
                    $.ajax({
                        url: 'ajax_agro.php?menu=425',
                        method: 'post',
                        data: {types: 'update_status_for_basic_po', ponoo: ponoo, statusval: statusval, status_remark: status_remark},
                        success: function (resp) {
                            console.log(resp);
                            if (resp == 1) {
                                alert("Status Updated Successfully...!!!");
                            } else {
                                alert("Status Update Error...!!!");
                            }
                            window.location = window.location;
                        }
                    });
                }




                $(document).ready(function () {
                    $(window).keydown(function (event)
                    {
                        if (event.keyCode == 13) {
                            event.preventDefault();
                            return false;
                        }
                    });
                })
                        ;

            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    

        </div>
    </body>
</html>
