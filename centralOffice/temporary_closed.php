<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$get_retailer_id = '';
$retailer_code = '';
$retailer_name = '';
$address = '';
$contact_name = '';
$contact_numbar = '';
$status = 1;
$email = '';
$retailer_state_id = 0;
$retailer_company_id = '';
$password = '';
$pincode = '';
$get_distributer_id_array = array();
$btn_name = "Close";

if (isset($_POST['submit'])) {
    $table_name = "retailer_master";
    $data = array();
    $data['temp_closed'] = 0;
    $data['temp_closed_date'] = date('Y-m-d h:i:s');
    $where = "company_id in (" . $company_id_in . ")";
    update($table_name, $data, $where);

    $post_distributer_id_array = $_POST['distributer_id'];
    if (empty($post_distributer_id_array)) {
        header("Location:temporary_closed.php" . $menuURL . "&success=1");
        exit;
    }
    $quoted = array_map(function($val) {
        return "'" . $val . "'";
    }, $post_distributer_id_array);

    $inString = '(' . implode(',', $quoted) . ')';


    $table_name = "retailer_master";
    $data = array();
    $data['temp_closed'] = 1;
    $data['temp_closed_date'] = date('Y-m-d h:i:s');
    $where = "company_id in (" . $company_id_in . ") AND id in " . $inString;
    $retailer = update($table_name, $data, $where);

    if ($retailer) {
        header("Location:temporary_closed.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:temporary_closed.php" . $menuURL . "&error=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>
    <style>

        .form-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        label {
            font-weight: bold;
        }
        .distributor-list {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            max-height: auto;
            overflow-y: auto;
            background: #fafafa;
        }
        .distributor-item {
            margin-bottom: 8px;
        }
        .form-actions {
            text-align: center;
            margin-top: 25px;
        }
        .btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn:hover {
            background: #0056b3;
        }
        .select-all {
            margin-bottom: 15px;
        }
    </style>
    <script>
        function toggleAllCheckboxes(source) {
            const checkboxes = document.querySelectorAll('input[name="distributer_id[]"]');
            checkboxes.forEach(cb => cb.checked = source.checked);
        }
    </script>
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
                                            $msg = "Retailer data can not be updated.";
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
                                        <?php echo "Retailers Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Select Distributors for Temporary Closurer.</h3>
                                <hr/>
                                <form id="searchform" name="myForm" method="POST" enctype="multipart/form-data">

                                    <div class="form-actions">
                                        <button type="submit" name="submit" class="btn btn-danger">
                                            <?php echo $btn_name; ?>
                                        </button>
                                    </div>
                                    
                                    <label for="distributorSelect">Select Distributors <span style="color:red">*</span>:</label>
                                    <div class="select-all">
                                        <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)"> <strong>Select All</strong>
                                    </div>

                                    <div class="distributor-list">
                                        <?php
                                        $srNo = 1;
                                        foreach (getActiveRetailerDetailsByStateId($retailer_state_id, $company_id_in) as $retailer) {
                                            // Color by company_id
                                            $companyColors = [1 => "red", 2 => "green", 3 => "blue"];
                                            $class_color = $companyColors[$retailer->company_id];

                                            // Color by state_id
                                            $stateColors = [4 => "green", 9 => "red", 1 => "pink", 5 => "blue"];
                                            $s_class_color = $stateColors[$retailer->state_id];

                                            $isChecked = $retailer->temp_closed == 1 ? 'checked' : '';
                                            ?>
                                            <div class="distributor-item">
                                                <b class="red"><?php echo $srNo; ?>.</b>
                                                <input type="checkbox" name="distributer_id[]" value="<?php echo $retailer->id; ?>" <?php echo $isChecked; ?> />
                                                <b>
                                                    <?php echo $retailer->name; ?>
                                                </b> |
                                                <b class="<?php echo $s_class_color; ?>">
                                                    <?php echo getStateNameById($retailer->state_id); ?>
                                                </b> |
                                                <b class="<?php echo $class_color; ?>">
                                                    <?php echo getCompanyNameById($retailer->company_id); ?>
                                                </b>
                                            </div>
                                            <?php
                                            $srNo++;
                                        }
                                        ?>
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

