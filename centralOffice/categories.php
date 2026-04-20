<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$category_id = "";
$cotegory_name = "";
//$btn_name = "Save";
$status = "1";
$general = "1";
// if (isset($_GET['category_id'])) {
//     $category_id = base64_decode($_GET['category_id']);
//     $btn_name = "Update";
//     $category = getCategoriesById($category_id, $general);
//     $cotegory_name = $category->name;
//     $status = $category->status;
// }
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
                                <a href="add_category.php?menu=4" class="float-sm-left"><button class="btn btn-primary float-sm-left">New Category</button></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 2:
                                            $msg = "Image must be less than 2MB.";
                                            break;
                                        case 1:
                                            $msg = "Sorry, only PNG Image is allowed..";
                                            break;
                                        case 3:
                                            $msg = "Sorry, Image Not Uploaded.";
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
                                        <?php echo "Category Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Item Categories.</h3>
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>

                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th class="green">Category Name</th>
                                                    <th class="red">Parent Category</th>
                                                    <th>Status</th>   
                                                    <th>Date</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 1;
                                                $categories = getAdminproductCategories($general);
                                                foreach ($categories as $category) {
                                                    if ($category->status == 1) {
                                                        $status = '<span class="badge badge-success">Active</span>';
                                                    } else {
                                                        $status = '<span class="badge badge-danger">In-Active</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td class="green"><?php echo $category->name; ?></td>
                                                        <td class="red"><?php echo getCategoryNameById($category->parent_category); ?></td>
                                                        <td><?php echo $status; ?></td>
                                                        <td><?php echo date('d M Y', strtotime($category->datetime)); ?></td>
                                                        <td>
                                                            <a href="add_category.php?menu=4&category_id=<?php echo base64_encode($category->id); ?>"><button class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                                            <!--<a href="delete_category.php?menu=4&category_id=<?php // echo base64_encode($category->id);  ?>"><button class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></a>-->                                                        
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
