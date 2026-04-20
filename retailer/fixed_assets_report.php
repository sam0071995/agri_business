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
                                            <h4 class="widget-title">Retailer | NKKSK CENTERS FIXED ASSETS.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">

                                                <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-sm-2">
                                                                <select class="form-field-select-2 form-control" name="fin_years" id="fin_years" required="required">
                                                                    <?php
                                                                    $startdate = 2023;
                                                                    $enddate = date("Y");
                                                                    $years = range($startdate, $enddate);
                                                                    foreach ($years as $year) {
                                                                        ?>
                                                                        <option value="<?php echo str_pad((substr($year, -2)), 2, "0", STR_PAD_LEFT) . '' . str_pad((substr($year, -2) + 1), 2, "0", STR_PAD_LEFT); ?>"><?php echo $year . ' - ' . str_pad((substr($year, -2) + 1), 2, "0", STR_PAD_LEFT); ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
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
                                        <?php
                                        $allActiveAssets = getAdminFixedAssets();
                                        $count_assets = count($allActiveAssets);
                                        $count_assets = $count_assets + 3;
                                        ?>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Retailer Name</th>
                                                    <th><?php echo "FinYear" ?></th>
                                                    <?php foreach ($allActiveAssets as $allActiveAsset) { ?>
                                                        <th><?php echo $allActiveAsset->item_name; ?></th>
                                                        <!--<th style="writing-mode: vertical-lr;transform: rotate(180deg);text-align:left;"><?php // echo $allActiveAsset->item_name;  ?></th>-->
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST['show'])) {
                                                    $fin_years = $_POST['fin_years'];
                                                    $index = 1;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                        <td><?php echo $fin_years ?></td>
                                                        <?php foreach ($allActiveAssets as $allActiveAsset) { ?>
                                                            <th><?php echo getActiveFixedAssetsCountByFinYearRetailerIdQty($fin_years, $retailer_id, $allActiveAsset->item_code); ?></th>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                    $index++;
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

