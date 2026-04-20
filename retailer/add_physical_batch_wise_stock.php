<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = $_SESSION['id'];
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
                                        case 2:
                                            $msg = "Image must be less than 2MB.";
                                            break;
                                        case 1:
                                            $msg = "Sorry, only PNG Image is allowed..";
                                            break;
                                        case 3:
                                            $msg = "Sorry, Image Not Uploaded.";
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
                                        <?php echo "Data Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Add Physical Batch Wise Stock.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Item<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control chosen-select" name="item_data" id="item_data" required="required">
                                                <option value=""> -- Select -- </option>
                                                <?php foreach (getRetailerActiveItemsList($retailer_id) as $raw) { ?>
                                                    <option value="<?php echo $raw->item_id . "_" . $raw->item_code . "_" . $raw->item_desc; ?>" ><?php echo $raw->item_desc; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-info">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Show
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.row -->


                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                if (isset($_POST['submit'])) {
                                    $item_data = explode("_", $_POST['item_data']);
                                    $item_id = $item_data[0];
                                    $item_code = $item_data[1];
                                    $item_desc = $item_data[2];
                                    ?>

                                    <form name="secound_form" action="" method="post">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center;" > <?php echo $item_desc; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Current Stock For   => <?php echo getCurrentStockByRetailerIdAndItemId($retailer_id, $item_id); ?></th>
                                                    <th colspan="2">
                                                        Total Physical Available Quantity &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 

                                                        <input type="number" name="ttl_phy_qty" />

                                                        <input type="hidden" name="bitem_code_1" value="<?php echo $item_code; ?>" />
                                                        <input type="hidden" name="bitem_id_1" value="<?php echo $item_id; ?>" />
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Batch No</th>
                                                    <th>Current Quantity Batch Wise</th>
                                                    <th>Physical Available Quantity Batch Wise</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $bath_d = getFreeSerielNoByRetailerItemVerde($item_code, $retailer_id);

                                                foreach ($bath_d as $raw_2) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $raw_2->batch_no; ?>
                                                            <input type="hidden" name="batch_no[]" value="<?php echo $raw_2->batch_no; ?>" />
                                                            <input type="hidden" name="batch_cur_count[]" value="<?php echo $raw_2->cf; ?>" />
                                                        </td>
                                                        <td><?php echo $raw_2->cf; ?></td>
                                                        <td>
                                                            <input type="number" name="phy_avl_count[]" id="phy_avl_count"  />
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" style="text-align: center;"><button type="submit" name="finel_submit" class="btn btn-default btn-sm" >SubmitData</button></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </form>



                                    <?php
                                }
                                ?>
                            </div>
                        </div>


                        <?php
                        if (isset($_POST['finel_submit'])) {

                            $item_cd = $_POST['bitem_code_1'];
                            $item_iid = $_POST['bitem_id_1'];
                            $total_phy_qty = $_POST['ttl_phy_qty'];

                            $upda = array();
                            $upda['physical_stock'] = $total_phy_qty;
                            $whrr = "retailer_id = '$retailer_id' and item_id = '$item_iid' and item_code = '$item_cd'";

                            $upd = update('retailer_inventory_master', $upda, $whrr);

                            $ins_arr = array();
                            $ins_arr['retailer_id'] = $retailer_id;
                            $ins_arr['item_id'] = $item_iid;
                            $ins_arr['item_code'] = $item_cd;
                            $ins_arr['physical_stock'] = $total_phy_qty;
                            $ins_arr['add_date'] = date('Y-m-d H:i:s');
                            insert('retailer_inventory_physical_history', $ins_arr);

                            $batch_no = $_POST['batch_no'];
                            $batch_cur_count = $_POST['batch_cur_count'];
                            $batch_phy_avl_count = $_POST['phy_avl_count'];

                            for ($i = 0; $i < count($batch_no); $i++) {
                                $hiss_arr = array();
                                $hiss_arr['retailer_id'] = $retailer_id;
                                $hiss_arr['item_id'] = $item_iid;
                                $hiss_arr['item_code'] = $item_cd;
                                $hiss_arr['batch_no'] = $batch_no[$i];
                                $hiss_arr['batch_wise_count'] = $batch_cur_count[$i];
                                $hiss_arr['batch_wise_phy_count'] = $batch_phy_avl_count[$i];
                                $hiss_arr['add_date'] = date('Y-m-d H:i:s');
                                $inss = insert('retailer_inventory_batchwise_history', $hiss_arr);
                            }

                            if ($inss && $upd) {
                                echo "<h4 style='color:green;'>Data Submited Successfully..!!</h4>";
                            } else {
                                echo "<h4 style='color:red;'>Data Submit Error..!!</h4>";
                            }
                        }
                        ?>


                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>
