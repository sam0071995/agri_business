<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

$po_details = getPurchaseOrderByPoId(base64_decode($_GET['po_id']));

if (isset($_POST['submit'])) {
    $PO_id = $_POST['PO_id'];

        $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
        //exit;
        // Allowed extensions
        $allowedExt = array(
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'odt', 'ods', 'odp','pdf'
        );

        if (isset($_FILES['po_invoice_copy']) && $_FILES['po_invoice_copy']['error'] == 0) {

            $fileName = $_FILES['po_invoice_copy']['name'];
            $fileTmp = $_FILES['po_invoice_copy']['tmp_name'];
            $fileSize = $_FILES['po_invoice_copy']['size'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));


            // 1?? Check file size
            if ($fileSize > $maxFileSize) {
                echo "<script>alert('Error: File size must be less than 2MB');window.location = window.location;</script>";
                exit;
            }

            // 2?? Check file type
            if (!in_array($fileExt, $allowedExt)) {
                echo "<script>alert('Error: Only images and ODF files are allowed.');window.location = window.location;</script>";
                exit;
            }

            // 3?? Secure file name
            $newFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "", $fileName);

            $uploadDir = "challan_copy/";

            // Create folder if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $uploadPath = $uploadDir . $newFileName;

            // 4?? Move file
            if (move_uploaded_file($fileTmp, $uploadPath)) {
                $data = array();
                $data['invoice_copy'] = $newFileName;
                $data['invoice_copy_remarks'] = $newFileName . "Upload for PO id : " . $PO_id;
                $data['update_datetime'] = date("Y-m-d h:i:s");
                $where = "id = '" . $PO_id . "'";
                $update = update('purchase_order', $data, $where);
                echo "<script>alert('File uploaded successfully!');window.location = 'purchase_order.php?menu=458';</script>";
                exit;
            } else {
                echo "<script>alert('Error uploading file');window.location = window.location;</script>";
                exit;
            }
        } else {
            echo "<script>alert('Please select a file.');window.location = window.location;</script>";
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
                        <div class="page-header">
                            <div class="row float-sm-left">
                                <a href="purchase_order.php?menu=458" class="float-sm-left"><button class="btn btn-primary float-sm-left">Back</button></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">

                                <h3 class="page-header">Update Invoice for PO Number.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">PO No<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <?php echo $po_details->po_no; ?>
                                            <input type="hidden" name="PO_id"  class="input_class form-control"  value="<?php echo $po_details->id; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">PO Date<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <?php echo date('d-M-Y', strtotime($po_details->po_date)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">PO Supplier<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <?php echo $po_details->supplier_id; ?>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Retailer<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <?php echo getRetailerNameById($po_details->retailer_id); ?>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Total Amount<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <?php echo $po_details->grand_total; ?>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Upload Purchase Invoice<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="file" name="po_invoice_copy" required="required" />
                                            <?php if ($po_details->invoice_copy != '0') { ?>
                                                <br/>
                                                <a href="challan_copy/<?php echo $po_details->invoice_copy; ?>" target="_blank">
                                                    View File
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-info">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Upload
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
