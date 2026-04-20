<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$todays_date = date("Y-m-d");
if (ltrim(date('m')) > 3) {
    $cd = date('y');
    $dd = $cd + 1;
} else {
    $dd = date('y');
    $cd = $dd - 1;
}
$fin_year = $cd . '' . $dd;
$fin_year_txt = $cd . '-' . $dd;

$fidexAssets = getActiveFixedAssetsDetail();
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>

    <body class="no-skin">
        <style>
            .marg_tp_one {
                margin-top: 10px;
            }
        </style>
        <?php require_once 'includes/menu.php'; ?>
        <div class="main-container ace-save-state" id="main-container">
            <?php require_once 'includes/left_sidebar.php'; ?>
            <div class="main-content">
                <div class="main-content-inner">
                    <?php require_once 'includes/breadcrumbs.php'; ?>
                    <div class="page-content">
                        <?php require_once 'includes/page-header.php'; ?>
                        <div class="row">
                            <div class="col-xs-6">
                                <h3 class="header">Fixed Assets Entry - Distributer <b class="red">(FIN Year - <?php echo $fin_year_txt; ?>)</b></h3>
                                <form action="" method="POST">
                                    <table class="table table-border">
                                        <tbody>
                                            <tr>
                                                <td>Item</td>
                                                <td>Available Qty</td>
                                                <td>Item</td>
                                                <td>Available Qty</td>
                                                <td>Item</td>
                                                <td>Available Qty</td>
                                            </tr>
                                            <tr>
                                                <?php
                                                $fidexAssets = getActiveFixedAssetsDetail();
                                                $index = 1;
                                                foreach ($fidexAssets as $fidexAsset) {
                                                    $qty = getRetailrFinYearAssetEntryQtyByItem($_SESSION['id'], $fidexAsset->item_code, $fin_year);
                                                    if (empty($qty)) {
                                                        $qty = 0;
                                                    }
                                                    ?>
                                                    <td class="bolder"><?php echo $fidexAsset->item_name; ?></td>
                                                    <td><input type="text" name="<?php echo $fidexAsset->id; ?>" value="<?php echo $qty; ?>"></td>

                                                    <?php
                                                    if ($index % 3 == 0) {
                                                        ?>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <?php
                                                }

                                                $index++;
                                            }
                                            ?>
                                            <tr>
                                                <td class="bolder"></td>
                                                <td><input type="submit" class="btn btn-success" name="submit" value="Save"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                                <?php
                                if (isset($_POST['submit'])) {
                                    $postArray = $_POST;
                                    $count = 0;
                                    foreach ($postArray as $key => $postData) {
                                        if ($key != 'submit') {
                                            $dataItem = getActiveFixedAssetsDetailById($key);
                                            $data = array();
                                            $data['retailer_id'] = $_SESSION['id'];
                                            $data['item_code'] = $dataItem->item_code;
                                            $data['item_name'] = $dataItem->item_name;
                                            $data['category'] = $dataItem->category;
                                            $data['qty'] = $postData;
                                            $data['fin_year'] = $fin_year;
                                            $data['date'] = date("Y-m-d H:i:s");
                                            $data['status'] = 1;
                                            $table = "retailer_fixed_asset";
                                            $itemCheckked = getRetailrFinYearAssetEntryQtyByItemCount($_SESSION['id'], $dataItem->item_code, $fin_year);
                                            if ($itemCheckked > 0) {
                                                $where = " retailer_id = '" . $_SESSION['id'] . "' and fin_year='$fin_year' and item_code='$dataItem->item_code'";
                                                $insert = update($table, $data, $where);
                                            } else {
                                                $insert = insert($table, $data);
                                            }
                                            if ($insert) {
                                                $count = $count + 1;
                                            } else {
                                                $count = $count;
                                            }
                                        }
                                    }
                                    echo '<script>alert("Items Successfully Saved.");window.location="entry_fixed_assets.php?menu=26";</script>';
                                    exit;
                                }
                                ?>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->


            <!--END MAIN WRAPPER -->

            <script type="text/javascript">
                $(document).ready(function () {
                    $("#save_buttton").hide();
                    $(".total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").show();
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".2000_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_2000 = $(".2000_total_cash").val();
                        var total_money_2000 = total_cash_2000 * 2000;
                        $(".2000_total_money").val(total_money_2000);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").show();
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".500_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_500 = $(".500_total_cash").val();
                        var total_money_500 = total_cash_500 * 500;
                        $(".500_total_money").val(total_money_500);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").css("display", "block");
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".200_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_200 = $(".200_total_cash").val();
                        var total_money_200 = total_cash_200 * 200;
                        $(".200_total_money").val(total_money_200);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").css("display", "block");
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".100_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_100 = $(".100_total_cash").val();
                        var total_money_100 = total_cash_100 * 100;
                        $(".100_total_money").val(total_money_100);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").css("display", "block");
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".50_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_50 = $(".50_total_cash").val();
                        var total_money_50 = total_cash_50 * 50;
                        $(".50_total_money").val(total_money_50);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").css("display", "block");
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".20_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_20 = $(".20_total_cash").val();
                        var total_money_20 = total_cash_20 * 20;
                        $(".20_total_money").val(total_money_20);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").css("display", "block");
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".10_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_10 = $(".10_total_cash").val();
                        var total_money_10 = total_cash_10 * 10;
                        $(".10_total_money").val(total_money_10);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").show();
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".5_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_5 = $(".5_total_cash").val();
                        var total_money_5 = total_cash_5 * 5;
                        $(".5_total_money").val(total_money_5);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").show();
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".2_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_2 = $(".2_total_cash").val();
                        var total_money_2 = total_cash_2 * 2;
                        $(".2_total_money").val(total_money_2);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").show();
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                    $(".1_total_cash").blur(function () {
                        var total_cash = $(".total_cash").val();
                        var total_cash_1 = $(".1_total_cash").val();
                        var total_money_1 = total_cash_1 * 1;
                        $(".1_total_money").val(total_money_1);
                        var total_money_currency = parseInt($(".2000_total_money").val()) + parseInt($(".500_total_money").val()) + parseInt($(".200_total_money").val()) + parseInt($(".100_total_money").val()) + parseInt($(".50_total_money").val()) + parseInt($(".20_total_money").val()) + parseInt($(".10_total_money").val()) + parseInt($(".5_total_money").val()) + parseInt($(".2_total_money").val()) + parseInt($(".1_total_money").val());
                        if (total_cash == total_money_currency) {
                            $("#save_buttton").show();
                        } else {
                            $("#save_buttton").hide();
                        }
                    });
                });
            </script>
            <?php require_once 'includes/footer.php'; ?>
        </div>
    </body>

</html>