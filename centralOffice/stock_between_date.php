<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

//$retailer_id = 1;
$status = 1;
$retailer_id = '';
$product_id = '';
$product_title = '';
$product_category = '';
$general_category = '';
$short_description = '';
$feature_description = '';
$remarks = '';
$description = '';
$btn_name = "Submit";
$item_code = "";
if (isset($_POST['show'])) {
    $retailer_id = $_POST['Retailer_id'];
    $item_code = $_POST['item_code'];
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
                                <h3 class="page-header">Retailer | Inventory Stock Report Between Date</h3>

                                <form name="myForm" id="myForm" role="form" action="" method="POST" target="_blank" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-5">
                                                <b>Retailer </b>
                                                <select class="form-field-select-2 form-control" multiple name="Retailer_id[]" id="Retailer_id" required="required">
                                                    <!--<option value="ALL">All Retailer</option>-->
                                                    <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                        if ($active_sellers->id == $retailer_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_sellers->name; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                            <!--                                            <div class="col-sm-3">
                                                                                            <b>Item :</b>
                                                                                            <select class="form-field-select-2 form-control" multiple name="item_code[]" id="item_code" required="required">
                                            <?php // foreach (getActiveItemsList() as $active_item) { ?>
                                                                                                    <option value="<?php // echo $active_item->id;                   ?>" <?php
//                                                        if ($item_code == $active_item->id) {
//                                                            echo 'selected="selected"';
//                                                        }
                                            ?>><?php // echo $active_item->item_desc; ?></option>
                                            <?php // } ?>
                                                                                            </select>
                                                                                        </div>-->
                                            <div class="col-sm-3">
                                                <b>From Date </b>
                                                <div class="input-group">
                                                    <input class="form-control date-picker" id="date_1-" name="date_1" type="text" value="<?php
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
                                                <b>To Date </b>
                                                <div class="input-group">
                                                    <input class="form-control date-picker" id="date_2" name="date_2" type="text" value="<?php
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

                                            <div class="col-sm-5">
                                                <b>Item </b>
                                                <select class="form-field-select-2 form-control chosen-select" name="item_code" required="required">
                                                    <option value="ALL">All Items</option>
                                                    <?php foreach (getActiveItemsList() as $active_item) { ?>
                                                        <option value="<?php echo $active_item->item_code; ?>" <?php
                                                        if ($active_item->item_code == $item_code) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_item->item_desc; ?> [<?php echo $active_item->item_code; ?>]</option>
                                                            <?php } ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-3">
                                                <b>Reports </b>
                                                <select class="form-control filter_report" name="filter_report" required="required">
                                                    <option value="1">FIFO</option>
                                                    <option value="2">LIFO</option>
                                                    <option value="3">AVERAGE</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="show" value="show" />
                                        </div>
                                    </div>
                                </form>
                                <button class="btn btn-info submit-btn" type="button" name="show" value="show">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Show
                                </button>
                            </div>
                        </div>
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->
            <script>
                $('.submit-btn').on('click', function () {
                    const form = $('#myForm');
                    var filter_report = $(".filter_report").val();
                    if (filter_report == 1) {
                        var url = "stock_auto_mail.php?menu=1";
                    } else if (filter_report == 2) {
                        var url = "lifo_stock_auto_mail.php?menu=1";
                    } else {
                        var url = "avarage_stock_auto_mail.php?menu=1";
                    }
                    form.attr('action', url);
                    form.submit();
                });
            </script>
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
            <script type="text/javascript">
                $('#item_code').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: true
                });
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

