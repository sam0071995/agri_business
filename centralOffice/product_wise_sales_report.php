<?php
// phpinfo();
// exit;
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
if (isset($_POST['show'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
    $retailer_id = 'All';
    if (isset($_POST['Retailer_id'])) {
        $retailer_id = $_POST['Retailer_id'];
    }
    if (isset($_POST['item_code'])) {
        $item_code = $_POST['item_code'];
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
                            <div class="page-header">
                                <div class="widget-box">
                                    <div class="widget-header">
                                        <h4 class="widget-title">Retailer | Product wise Sales Report.</h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <form class="form-inline center" action="" method="POST">
                                                <div class="row">
                                                    <div class="form-group col-xs-3">
                                                        <input class="form-control date-picker" id="id-" name="date_1"
                                                            type="text"
                                                            value="<?php
                                                                    if (isset($_POST['date_1'])) {
                                                                        echo $_POST['date_1'];
                                                                    } else {
                                                                        echo date('d-m-Y');
                                                                    }
                                                                    ?>"
                                                            data-date-format="dd-mm-yyyy" />
                                                    </div>

                                                    <div class="form-group col-xs-3">
                                                        <input class="form-control date-picker" id="" name="date_2"
                                                            type="text"
                                                            value="<?php
                                                                    if (isset($_POST['date_2'])) {
                                                                        echo $_POST['date_2'];
                                                                    } else {
                                                                        echo date('d-m-Y');
                                                                    }
                                                                    ?>"
                                                            data-date-format="dd-mm-yyyy" />
                                                    </div>

                                                    <div class="form-group col-xs-2">
                                                        <select class="form-control" multiple name="Retailer_id[]"
                                                            id="Retailer_id">
                                                            <option value="0">All</option>
                                                            <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                <option value="<?php echo $active_sellers->id; ?>">
                                                                    <?php echo $active_sellers->name; ?></option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>

                                                    <div class="form-group col-xs-2">
                                                        <select class="form-control" name="category_id"
                                                            id="category_id">
                                                            <option value="">All category</option>
                                                            <?php foreach (getParentActiveCategories() as $category) { ?>
                                                                <option value="<?php echo $category->id; ?>">
                                                                    <?php echo $category->name; ?></option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="clearfix form-actions">
                                                    <div class="col-md-offset-3 col-md-5">
                                                        <button class="btn btn-info" type="button" name="show"
                                                            value="show" id="filter_product">
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            Show
                                                        </button>

                                                        &nbsp; &nbsp; &nbsp;
                                                        <button class="btn" type="reset" id="reset_filter_product">
                                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                                            Reset
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <?php if (isset($_POST['show'])) { ?>
                                    <!--                                        <h5 class="red">Total sale amount between <?php // echo $date_1;                                                                                   
                                                                                                                            ?> and <?php // echo $date_2;                                                                                   
                                                                                                                                    ?> is : <b class="blue"><?php // echo IND_money_format(getProductSalesTotalAmtByRetailerTempTable($date_1, $date_2, $retailer_id, $company_id_in));                                                                                   
                                                                                                                                                            ?> Rs.</b></h5>-->
                                <?php } ?>
                                <div class="row clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div>
                                    <table id="dynamic-tables" class="table table-bordered table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Retailer Name</th>
                                                <th>Customer Name</th>
                                                <th>Invoice No</th>
                                                <th>Item Name</th>
                                                <th>Item Brand</th>
                                                <th>HSNCODE</th>
                                                <th>Category</th>
                                                <th>Sub Category</th>
                                                <th>PaymentType</th>
                                                <th>GST Rate</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Unit</th>
                                                <th>Taxable Value</th>
                                                <th>CGST</th>
                                                <th>SGST</th>
                                                <th>IGST</th>
                                                <th>Total</th>
                                                <th>DiscountAmount</th>
                                                <th>CouponCode</th>
                                                <th>Fincial Year</th>
                                                <th>BillDate</th>
                                                <th>BillTime</th>
                                                <th>Order Type</th>
                                                <th>Credit Note</th>
                                                <th>Return Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
        <script type="text/javascript">
            $('#Retailer_id').multiselect({

                columns: 1, // how many columns should be use to show options
                search: true, // include option search box
                texts: {
                    placeholder: '-- Select Distributer --', // text to use in dummy input
                },
                selectAll: true
            });
            $('#item_code').multiselect({

                columns: 1, // how many columns should be use to show options
                search: true, // include option search box
                texts: {
                    placeholder: '-- Select Item --', // text to use in dummy input
                },
                selectAll: true
            });


            let myTable;

            $(document).ready(function() {
                if ($('#dynamic-tables').length) {
                    myTable = $('#dynamic-tables').DataTable({
                        serverSide: true,
                        processing: true,
                        pageLength: 20,
                        searching: true,
                        ordering: true,
                        "scrollX": true,
                        searchDelay: 500,
                        ajax: {
                            url: './api/api_product_sales.php',
                            type: 'POST',
                            data: function(d) {
                                d.date_1 = $('input[name="date_1"]').val();
                                d.date_2 = $('input[name="date_2"]').val();
                                d.category_id = $('#category_id').val();
                                d.retailer_id = $('#Retailer_id').val();
                                d.company_id = "<?php echo $company_id_in; ?>";
                            }
                        },
                        columns: [{
                                data: 'index'
                            },
                            {
                                data: 'retailer_fullname'
                            },
                            {
                                data: 'cus_name'
                            },
                            {
                                data: 'order_no'
                            },
                            {
                                data: 'item_name'
                            },
                            {
                                data: 'brand_name'
                            },
                            {
                                data: 'hsn_code'
                            },
                            {
                                data: 'main_category_name'
                            },
                            {
                                data: 'sub_category_name'
                            },
                            {
                                data: 'payment_type'
                            },
                            {
                                data: 'gst_rate'
                            },
                            {
                                data: 'qty'
                            },
                            {
                                data: 'rate'
                            },
                            {
                                data: 'unit_name'
                            },
                            {
                                data: 'basic'
                            },
                            {
                                data: 'cgst'
                            },
                            {
                                data: 'sgst'
                            },
                            {
                                data: 'igst'
                            },
                            {
                                data: 'total'
                            },
                            {
                                data: 'discount_amount'
                            },
                            {
                                data: 'coupon_code'
                            },
                            {
                                data: 'fin_year'
                            },
                            {
                                data: 'bill_date'
                            },
                            {
                                data: 'bill_time'
                            },
                            {
                                data: 'order_type'
                            },
                            {
                                data: 'credit_note_no'
                            },
                            {
                                data: 'return_qty'
                            }
                        ],
                        columnDefs: [{
                            orderable: false,
                            targets: 0
                        }],
                        dom: 'Bfrtip',
                        buttons: GetTableButtons()
                    });
                    applyCommonTableFeatures(myTable);
                }
            });
            $('#filter_product').on('click', function() {
                myTable.ajax.reload();
            });
            $('#reset_filter_product').on('click', function() {
                setTimeout(() => {
                    myTable.ajax.reload();
                }, 100);
            })
        </script>
    </div>
</body>

</html>