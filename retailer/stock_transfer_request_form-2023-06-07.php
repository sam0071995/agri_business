<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
if (isset($_POST['retailer_id'])) {
    $retailer_id = $_POST['retailer_id'];
} else {
    $retailer_id = 0;
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
                                <h3 class="header">Stock Request Form.</h3>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">

                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select Item <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <select class="form-control chosen-select" name="item_id" id="item_id" onchange="get_retailers_data();" required="required">
                                                        <option value="">-- Select Item --</option>
                                                        <?php
                                                        $data = getActiveItemsList();
                                                        foreach ($data as $row) {
                                                            $cur_stck = getCurrentStockByRetailerIdAndItemId($_SESSION['id'], $row->id);
                                                            if (empty($cur_stck)) {
                                                                $cur_stck = 0;
                                                            }
                                                            $html .= "<option value='" . $row->id . "'>" . $row->item_desc . " ( " . $cur_stck . " )</option>";
                                                        }
                                                        echo $html;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select Retailer <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" name="retailer_id" id="retailer_id"  required="required">
                                                        <option value="">-- Select Retailer --</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Qty <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="qty" id="qty" required="required">
                                                </div>
                                            </div>

                                            <div class="clearfix form-actions">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" name="submit" class="btn btn-info">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Add
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.row -->

                                <?php
                                if (isset($_POST['submit'])) {
                                    $retailer_id = $_POST['retailer_id'];
                                    $item_id = $_POST['item_id'];
                                    $qty = $_POST['qty'];

                                    $dupcheck = getCheckDUpDataStockTransfer($_SESSION['id'], $retailer_id, $item_id);

                                    if (count($dupcheck) == 0) {
                                        $insd = array();
                                        $insd['retailer_id'] = $_SESSION['id'];
                                        $insd['company_id'] = getRetailerCompanyIdById($_SESSION['id']);
                                        $insd['frm_retailer_id'] = $retailer_id;
                                        $insd['item_id'] = $item_id;
                                        $insd['item_code'] = getInventoryMasterDataById($item_id)->item_code;
                                        $insd['req_qty'] = $qty;
                                        $insd['cart_date'] = date('Y-m-d');
                                        $ins = insert('retailer_stock_transfer', $insd);
                                    } else {
                                        echo "<script>alert('Item Already Added');</script>";
                                    }
                                }
                                ?>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <!-- <div class="modal-body">
                                                <div class="row clearfix">
                                                    <div class="pull-right tableTools-container"></div>
                                                </div>
                                            </div> -->
                                            <div>
                                                <table id="dynamic-table" class="table table-bordered table-hover">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th width="8%" align="left">#</th>
                                                            <th width="15%" align="left">Fromretailer</th>
                                                            <th width="15%" align="left">Item Name</th>
                                                            <th width="25%" align="left">Qty</th>
                                                            <th width="15%" align="left">Batch</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ind = 0;
                                                        foreach (getTransferPendingData($_SESSION['id']) as $datatwo) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo ++$ind; ?></td>
                                                                <td><?php echo getRetailerDataById($datatwo->frm_retailer_id)->name; ?></td>
                                                                <td><?php echo getInventoryMasterDataById($datatwo->item_id)->item_desc; ?></td>
                                                                <td><?php echo $datatwo->req_qty; ?></td>
                                                                <td>
                                                                    <select name="batch_<?php echo $datatwo->id; ?>" class="batch_<?php echo $datatwo->id; ?>">
                                                                        <?php foreach (getFreeBatchyRetailerId($datatwo->frm_retailer_id, $datatwo->item_code) as $batch_array) { ?>
                                                                            <option value="<?php echo $batch_array->batch_no; ?>"><?php echo $batch_array->batch_no . " (" . $batch_array->count . ")"; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td><button class="btn btn-danger btn-xs" onclick="deletedata(<?php echo $datatwo->id; ?>);">Delete</button></td>
                                                            </tr>
                                                        <?php }
                                                        ?>

                                                    </tbody>
                                                    <tfoot>
                                                        <?php if ($ind !== 0) { ?>
                                                            <tr>
                                                                <td colspan="5"><button class="btn btn-success btn-sm" id="<?php echo $datatwo->id; ?>" onclick="confirmOrder(<?php echo $_SESSION['id']; ?>);">Confirm</button></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.row -->

                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">

                function get_retailers_data() {
                    var item_id = document.getElementById('item_id').value;

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            item_id: item_id,
                            'request_type': 'get_retailers_by_item_id'
                        },
                        success: function (result) {
                            console.log(result);
                            document.getElementById('retailer_id').innerHTML = result;

                        }
                    });
                }


                function getRetailerItem() {
                    var retailer_id = document.getElementById('retailer_id').value;
                    // alert(retailer_id);
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            retailer_id: retailer_id,
                            'request_type': 'retailer_item_by_id'
                        },
                        success: function (result) {

                            document.getElementById('item_id').innerHTML = result;

                        }
                    });

                }

                function deletedata(id) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            id: id,
                            'request_type': 'delete_stock_trans_data'
                        },
                        success: function (result) {
                            if (result == 0) {
                                alert('Data Remove Successfully...');
                                window.location = window.location;
                            } else {
                                alert('Data Remove Error...');
                                window.location = window.location;
                            }
                        }
                    });
                }
 
                function confirmOrder(retailer_id) {
                    alert(this.id);
                    return false;
//                    $.ajax({
//                        type: 'POST',
//                        url: '<?php // echo $ajax_page;  ?>',
//                        data: {
//                            retailer_id: retailer_id,
//                            'request_type': 'confirm_stock_tras_request'
//                        },
//                        success: function (result) {
//                            if (result == 0) {
//                                alert('Request Placed Successfully...');
//                                window.location = window.location;
//                            } else {
//                                alert('Request Placed Error...');
//                                window.location = window.location;
//                            }
//                        }
//                    });
                }
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>