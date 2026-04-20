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
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- <div class="row">
                                <div class="align-right">
                                    <a href="close_purchase_goods_order.php?menu=7"><button class="btn btn-danger">Closed Order</button></a>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-xs-12">

                                    <div class="row">
                                        <h3 class="header-text">Inward Purchase Order.</h3>
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="modal-body">
                                                    <div class="row clearfix">
                                                        <div class="pull-right tableTools-container"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <table id="dynamic-table" class="table table-bordered table-hover">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th width="8%" align="left">#</th>
                                                                <th width="15%" align="left">Purchase No</th>
                                                                <th width="15%" align="left">ItemName</th>
                                                                <th width="25%" align="left">Supplier</th>
                                                                <th width="15%" align="left">ItemCount</th>
                                                                <th width="25%" align="left">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $status = 0;
                                                            $i = 1;
                                                            $purchaseOrder = getInventoryGrnDetailsById($status, $_SESSION['id']);
                                                            if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                foreach ($purchaseOrder as $row) {
                                                            ?>
                                                                    <tr>
                                                                        <td><?php echo $i; ?></td>
                                                                        <td><?php echo $row->po_no; ?></td>
                                                                        <td><?php echo $row->item_desc; ?></td>
                                                                        <td><?php echo $row->supplier_name; ?></td>
                                                                        <td><?php echo $row->billed_qty; ?></td>
                                                                        <td width='300'>
                                                                            <button type='button' class='button btn-success' style='cursor:pointer' onclick="inward_item('<?php echo $row->id; ?>');" title='Inward'>Inward</button>
                                                                        </td>
                                                                    </tr>
                                                            <?php
                                                                    $i++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.row -->
                                </div>
                            </div><!-- /.row -->
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->

        <script type="text/javascript">
            

            function inward_item(id) {
                alert(id);
                if (confirm("Are you sure you want to Inward this?")) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_inward; ?>',
                        data: {
                            'id': id,
                            'request_type': 'inward_grn'
                        },
                        success: function(result) {
                            result = $.trim(result);
                            if (result == 0) {;
                                alert('Your Item Inward Successfully...!!');
                            } else {
                                alert('Item Inward Error...!!');
                            }
                        }
                    });
                }
            }

          
        </script>
        <!--END MAIN WRAPPER -->
        <?php require_once 'includes/footer.php'; ?>

    </div>
</body>

</html>