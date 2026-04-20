<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$todays_date = date("Y-m-d");
$book_entry = getTodayDayBookEntry($_SESSION['id'], $todays_date);
$total_amount = 0;
$c_2000 = 0;
$c_500 = 0;
$c_200 = 0;
$c_100 = 0;
$c_50 = 0;
$c_20 = 0;
$c_10 = 0;
if (isset($book_entry->id)) {
    $total_amount = $book_entry->total_amount;
    $c_2000 = $book_entry->c_2000;
    $c_500 = $book_entry->c_500;
    $c_200 = $book_entry->c_200;
    $c_100 = $book_entry->c_100;
    $c_50 = $book_entry->c_50;
    $c_20 = $book_entry->c_20;
    $c_10 = $book_entry->c_10;
}
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
                                <h3 class="header">Day Book Entry - Distributer</h3>
                                <form action="" method="POST">
                                    <table class="table table-border">
                                        <tbody>
                                            <tr>
                                                <td colspan="5">Total Amount in hand : <b>Rs.</b> 
                                                    <input type="text" name="total_cash" class="total_cash" value="<?php echo $book_entry->total_amount; ?>">

                                                    <?php
                                                    if (isset($book_entry->id)) {
                                                        ?>
                                                        <input type="hidden" name="update_id" value="<?php echo $book_entry->id; ?>">
                                                        <?php
                                                    }
                                                    ?>
                                                <td>
                                            </tr>
                                            <tr>
                                                <td>Particular</td>
                                                <td>X</td>
                                                <td>Currency Notes</td>
                                                <td>=</td>
                                                <td>Currency Total</td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">2000</td>
                                                <td>X</td>
                                                <td><input type="text" name="2000_total_cash" class="2000_total_cash" value="<?php echo $book_entry->c_2000; ?>"></td>
                                                <td class="2000_money">=</td>
                                                <td><input type="text" name="2000_total_money" class="2000_total_money" value="<?php echo $book_entry->m_2000; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">500</td>
                                                <td>X</td>
                                                <td><input type="text" name="500_total_cash" class="500_total_cash" value="<?php echo $book_entry->c_500; ?>"></td>
                                                <td class="500_money">=</td>
                                                <td><input type="text" name="500_total_money" class="500_total_money" value="<?php echo $book_entry->m_500; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">200</td>
                                                <td>X</td>
                                                <td><input type="text" name="200_total_cash" class="200_total_cash" value="<?php echo $book_entry->c_200; ?>"></td>
                                                <td class="200_money">=</td>
                                                <td><input type="text" name="200_total_money" class="200_total_money" value="<?php echo $book_entry->m_200; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">100</td>
                                                <td>X</td>
                                                <td><input type="text" name="100_total_cash" class="100_total_cash" value="<?php echo $book_entry->c_100; ?>"></td>
                                                <td class="100_money">=</td>
                                                <td><input type="text" name="100_total_money" class="100_total_money" value="<?php echo $book_entry->m_100; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">50</td>
                                                <td>X</td>
                                                <td><input type="text" name="50_total_cash" class="50_total_cash" value="<?php echo $book_entry->c_50; ?>"></td>
                                                <td class="50_money">=</td>
                                                <td><input type="text" name="50_total_money" class="50_total_money" value="<?php echo $book_entry->m_50; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">20</td>
                                                <td>X</td>
                                                <td><input type="text" name="20_total_cash" class="20_total_cash" value="<?php echo $book_entry->c_20; ?>"></td>
                                                <td class="20_money">=</td>
                                                <td><input type="text" name="20_total_money" class="20_total_money" value="<?php echo $book_entry->m_20; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">10</td>
                                                <td>X</td>
                                                <td><input type="text" name="10_total_cash" class="10_total_cash" value="<?php echo $book_entry->c_10; ?>"></td>
                                                <td class="10_money">=</td>
                                                <td><input type="text" name="10_total_money" class="10_total_money" value="<?php echo $book_entry->m_10; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">5</td>
                                                <td>X</td>
                                                <td><input type="text" name="5_total_cash" class="5_total_cash" value="<?php echo $book_entry->c_5; ?>"></td>
                                                <td class="5_money">=</td>
                                                <td><input type="text" name="5_total_money" class="5_total_money" value="<?php echo $book_entry->m_5; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">2</td>
                                                <td>X</td>
                                                <td><input type="text" name="2_total_cash" class="2_total_cash" value="<?php echo $book_entry->c_2; ?>"></td>
                                                <td class="2_money">=</td>
                                                <td><input type="text" name="2_total_money" class="2_total_money" value="<?php echo $book_entry->m_2; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder">1</td>
                                                <td>X</td>
                                                <td><input type="text" name="1_total_cash" class="1_total_cash" value="<?php echo $book_entry->c_1; ?>"></td>
                                                <td class="1_money">=</td>
                                                <td><input type="text" name="1_total_money" class="1_total_money" value="<?php echo $book_entry->m_1; ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="bolder"></td>
                                                <td></td>
                                                <td><input type="submit" class="btn btn-success" id="save_buttton" name="submit" value="Save"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                                <?php
                                if (isset($_POST['submit'])) {
                                    $total_cash = $_POST['total_cash'];
                                    $total_cash_2000 = $_POST['2000_total_cash'];
                                    $total_money_2000 = $_POST['2000_total_money'];
                                    $total_cash_500 = $_POST['500_total_cash'];
                                    $total_money_500 = $_POST['500_total_money'];
                                    $total_cash_200 = $_POST['200_total_cash'];
                                    $total_money_200 = $_POST['200_total_money'];
                                    $total_cash_100 = $_POST['100_total_cash'];
                                    $total_money_100 = $_POST['100_total_money'];
                                    $total_cash_50 = $_POST['50_total_cash'];
                                    $total_money_50 = $_POST['50_total_money'];
                                    $total_cash_20 = $_POST['20_total_cash'];
                                    $total_money_20 = $_POST['20_total_money'];
                                    $total_cash_10 = $_POST['10_total_cash'];
                                    $total_money_10 = $_POST['10_total_money'];
                                    $total_cash_5 = $_POST['5_total_cash'];
                                    $total_money_5 = $_POST['5_total_money'];
                                    $total_cash_2 = $_POST['2_total_cash'];
                                    $total_money_2 = $_POST['2_total_money'];
                                    $total_cash_1 = $_POST['1_total_cash'];
                                    $total_money_1 = $_POST['1_total_money'];

                                    $table = "day_book_entry";
                                    $data = array();
                                    $data['retailer_id'] = $_SESSION['id'];
                                    $data['total_amount'] = $total_cash;
                                    $data['c_2000'] = $total_cash_2000;
                                    $data['m_2000'] = $total_money_2000;
                                    $data['c_500'] = $total_cash_500;
                                    $data['m_500'] = $total_money_500;
                                    $data['c_200'] = $total_cash_200;
                                    $data['m_200'] = $total_money_200;
                                    $data['c_100'] = $total_cash_100;
                                    $data['m_100'] = $total_money_100;
                                    $data['c_50'] = $total_cash_50;
                                    $data['m_50'] = $total_money_50;
                                    $data['c_20'] = $total_cash_20;
                                    $data['m_20'] = $total_money_20;
                                    $data['c_10'] = $total_cash_10;
                                    $data['m_10'] = $total_money_10;
                                    $data['c_5'] = $total_cash_5;
                                    $data['m_5'] = $total_money_5;
                                    $data['c_2'] = $total_cash_2;
                                    $data['m_2'] = $total_money_2;
                                    $data['c_1'] = $total_cash_1;
                                    $data['m_1'] = $total_money_1;
                                    $data['status'] = 1;
                                    $data['date'] = $todays_date;
                                    $data['datetime'] = date("Y-m-d H:i:s");
                                    if (isset($_POST['update_id'])) {
                                        $update_id = $_POST['update_id'];
                                        $where = "id='$update_id'";
                                        $insert = update($table, $data, $where);
                                    } else {
                                        $insert = insert($table, $data);
                                    }
                                    if ($insert) {
                                        echo '<script>alert("Successfully Saved.");window.location="index.php?menu=26";</script>';
                                    } else {
                                        echo '<script>alert("Error.");window.location="index.php?menu=26";</script>';
                                    }
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