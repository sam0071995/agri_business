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
                        <?php require_once 'includes/page-header.php'; ?> <div class="page-header">
                            <div class="row float-sm-left">
                                <a href="add_inventory_item.php<?php echo $menuURL; ?>" class="float-sm-left"><button class="btn btn-primary float-sm-left">New Inventory Item</button></a>
                                <a href="inventory_master.php<?php echo $menuURL; ?>" class="float-sm-left"><button class="btn btn-danger float-sm-left">List Inventory Item</button></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Item can not be insert.";
                                            break;
                                        case 101:
                                            $msg = "Image can not uploaded.";
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
                                        <?php echo "product Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Search Inventory Item.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Search Item By Item Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-9">
                                            <select class="form-field-select-2 form-control chosen-select" name="main_category" required="required">
                                                <option value="">--search item--</option>
                                                <?php foreach (getProductsList() as $pCategory) { ?>
                                                    <option value="<?php echo $pCategory->item_code; ?>" <?php
                                                    ?>>
                                                                <?php echo $pCategory->item_desc; ?> 
                                                                <?php
                                                                echo '<b> | ' . $pCategory->description . '</b>';
                                                                ?>
                                                                <?php
                                                                if ($pCategory->status == 1) {
                                                                    echo '<b> | Active</b>';
                                                                } else {
                                                                    echo '<b> | IN-Active></b>';
                                                                }
                                                                ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-12">
                                            <h4 class="red">Dear User, please search for the item before creating a new one. If the item already exists, review its details and current status instead of duplicating it.</h4>
                                        </div>
                                    </div>
                                </form>
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

