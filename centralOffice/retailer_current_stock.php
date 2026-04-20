<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

$retailer_id = 1;
$status = 1;
$product_id = '';
$item_code = '';
$product_title = '';
$product_category = '';
$general_category = '';
$short_description = '';
$feature_description = '';
$remarks = '';
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
                                                        ?>><?php echo $active_item->item_desc; ?> [<?php echo $active_item->item_code; ?>]</option>
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
                                                    <th>Item Code</th>
                                                    <th>Item Name</th>
                                                    <th>Description</th>
                                                    <th>Category</th>
                                                    <th>Sub Category</th>
                                                    <th>Brnad</th>
                                                    <th>HSN Code</th>
                                                    <th>Unit</th>
                                                    <th>Current Stock</th>   
                                                    <?php if ($_SESSION['username'] == 'UAAG_ADMIN') { ?>
<!--                                                        <th>Current Stock (IT)</th>   -->
                                                    <?php } ?>
                                                    <!--<th>Sale GST Rate</th>-->   
                                                    <!--<th>Sale Basic Amount</th>-->   
                                                    <th>Last Purchase Rate</th>   
                                                    <th>Last Purchase Basic Amount</th>   
                                                    <th>Status</th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 1;
                                                $products = getRetailerItemByRetailerIdItemId_for_without_batch($retailer_id, $item_code, $company_id_in);
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
                                                        <td><?php echo $product->item_code; ?></td>
                                                        <td><?php echo $product->item_desc; ?></td>
                                                        <td><?php echo getproductDescriptionById($product->item_code); ?></td>
                                                        <td><?php echo getCategoryNameById($product->main_category_id); ?></td>
                                                        <td><?php echo getCategoryNameById($product->sub_category_id); ?></td>
                                                        <td><?php echo $product->brand_name; ?></td>
                                                        <td><?php echo $product->hsn_code; ?></td>
                                                        <td><?php echo getItemUnitByItemCodee($product->item_code); ?></td>
                                                        <td><a target="_blank" href="retailer_current_free_sr_no.php?retailer_id=<?php echo base64_encode($product->retailer_id); ?>&menu=<?php echo "38"; ?>&item=<?php echo base64_encode($product->item_code); ?>"><?php echo $product->current_stock; ?></a></td>
                                                        <?php if ($_SESSION['username'] == 'UAAG_ADMIN') { ?>
                                                            <!--<td><a target="_blank" href="retailer_current_free_sr_no.php?retailer_id=<?php // echo base64_encode($product->retailer_id); ?>&menu=<?php // echo "38"; ?>&item=<?php // echo base64_encode($product->item_code); ?>"><?php // echo $product->u_current_stock; ?></a></td>-->
                                                        <?php } ?>
                                                        <!--<td><?php // echo $product->igst_rate; ?></td>-->
                                                        <!--<td><?php // echo $product->basic_price; ?></td>--> 
                                                        <td><?php echo $product->last_po_gst; ?></td> 
                                                        <td><?php echo $product->last_po_basic; ?></td> 
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

