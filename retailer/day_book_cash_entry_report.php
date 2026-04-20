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
                                            <h4 class="widget-title">Retailer | Day Book Cash in-hand Details.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <b>Date :</b>
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
                                                        <div class="clearfix form-actions">
                                                            <div class="col-md-offset-3 col-md-5">
                                                                <button class="btn btn-info" type="submit" name="show" value="show">
                                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                                    Show
                                                                </button>

                                                                &nbsp; &nbsp; &nbsp;
                                                                <button class="btn" type="reset">
                                                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                    Reset
                                                                </button>
                                                            </div>
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
                                                    <th>Amount</th>
                                                    <th>C 2000</th>
                                                    <th>M 2000</th>
                                                    <th>C 500</th>
                                                    <th>M 500</th>
                                                    <th>C 200</th>
                                                    <th>M 200</th>
                                                    <th>C 100</th>
                                                    <th>M 100</th>
                                                    <th>C 50</th>
                                                    <th>M 50</th>
                                                    <th>C 20</th>
                                                    <th>M 20</th>
                                                    <th>C 10</th>
                                                    <th>M 10</th>
                                                    <th>C 5</th>
                                                    <th>M 5</th>
                                                    <th>C 2</th>
                                                    <th>M 2</th>
                                                    <th>C 1</th>
                                                    <th>M 1</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST['show'])) {
                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_2']));
                                                    $cashInhands = getDaybookCasInhandEntry($retailer_id, $date_1);
                                                    $index = 1;
                                                    foreach ($cashInhands as $cashInhand) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->total_amount); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_2000); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_2000); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_500); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_500); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_200); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_200); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_100); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_100); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_50); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_50); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_20); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_20); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_10); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_10); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_5); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_5); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_2); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_2); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->c_1); ?></td>
                                                            <td><?php echo IND_money_format($cashInhand->m_1); ?></td>
                                                        </tr>
                                                        <?php
                                                        $index++;
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
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

