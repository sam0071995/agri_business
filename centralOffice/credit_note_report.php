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
                                            <h4 class="widget-title">Retailer | Credit Note Report.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">

                                                <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-sm-4">
                                                                <select class="form-field-select-2 form-control" multiple name="Retailer_id[]" id="Retailer_id" required="required">
                                                                    <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                                        if ($active_sellers->id == $retailer_id) {
                                                                            echo "selected='selected'";
                                                                        }
                                                                        ?>><?php echo $active_sellers->name; ?></option>
                                                                            <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
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
                                                            <div class="col-sm-3">
                                                                <div class="input-group">
                                                                    <input class="form-control date-picker" id="id-" name="date_2" type="text" value="<?php
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
                                                            <button class="btn btn-info" type="submit" name="show" value="show">
                                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                                Show
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>

                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Retailer Name</th>
                                                    <th>Item</th>
                                                    <th>Ordered Qty</th>
                                                    <th>Return Qty</th>
                                                    <th>Credit Note No</th>
                                                    <th>Invoice No</th>
                                                    <th>Date</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST['show'])) {
                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                    $retailer_id_Array = $_POST['Retailer_id'];
                                                    $index = 1;
                                                    foreach ($retailer_id_Array as $retailer_id) {
                                                        $partiallyOrders = getPatriallyRejectedOrder($retailer_id, $date_1, $date_2);
                                                        foreach ($partiallyOrders as $partiallyOrder) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $index; ?></td>
                                                                <td><?php echo getRetailerNameById($partiallyOrder->retailer_id); ?></td>
                                                                <td><?php echo getItemNameByItemCode($partiallyOrder->item_code); ?></td>
                                                                <td><?php echo $partiallyOrder->ordered_qty; ?></td>
                                                                <td><?php echo $partiallyOrder->rejected_qty; ?></td>
                                                                <td><?php echo $partiallyOrder->credit_note_no; ?></td>
                                                                <td><?php echo $partiallyOrder->order_no; ?></td>
                                                                <td><?php echo $partiallyOrder->datetime; ?></td>
                                                                <td><?php echo $partiallyOrder->remarks; ?></td>
                                                            </tr>
                                                            <?php
                                                            $index++;
                                                        }
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
            <script type="text/javascript">
                $('#Retailer_id').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: true
                });
            </script>
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

