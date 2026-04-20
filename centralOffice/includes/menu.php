<?php
$getOrderRequest_count = count(getApproveStockRequestIN($company_id_in));
$notificatuon_total = $getOrderRequest_count;
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
            <a href="index.php?menu=1" class="navbar-brand">
                <small>
                    <i class="fa fa-leaf"></i>
                    Welcome Central Office Team <?php
                    $assign_company_array = explode(',', $user_detail->company_id);
                    foreach ($assign_company_array as $assign_company) {
                        echo ' | ';
                        echo getCompanyNameById($assign_company);
                    }
                    ?>
                </small>
            </a>
        </div>
        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav"><li class="purple dropdown-modal">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                        <span class="badge badge-important"> <?php echo $notificatuon_total; ?></span>
                    </a> 

                    <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <a href="approve_stock_transfer.php?menu=21"><i class="ace-icon fa fa-exclamation-circle"></i> 
                                <b>Stock Transfer Request : <b class="red"><?php echo $getOrderRequest_count; ?></b></b>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="light-white dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="assets/images/avatars/avatar5.png" alt="Agro Business" />
                        <span class="user-info">
                            <small>Profile!</small>
                        </span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>
                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li class="divider"></li>
                        <li>
                            <a href="logout.php?menu=1">
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