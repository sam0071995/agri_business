<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
if (isset($_POST['show'])) {
    if (isset($_POST['Retailer_id'])) {
        $retailer_id = $_POST['Retailer_id'];
    }
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

                                <h3 class="page-header">Retailer | Day book currency entry.</h3>
                                <div class="page-header">
                                    <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-sm-5">
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
                                                <div class="col-sm-5">
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
                                                <button class="btn btn-info" type="submit" name="show" value="show">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    Show
                                                </button>
                                            </div>
                                        </div>
                                    </form>
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
                                                    <th>DateTime</th>
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
                                                    $retailer_id_Array = $_POST['Retailer_id'];
                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));

                                                    foreach ($retailer_id_Array as $retailer_id) {
                                                        $cashInhands = getDaybookCasInhandEntry($retailer_id, $date_1);
                                                        $index = 1;
                                                        foreach ($cashInhands as $cashInhand) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $index; ?></td>
                                                                <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                <td><?php echo IND_money_format($cashInhand->total_amount); ?></td>
                                                                <td><?php echo date("d M Y H:i:s", strtotime($cashInhand->datetime)); ?></td>
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

