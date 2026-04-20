<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$post_user_id = '';
if (isset($_POST['show'])) {
    if (isset($_POST['user_id'])) {
        $post_user_id = $_POST['user_id'];
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
                                            $msg = "Problem for update/assign menu";
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
                                        <?php echo "Menu Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">User | Assign Menu.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline center" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>Select User :</b>
                                                                <div class="input-group">
                                                                    <select class="form-control" name="user_id">
                                                                        <option value="">--select--</option>
                                                                        <?php foreach (getUserListforAssignNenu() as $menu) { ?>
                                                                            <option <?php
                                                                            if ($post_user_id == $menu->id) {
                                                                                echo 'selected="selected"';
                                                                            }
                                                                            ?> value="<?php echo $menu->id; ?>"><?php echo $menu->name . " ( UName : " . $menu->username . " )"; ?></option>
                                                                            <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix form-actions">
                                                            <div class="col-md-offset-3 col-md-5">
                                                                <button class="btn btn-info" type="submit" name="show" value="show">
                                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                                    Show
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
                                    <?php
                                    if (isset($_POST['show'])) {
                                        $user_id = $_POST['user_id'];
                                        if (!empty($user_id)) {
                                            $user_data = getUserDetailById($user_id);
                                            $assign_menu_string = $user_data->menu;
                                            $assign_menu_array = explode(',', $assign_menu_string);
                                            ?>
                                            <h3>Change Assign menu for user : <b class="red"><?php echo $user_data->name . " [" . $user_data->username . " ]"; ?></b></h3>
                                            <form action="" method="post">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                                <table class="table">
                                                    <tr><td colspan="2"><h4 class="blue">Forms</h4></td></tr>
                                                    <tr>
                                                        <?php
                                                        $forms_menu_lists = getSubMenuList(2);
                                                        $index = 0;
                                                        foreach ($forms_menu_lists as $forms_menu_list) {
                                                            if ($index % 4 == 0) {
                                                                echo "</tr><tr>";
                                                            }
                                                            ?>
                                                            <td><input type="checkbox" name="page_title[]" value="<?php echo $forms_menu_list->id; ?>" <?php
                                                                if (in_array($forms_menu_list->id, $assign_menu_array)) {
                                                                    echo 'checked="checked"';
                                                                }
                                                                ?> /> <?php echo $forms_menu_list->page_title; ?></td>
                                                                <?php
                                                                $index++;
                                                            }
                                                            ?>
                                                    </tr>
                                                    <tr><td colspan="2"><h4 class="blue">Reports</h4></td></tr>
                                                    <tr>
                                                        <?php
                                                        $forms_menu_lists = getSubMenuList(3);
                                                        $index = 0;
                                                        foreach ($forms_menu_lists as $forms_menu_list) {
                                                            if ($index % 4 == 0) {
                                                                echo "</tr><tr>";
                                                            }
                                                            ?>
                                                            <td><input type="checkbox" name="page_title[]" value="<?php echo $forms_menu_list->id; ?>" <?php
                                                                if (in_array($forms_menu_list->id, $assign_menu_array)) {
                                                                    echo 'checked="checked"';
                                                                }
                                                                ?> /> <?php echo $forms_menu_list->page_title; ?></td>
                                                                <?php
                                                                $index++;
                                                            }
                                                            ?>
                                                    </tr>
                                                    <tr><td colspan="2"><h4 class="blue">History</h4></td></tr>
                                                    <tr>
                                                        <?php
                                                        $forms_menu_lists = getSubMenuList(454);
                                                        $index = 0;
                                                        foreach ($forms_menu_lists as $forms_menu_list) {
                                                            if ($index % 4 == 0) {
                                                                echo "</tr><tr>";
                                                            }
                                                            ?>
                                                            <td><input type="checkbox" name="page_title[]" value="<?php echo $forms_menu_list->id; ?>" <?php
                                                                if (in_array($forms_menu_list->id, $assign_menu_array)) {
                                                                    echo 'checked="checked"';
                                                                }
                                                                ?> /> <?php echo $forms_menu_list->page_title; ?></td>
                                                                <?php
                                                                $index++;
                                                            }
                                                            ?>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <button class="btn btn-danger" type="submit" name="update_menu" value="Update">
                                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                                Update
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                    if (isset($_POST['update_menu'])) {
                                        $user_id = $_POST['user_id'];
                                        $update_menu = "";
                                        $update_menu_string = "";
                                        if (isset($_POST['page_title'])) {
                                            $update_menu = $_POST['page_title'];
                                            if (count($update_menu) != 0) {
                                                $update_menu_string = implode(",", $update_menu);
                                            }
                                        }
                                        $update_menu_string = "1,2,3," . $update_menu_string;
                                        $data = array();
                                        $data['menu'] = $update_menu_string;
                                        $where = "id='$user_id'";
                                        $update = update("user_master", $data, $where);
                                        if ($update) {
                                            print '<script>alert("Menus Successfully Assign.");window.location="user_assign_menu.php' . $menuURL . '&success=1";</script>';
                                            exit;
                                        } else {
                                            print '<script>alert("Sorry, something Wrong.");window.location="user_assign_menu.php' . $menuURL . '&error=1";</script>';
                                            exit;
                                        }
                                    }
                                    ?>
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

