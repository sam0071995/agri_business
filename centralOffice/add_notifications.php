<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$notification_code = "";
$btn_name = "Save";
$description = "";
$status = "1";
$image = "";
$stores_array = array();

$destPath = "";
if (isset($_GET['notification_id'])) {
    $notification_code = base64_decode($_GET['notification_id']);
    $btn_name = "Update";
    $notification = getAdminproductNotificationsByCode($notification_code);
    if (isset($notification->description)) {
        $description = $notification->description;
        $status = $notification->status;
        $image = $notification->image;
        $destPath = $notification->image;
        foreach (getStoreListNotification($notification->notification_code) as $ListNotification) {
            $stores_array[] = $ListNotification->retailer_id;
        }
    }
}
if (isset($_POST['submit'])) {
    $notification_code = $_POST['notification_code'];

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Ensure this folder exists and is writable
        $fileTmpPath = $_FILES['attachment']['tmp_name'];
        $fileName = basename($_FILES['attachment']['name']);
        $fileSize = $_FILES['attachment']['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allowed extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        // Max file size: 2MB
        $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes

        if (!in_array($fileExtension, $allowedExtensions)) {
            $msg = "<p style='color:red;'>Invalid file type. Only JPG, PNG, and PDF files are allowed.</p>";
            echo '<script>alert("' . $msg . '");window.location.href="notifications.php""' . $menuURL . '""&success=1";</script>';
        } elseif ($fileSize > $maxFileSize) {
            $msg = "<p style='color:red;'>File is too large. Maximum allowed size is 2MB.</p>";
            echo '<script>alert("' . $msg . '");window.location.href="notifications.php"' . $menuURL . '"&success=1";</script>';
        } else {
            $newFileName = uniqid('file_', true) . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                $msg = "<p style='color:red;'>Error moving the uploaded file.</p>";
                echo '<script>alert("' . $msg . '");window.location.href="notifications.php"' . $menuURL . '"&success=1";</script>';
            } else {
                if (!empty($image)) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/" . $image);
                }
            }
        }
    } else {
        $msg = "<p style='color:red;'>Please upload a valid file.</p>";
        echo '<script>alert("' . $msg . '");window.location.href="notifications.php"' . $menuURL . '"&success=1";</script>';
    }


    if (empty($notification_code)) {
        $retailer_id_array = $_POST['retailer_id'];
        $notification_code = time() . generateRandomString();
        foreach ($retailer_id_array as $retailer_id) {
            $description = $_POST['description'];
            $data = array();
            $data['user_id'] = $user_id;
            $data['notification_code'] = $notification_code;
            $data['description'] = $description;
            $data['retailer_id'] = $retailer_id;
            $data['company_id'] = $company_id;
            $data['status'] = 1;
            if (!empty($destPath)) {
                $data['image'] = $destPath;
            }
            $data['datetime'] = date("Y-m-d h:i:s");
            $update = insert("notifications", $data);
        }
    } else {
        $deleteWhere = "notification_code='$notification_code'";
        delete("notifications", $deleteWhere);
        $retailer_id_array = $_POST['retailer_id'];
        foreach ($retailer_id_array as $retailer_id) {
            $description = $_POST['description'];
            $status = $_POST['status'];
            $data = array();
            $data['user_id'] = $user_id;
            $data['notification_code'] = $notification_code;
            $data['description'] = $description;
            $data['retailer_id'] = $retailer_id;
            $data['company_id'] = $company_id;
            $data['status'] = $status;
            if (!empty($destPath)) {
                $data['image'] = $destPath;
            }
            $data['datetime'] = date("Y-m-d h:i:s");
            $update = insert("notifications", $data);
        }
    }
    if ($update) {
        header("Location:notifications.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:notifications.php" . $menuURL . "&error=1");
        exit;
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
                                        <?php echo "Data Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Add Notifications.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Notifications<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="notification_code" value="<?php echo $notification_code; ?>"/>
                                            <textarea name="description" class="input_class form-control" rows="15" required="required"><?php echo $description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select Store<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6"><select class="form-field-select-2 form-control" name="retailer_id[]" multiple size="20">
                                                <option value="0">--Select Stores--</option> 
                                                <?php
                                                $activeStores = getActiveRetailerCompnyIn();
                                                if ($activeStores) {
                                                    foreach ($activeStores as $store) {
                                                        ?>
                                                        <option value="<?php echo $store->id; ?>" <?php echo in_array($store->id, $stores_array) ? "selected='selected'" : ""; ?>>
                                                            <?php echo $store->name; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>


                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Status<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="status" required="required">
                                                <option value="1" <?php
                                                if ($status == 1) {
                                                    echo "selected='selected'";
                                                }
                                                ?>>Active</option>
                                                <option value="0" <?php
                                                if ($status == 0) {
                                                    echo "selected='selected'";
                                                }
                                                ?>>In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="upload_file">
                                            Upload Image / PDF<span style="color:red"></span> :
                                        </label>
                                        <div class="col-sm-6">
                                            <input type="file" name="attachment" id="upload_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <small style="color:#999;">Allowed: JPG, PNG, PDF | Max size: 2MB</small>
                                            <?php if (!empty($image)) { ?>
                                                <small style="color:#999;"><a href="<?php echo $image; ?>" target="_blank">View Uploaded Document</a></small>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-info">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                <?php echo $btn_name; ?>
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn" type="reset">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                Reset
                                            </button>
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
