<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

$status = 1;
$product_id = '';
$product_title = '';
$product_category = '';
$general_category = '';
$short_description = '';
$feature_description = '';
$remarks = '';
$description = '';
$btn_name = "Submit";
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
                        <div class="page-header">
                            <div class="row float-sm-left">
                                <a href="add_inventory_item.php<?php echo $menuURL; ?>" class="float-sm-left"><button class="btn btn-primary float-sm-left">New Inventory Item</button></a>
                                <a href="SEARCH_INVENTORY.php<?php echo $menuURL; ?>" class="float-sm-left"><button class="btn btn-danger float-sm-left">Search Inventory Item</button></a>
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
                                        case 101:
                                            $msg = "Image Insert Problem.";
                                            break;
                                        case 102:
                                            $msg = "Image Insert Problem. only allow 'jpg', 'jpeg', 'png', 'gif'";
                                            break;
                                        case 103:
                                            $msg = "Image Insert Problem.";
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
                                <h3 class="page-header">Inventory Item Details.</h3>
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th width="1%">SrNo</th>
                                                    <th width="5%">Item Code</th>
                                                    <th width="5%">Item Name</th>
                                                    <th width="5%">HSN Code</th>
                                                    <th width="2%">GST</th>
                                                    <th width="2%">PACKSIZE</th>
                                                    <th width="2%">UNIT</th>
                                                    <th width="2%">UOM</th>
                                                    <th width="2%">MOQ</th>
                                                    <th width="5%">Parent Category</th>   
                                                    <th width="5%">Sub Category</th>   
                                                    <th width="5%">Brand</th>   
                                                    <th width="5%">Technical Name</th>   
                                                    <th width="5%">PC Per Carton</th>   
                                                    <th width="5%">Shelf Life</th>   
                                                    <th width="5%">Description</th>   
                                                    <th width="2%">Image</th>   
                                                    <th width="2%">Status</th>   
                                                    <th width="2%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 1;
                                                $products = getProductsList();
                                                foreach ($products as $product) {
                                                    $status = "";
                                                    if ($product->status == 1) {
                                                        $status .= '<span class="badge badge-success">Active</span>';
                                                    } else {
                                                        $status .= '<span class="badge badge-danger">In-Active</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td width="1%"><?php echo $index; ?></td>
                                                        <td width="5%"><?php echo $product->item_code; ?></td>
                                                        <td width="5%"><?php echo $product->item_desc; ?></td>
                                                        <td width="5%"><?php echo $product->hsn_code; ?></td>
                                                        <td width="2%"><?php echo $product->igst_rate; ?></td>
                                                        <td width="5%"><?php echo $product->pack_size; ?></td>
                                                        <td width="5%"><?php echo $product->unit; ?></td>
                                                        <td width="2%"><?php echo $product->uom; ?></td>
                                                        <td width="2%"><?php echo $product->moq; ?></td>
                                                        <td width="5%"><b class="green"><?php echo getCategoryNameById($product->main_category_id); ?></b></td>
                                                        <td width="5%"><b class="blue"><?php echo getCategoryNameById($product->sub_category_id); ?></b></td>
                                                        <td width="5%"><?php
                                                            if (!empty($product->brand_name)) {
                                                                echo strip_tags($product->brand_name);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td width="5%"><?php
                                                            if (!empty($product->technical_name)) {
                                                                echo strip_tags($product->technical_name);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td width="5%"><?php
                                                            if (!empty($product->PC_per_carton)) {
                                                                echo strip_tags($product->PC_per_carton);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td width="5%"><?php
                                                            if (!empty($product->shelf_life)) {
                                                                echo strip_tags($product->shelf_life);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td width="5%"><?php echo strip_tags($product->description); ?></td>
                                                        <td width="5%"><?php if ($product->product_image != '0') { ?><p><a href="<?php echo $product->product_image; ?>" target="_blank">Uploaded Image</a></p><?php } ?></td>
                                                        <td width="2%"><?php echo $status; ?></td>
                                                        <td width="2%">
                                                            <a href="add_inventory_item.php<?php echo $menuURL; ?>&product_id=<?php echo base64_encode($product->id); ?>"><button class="btn btn-primary btn-sm" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a><br /><br />
                                                            <a href="add_inventory_item_copy.php<?php echo $menuURL; ?>&product_id=<?php echo base64_encode($product->id); ?>" target="_blank"><button class="btn btn-primary btn-sm" alt="Copy" >Copy</button></a>
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

