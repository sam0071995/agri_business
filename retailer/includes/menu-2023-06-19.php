<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
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
                <li class="light-blue dropdown-modal">

                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="assets/images/avatars/fta_main.png" alt="HSRP Photo"  />
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