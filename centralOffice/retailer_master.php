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
                                <a href="add_retailer.php<?php echo $menuURL; ?>" class="float-sm-left"><button class="btn btn-primary float-sm-left">Add New Store/Retailer</button></a>
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
                                <h3 class="page-header">Store/Retailer Details.</h3>
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Store OP Balance</th>
                                                    <th>Company</th>
                                                    <th>Zone</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Address</th>
                                                    <th>Contact Name</th>  
                                                    <th>Login</th>   
                                                    <th>LoginTime</th>   
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 1;
                                                $retailers = getAllRetailerDetails($company_id_in);
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
                                                        <td>
                                                            <?php echo $retailer->opening; ?>
                                                        </td>
                                                        <td><?php echo getCompanyNameById($retailer->company_id); ?></td>
                                                        <td>
                                                            <?php
                                                            if ($retailer->new_zone_id != 0) {
                                                                $explode_zone_id = explode(',', $retailer->new_zone_id);
                                                                foreach ($explode_zone_id as $zone_id) {
                                                                    echo getZoneNameById($zone_id);
                                                                    echo '<br/>';
                                                                }
                                                            }
                                                            ?>
                                                        <td><?php echo $retailer->retailer_code; ?><br/>
                                                            <?php echo $status; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $retailer->full_name; ?>
                                                        </td>
                                                        <td><?php echo strip_tags($retailer->address); ?></td>
                                                        <td><?php echo $retailer->contact_name . " <br/>(" . $retailer->contact_number . ")"; ?></td>
                                                        <td>
                                                            Email : <?php echo $retailer->email; ?><br/>
                                                            Password : <?php echo $retailer->password; ?>
                                                        </td>
                                                        <td><?php
                                                            if (!empty($retailer->login_datetime)) {
                                                                echo date("Y-m-d H:i:s", strtotime($retailer->login_datetime));
                                                            }
                                                            ?>

                                                            <hr/>
                                                            <?php
                                                            $inactive = 600;
                                                            $session_life = time() - $retailer->login_time;
                                                            if ($retailer->login_status == 0) {
                                                                echo '<span class="badge badge-danger">Session : In-Active</span>';
                                                            } else {
                                                                if ($session_life > $inactive) {
                                                                    echo '<span class="badge badge-danger">Session : In-Active</span>';
                                                                } else {
                                                                    echo '<span class="badge badge-success">Session : Active</span>';
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <a href="add_retailer.php<?php echo $menuURL; ?>&retailer_id=<?php echo base64_encode($retailer->id); ?>"><button class="btn btn-primary" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
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

