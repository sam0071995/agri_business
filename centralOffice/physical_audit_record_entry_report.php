<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

//print_r($_SESSION);
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
                                        <h3 class="header-text">Physical Audit Record Entry Report.</h3>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <form class="form-inline center" action="" method="POST">
                                                            <div class="row">

                                                                <div class="form-group">


                                                                    <div class="col-xs-14">
                                                                        <b>Select Retailer :</b>
                                                                        <div class="input-group">
                                                                            <select class="form-control chosen-select" name="retailer_id" id="retailer_id" requierd>
                                                                                <option value="all">All</option>

                                                                                <?php foreach (getActiveRetailerDetails($_SESSION['company_id']) as $data) { ?>
                                                                                    <option value="<?php echo $data->id; ?>"><?php echo $data->name; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>	
                                                                <br><br>

                                                                <div class="form-group">


                                                                    <div class="col-xs-14">
                                                                        <b>From Order Date :</b>
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
                                                                        <b>To Order Date :</b>
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
                                                </div><!-- /.row -->

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
                                                                    <th width="15%" align="left">Sr No</th>
                                                                    <th width="15%" align="left">Store</th>
                                                                    <th width="15%" align="left">Item</th>
                                                                    <th width="15%" align="left">Unit</th>
                                                                    <th width="15%" align="left">BatchNo</th>
                                                                    <th width="15%" align="left">BatchWiseQty</th>
                                                                    <th width="15%" align="left">CurrentQty</th>
                                                                    <th width="15%" align="left">ExpiryDate</th>
                                                                    <th width="15%" align="left">Qty in Bag/carton</th>
                                                                    <th width="15%" align="left">MRP</th>
                                                                    <th width="15%" align="left">Remark</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $status = 0;
                                                                $i = 1;
                                                                if (isset($_POST['show'])) {
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $retailer_id = $_POST['retailer_id'];

                                                                    $purchaseOrder = get_data_of_physical_audit_table($date_1, $date_2, $retailer_id);
                                                                    foreach ($purchaseOrder as $row) {
                                                                        ?>
                                                                        <tr >
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo $row->id; ?></td>
                                                                            <td><?php echo getRetailerNameById($row->retailer_id); ?></td>
                                                                            <td><?php echo getItemNameByItemCode($row->item_code); ?></td>
                                                                            <td><?php echo getItemUNITByItemCode($row->item_code); ?></td>
                                                                            <td><?php echo $row->batch_no; ?></td>
                                                                            <td><?php echo $row->batch_wise_qty; ?></td>
                                                                            <td><?php echo $row->current_stock; ?></td>
                                                                            <td><?php echo date('Y-m-d', strtotime($row->expire_date)); ?></td>
                                                                            <td><?php echo $row->packet_no; ?></td>
                                                                            <td><?php echo $row->mrp; ?></td>
                                                                            <td><?php echo $row->remark; ?></td>
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


            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>