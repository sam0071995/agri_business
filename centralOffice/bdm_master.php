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
                        <div class="page-header">
                            <div class="row float-sm-left">
                                <a href="add_bdm.php<?php echo $menuURL; ?>" class="float-sm-left"><button class="btn btn-primary float-sm-left">Add New BDM</button></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Details can not be insert.";
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
                                        <?php echo "Details Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">BDM Details.</h3>
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Company</th>
                                                    <th>Name</th>
                                                    <th>Code</th> 
                                                    <th>Mobile</th>   
                                                    <th>Email</th>   
                                                    <th>Password</th>
                                                    <th width="330">Stores</th>   
                                                    <th>Status</th>   
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 1;
                                                $retailers = getBDMDetails($company_id_in);
                                                foreach ($retailers as $retailer) {
                                                    $status = "";
                                                    if ($retailer->status == 1) {
                                                        $status .= '<span class="badge badge-success">Active</span>';
                                                    } else {
                                                        $status .= '<span class="badge badge-danger">In-Active</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td><?php echo getCompanyNameById($retailer->company_id); ?></td>
                                                        <td><?php echo $retailer->full_name; ?></td>
                                                        <td><?php echo $retailer->bdm_code; ?></td>
                                                        <td><?php echo $retailer->contact_number; ?></td>
                                                        <td><?php echo $retailer->email; ?></td>
                                                        <td>
                                                            <?php echo $retailer->password; ?>
    <!--                                                            <input type="text" value="<?php // echo $retailer->password;                            ?>" id="myInput_<?php echo $index; ?>" style="width:40px;">
                                                            <button onclick="myFunction(<?php // echo $index;                            ?>)">Copy</button>-->
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($retailer->retailer_id != 0) {
                                                                $retailerArray = explode(",", $retailer->retailer_id);
                                                                $ind = 1;
                                                                foreach ($retailerArray as $retailerA) {
                                                                    echo $ind . "). " . getRetailerNameById($retailerA);
                                                                    if ($ind % 1 == 0) {
                                                                        echo '<br/>';
                                                                    }
                                                                    $ind++;
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $status; ?></td>
                                                        <td>
                                                            <a href="add_bdm.php<?php echo $menuURL; ?>&bdm_id=<?php echo base64_encode($retailer->id); ?>"><button class="btn btn-primary" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                                        </td>
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
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

