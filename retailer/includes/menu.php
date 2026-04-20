<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$stock_transfer_pending_count = count(getPendingDispacthOrderNo($_SESSION['id']));
$purchaseOrder_count = count(getInventoryGrnDetailsById(0, $_SESSION['id']));
$expired_within_10_days = 0;
$fromDate = date("Y-m-d");
$to_date = date('Y-m-d', strtotime($fromDate . ' + 9 days'));
$expired_within_10_days = count(getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date));

$to_date = date('Y-m-d', strtotime($fromDate . ' + 19 days'));
$expired_within_20_days = count(getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date));

$to_date = date('Y-m-d', strtotime($fromDate . ' + 29 days'));
$expired_within_30_days = count(getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date));


$expired_count = count(getAlredyExpiredItems($retailer_id));

$notificatuon_total = $stock_transfer_pending_count + $purchaseOrder_count;
?>
<div class="loader" style="display: none;"></div>
<div id="navbar" class="navbar navbar-default ace-save-state">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-header pull-left">
            <a href="index.php?success=1&menu=1" class="navbar-brand">
                <small>
                    <i class="fa fa-user"></i>
                    Welcome <?php echo $retailer_details->full_name . " | " . getCompanyNameById($retailer_details->company_id); ?>
                </small>
            </a>
        </div>
        <div class="navbar-buttons navbar-header pull-right" role="navigation" >

            <ul class="nav ace-nav">
                <li class="purple dropdown-modal">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                        <span class="badge badge-important"> <?php echo $notificatuon_total; ?></span>
                    </a> 

                    <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <a href="stock_dispatch_form.php?menu=23"><i class="ace-icon fa fa-exclamation-circle"></i> 
                                <b>Stock Transfer Request : <b class="red"><?php echo $stock_transfer_pending_count; ?></b></b>
                            </a>
                        </li>
                        <li class="dropdown-header">
                            <a href="inward_po.php?menu=14"><i class="ace-icon fa fa-exclamation-circle"></i> 
                                <b>Pending Goods Received Note : <b class="red"><?php echo $purchaseOrder_count; ?></b></b>
                            </a>
                        </li>
                        <li class="dropdown-header">
                            <a href="retailer_expiry_report.php?menu=38&notification=1&filter_by=10"><i class="ace-icon fa fa-exclamation-circle"></i> 
                                <b>Stock Expire Within 10 Days : <b class="red"><?php echo $expired_within_10_days; ?></b></b>
                            </a>
                        </li>
                        <li class="dropdown-header">
                            <a href="retailer_expiry_report.php?menu=38&notification=1&filter_by=20"><i class="ace-icon fa fa-exclamation-circle"></i> 
                                <b>Stock Expire Within 20 Days : <b class="red"><?php echo $expired_within_20_days; ?></b></b>
                            </a>
                        </li>
                        <li class="dropdown-header">
                            <a href="retailer_expiry_report.php?menu=38&notification=1&filter_by=30"><i class="ace-icon fa fa-exclamation-circle"></i> 
                                <b>Stock Expire Within 30 Days : <b class="red"><?php echo $expired_within_30_days; ?></b></b>
                            </a>
                        </li>
                        <li class="dropdown-header">
                            <a href="retailer_expiry_report.php?menu=38&notification=1&filter_by=expired"><i class="ace-icon fa fa-exclamation-circle"></i> 
                                <b>Stock Expired: <b class="red"><?php echo $expired_count; ?></b></b>
                            </a>
                        </li>
                        <li class="dropdown-header">
                            <a href="price_update_history.php?menu=1"><i class="ace-icon fa fa-exclamation-circle"></i> 
                                <b>Update Price History</b></b>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="light-blue dropdown-modal">

                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="assets/images/avatars/avatar5.png" alt=""  />
                        <span class="user-info">
                            <small>Profile!</small>
                        </span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>
                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li class="divider"></li>
                        <li>
                            <a href="logout.php">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>


        </div>
    </div><!-- /.navbar-container -->
</div>