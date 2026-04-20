<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

$retailer_id = 1;
$status = 1;
$product_id = '';
$product_title = '';
$product_category = '';
$general_category = '';
$short_description = '';
$feature_description = '';
$remarks = '';
$item_code = '';
$description = '';
$btn_name = "Submit";

if (isset($_POST['submit'])) {
    $retailer_id = $_POST['Retailer_id'];
    $item_code = $_POST['item_code'];
}

if (isset($_GET['product_id'])) {
    $product_id = base64_encode($_GET['product_id']);
    $productData = getProductDetailsById($product_id);
    $product_title = $productData->title;
    $product_nickname = $productData->nickname;
    $general_category = $productData->g_category_id;
    $product_category = $productData->category_id;
    $short_description = $productData->short_description;
    $feature_description = $productData->feature_description;
    $description = $productData->description;
    $remarks = $productData->remarks;
    $status = $productData->status;
    $btn_name = "Update";
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
                                <h3 class="page-header">Retailer | Inventory Item Details.</h3>
                                <div class="page-header">
                                    <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                        <div class="form-group" id="c_n_password_c">
                                            <div class="col-sm-4">
                                                <select class="form-field-select-2 form-control chosen-select" name="Retailer_id" required="required">
                                                    <option value="">--Select Retailer--</option>
                                                    <option value="ALL">All Retailers</option>
                                                    <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                        if ($active_sellers->id == $retailer_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_sellers->name; ?><?php
                                                                    if ($active_sellers->status == 0) {
                                                                        echo '<b class="red"> [Clossed]</b>';
                                                                    }
                                                                    ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-field-select-2 form-control chosen-select" name="item_code" required="required">
                                                    <option value="">--Select item--</option>
                                                    <option value="ALL">All Items</option>
                                                    <?php foreach (getActiveItemsList() as $active_item) { ?>
                                                        <option value="<?php echo $active_item->item_code; ?>" <?php
                                                        if ($active_item->item_code == $item_code) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_item->item_desc; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row float-sm-left">
                                            <button name="submit" class="btn btn-primary float-sm-left">Filter</button>
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
                                                    <th>Item Name</th>
                                                    <th>HSN Code</th>
                                                    <th>UOM</th>
                                                    <th>GST Rate</th>
                                                    <th>Category</th>   
                                                    <th>Stock</th>   
                                                    <th>Sales Rate</th>   
                                                    <th>Date</th>
                                                    <th>Status</th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 1;
                                                $products = getRetailerItemByRetailerIdItemId($retailer_id, $item_code, $company_id_in);
                                                foreach ($products as $product) {
                                                    $status = "";
                                                    if ($product->status == 1) {
                                                        $status .= '<span class="badge badge-success">Active</span>';
                                                    } else {
                                                        $status .= '<span class="badge badge-danger">In-Active</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                        <td><?php echo $product->item_desc; ?></td>
                                                        <td><?php echo $product->hsn_code; ?></td>
                                                        <td><?php echo $product->uom; ?></td>
                                                        <td><?php echo $product->igst_rate; ?></td>
                                                        <td>
                                                            <b class="green">Parent : <?php echo getCategoryNameById($product->main_category_id); ?></b>
                                                            <hr/>
                                                            <b class="blue">Sub : <?php echo getCategoryNameById($product->sub_category_id); ?></b>
                                                        </td>
                                                        <td>
                                                            <b class="green">Opening : <?php echo $product->opening_stock; ?></b><br/>
                                                            <b class="cyan">Receive : <?php echo $product->receive_stock; ?></b><br/>
                                                            <b class="blue">outward : <?php echo $product->issued_stock; ?></b><br/>
                                                            <b class="red">Current : <?php echo $product->current_stock; ?></b><br/>
                                                        </td>
                                                        <td>
                                                            <b class="green">BASIC : <?php echo $product->basic_price; ?></b><br/>
                                                            <b class="cyan">CGST : <?php echo $product->cgst_value; ?></b><br/>
                                                            <b class="blue">SGST : <?php echo $product->sgst_value; ?></b><br/>
                                                            <b class="red">TOTAL : <?php echo $product->total; ?></b><br/>
                                                        </td>
                                                        <td>
                                                            <?php echo date('d M Y', strtotime($product->date)); ?>
                                                        </td>
                                                        <td><?php echo $status; ?></td>
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

