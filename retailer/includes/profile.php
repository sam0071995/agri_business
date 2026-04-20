<?php
require_once 'includes/common_function.php';
$dealerData = getDealerById($_SESSION['dealer_id']);
$CREDIT = getDealerOpBal($_SESSION['dealer_code']);
$ddmminData = getddmMinBal();
$avail = $CREDIT - $ddmminData->minbal;
$lastTraDate = getDealerLastTra($_SESSION['dealer_code']);
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>
    <body class="no-skin">
        <?php require_once 'includes/menu.php'; ?>
        <div class="main-container ace-save-state" id="main-container">
<!--            <script type="text/javascript">
                try {
                    ace.settings.loadState('main-container')
                } catch (e) {
                }
            </script>-->

            <?php require_once 'includes/left_sidebar.php'; ?>

            <div class="main-content">
                <div class="main-content-inner">
                    <?php require_once 'includes/breadcrumbs.php'; ?>
                    <div class="page-content">
                        <?php require_once 'includes/page-header.php'; ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <div class="alert alert-block alert-success">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>

                                    <i class="ace-icon fa fa-check green"></i>
                                    All Dealers are hereby intimated that due to the price revision of High Security Registration Plates (HSRP) and introduction of Krishi Kalyan Cess (KKC) , the prices of HSRP will be revised w.e.f 1st June 2016. Updated HSRP Price List
                                </div>

                                <div class="row">
                                    <div class="space-6"></div>

                                    <div class="col-sm-7 infobox-container">
                                        <div class="infobox infobox-green">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-comments"></i>
                                            </div>

                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $_SESSION['dealer_code']; ?></span>
                                                <div class="infobox-content">Dealer Code</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-blue">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-twitter"></i>
                                            </div>

                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo "2354" . str_replace('-', '', $_SESSION['dealer_code']); ?></span>
                                                <div class="infobox-content">eWallet A/C No</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-pink">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-shopping-cart"></i>
                                            </div>

                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $ddmminData->minbal; ?> /-</span>
                                                <div class="infobox-content">Minimum balance in account</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-red">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-flask"></i>
                                            </div>

                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo (getDealerOpBal($_SESSION['dealer_code']) == 0 ? '0.00' : number_format(getDealerOpBal($_SESSION['dealer_code']), 2) . " / -"); ?></span>
                                                <div class="infobox-content">Total Balance Rs.</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-orange2">
                                            <div class="infobox-chart">
                                                <span class="sparkline" data-values="196,128,202,177,154,94,100,170,224"></span>
                                            </div>

                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo ($CREDIT == 0 ? '0.00' : number_format($avail, 2) . " / -"); ?></span>
                                                <div class="infobox-content">Available Balance Rs.</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-blue2">
                                            <div class="infobox-progress">
                                                <div class="easy-pie-chart percentage" data-percent="42" data-size="46">
                                                    <span class="percent">42</span>%
                                                </div>
                                            </div>

                                            <div class="infobox-data">
                                                <span class="infobox-text"><?php echo ($lastTraDate != '' ? date('d-M-Y', strtotime($lastTraDate)) : 'N/A'); ?></span>

                                                <div class="infobox-content">
                                                    <span class="bigger-110">~</span>
                                                    Last Transaction Date
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-6"></div>

                                        <div class="infobox infobox-green infobox-small infobox-dark">
                                            <div class="infobox-progress">
                                                <div class="easy-pie-chart percentage" data-percent="61" data-size="39">
                                                    <span class="percent">61</span>%
                                                </div>
                                            </div>

                                            <div class="infobox-data">
                                                <div class="infobox-content">Task</div>
                                                <div class="infobox-content">Completion</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-blue infobox-small infobox-dark">
                                            <div class="infobox-chart">
                                                <span class="sparkline" data-values="3,4,2,3,4,4,2,2"></span>
                                            </div>

                                            <div class="infobox-data">
                                                <div class="infobox-content">Earnings</div>
                                                <div class="infobox-content">$32,000</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-grey infobox-small infobox-dark">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-download"></i>
                                            </div>

                                            <div class="infobox-data">
                                                <div class="infobox-content">Downloads</div>
                                                <div class="infobox-content">1,205</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="vspace-12-sm"></div>

                                    <div class="col-sm-5">
                                        <div class="widget-box">
                                            <div class="widget-header widget-header-flat widget-header-small">
                                                <h5 class="widget-title">
                                                    <i class="ace-icon fa fa-signal"></i>
                                                    Traffic Sources
                                                </h5>

                                                <div class="widget-toolbar no-border">
                                                    <div class="inline dropdown-hover">
                                                        <button class="btn btn-minier btn-primary">
                                                            This Week
                                                            <i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
                                                            <li class="active">
                                                                <a href="#" class="blue">
                                                                    <i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
                                                                    This Week
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="#">
                                                                    <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                                    Last Week
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="#">
                                                                    <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                                    This Month
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="#">
                                                                    <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                                    Last Month
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div id="piechart-placeholder"></div>

                                                    <div class="hr hr8 hr-double"></div>

                                                    <div class="clearfix">
                                                        <div class="grid3">
                                                            <span class="grey">
                                                                <i class="ace-icon fa fa-facebook-square fa-2x blue"></i>
                                                                &nbsp; likes
                                                            </span>
                                                            <h4 class="bigger pull-right">1,255</h4>
                                                        </div>

                                                        <div class="grid3">
                                                            <span class="grey">
                                                                <i class="ace-icon fa fa-twitter-square fa-2x purple"></i>
                                                                &nbsp; tweets
                                                            </span>
                                                            <h4 class="bigger pull-right">941</h4>
                                                        </div>

                                                        <div class="grid3">
                                                            <span class="grey">
                                                                <i class="ace-icon fa fa-pinterest-square fa-2x red"></i>
                                                                &nbsp; pins
                                                            </span>
                                                            <h4 class="bigger pull-right">1,050</h4>
                                                        </div>
                                                    </div>
                                                </div><!-- /.widget-main -->
                                            </div><!-- /.widget-body -->
                                        </div><!-- /.widget-box -->
                                    </div><!-- /.col -->
                                </div><!-- /.row -->

                                <div class="hr hr32 hr-dotted"></div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="widget-box transparent" id="recent-box">
                                            <div class="widget-header">
                                                <h4 class="widget-title lighter smaller">
                                                    <i class="ace-icon fa fa-rss orange"></i>More Details
                                                </h4>

                                                <div class="widget-toolbar no-border">
                                                    <ul class="nav nav-tabs" id="recent-tab">
                                                        <li class="active">
                                                            <a data-toggle="tab" href="#task-tab">NECESSARY STEPS</a>
                                                        </li>

                                                        <li>
                                                            <a data-toggle="tab" href="#member-tab">CONTACT US</a>
                                                        </li>

                                                        <li>
                                                            <a data-toggle="tab" href="#comment-tab">My Address</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="widget-body">
                                                <div class="widget-main padding-4">
                                                    <div class="tab-content padding-8">
                                                        <div id="task-tab" class="tab-pane active">
                                                            <h4 class="smaller lighter green">
                                                                <i class="ace-icon fa fa-list"></i>
                                                                Follow Below Steps:
                                                            </h4>

                                                            <ul id="tasks" class="item-list">
                                                                <li class="item-orange clearfix">
                                                                    <label class="inline">
                                                                        <span class="lbl"> Deposit/Transfer amount into bank.</span>
                                                                    </label>
                                                                </li>

                                                                <li class="item-red clearfix">
                                                                    <label class="inline">
                                                                        <span class="lbl"> Fill DUR Form.</span>
                                                                    </label>
                                                                </li>

                                                                <li class="item-default clearfix">
                                                                    <label class="inline">
                                                                        <span class="lbl"> Check Status in DUR Report.</span>
                                                                    </label>
                                                                </li>

                                                                <li class="item-blue clearfix">
                                                                    <label class="inline">
                                                                        <span class="lbl"> Generate HSRP Advance Receipt.</span>
                                                                    </label>
                                                                </li>

                                                                <li class="item-grey clearfix">
                                                                    <label class="inline">
                                                                        <span class="lbl"> Check Transaction Report.</span>
                                                                    </label>
                                                                </li>

                                                                <li class="item-green clearfix">
                                                                    <label class="inline">
                                                                        <span class="lbl"> Check Passbook Report.</span>
                                                                    </label>
                                                                </li>

                                                                <li class="item-pink clearfix">
                                                                    <label class="inline">
                                                                        <span class="lbl"> For more details : <a href="ewallet_process.pptx" target="_blank">Download ewallet Docket</a></span>
                                                                    </label>
                                                                </li>
                                                            </ul>

                                                            <div class="hr hr-double hr8"></div>
                                                        </div>

                                                        <div id="member-tab" class="tab-pane">
                                                            <div class="clearfix">
                                                                <div class="itemdiv">
                                                                    <span><b>
                                                                            FTA HSRP Solutions Pvt. Ltd.<br/>
                                                                            Mobile No. :(+91) <?php echo getZonelMobile($_SESSION['zone_id']); ?><br/>
                                                                            Mail Us :  dealers.ewallet@hsrpgujarat.com 
                                                                        </b></span>
                                                                </div>
                                                                <div class="hr hr-double hr8"></div>
                                                            </div>
                                                        </div><!-- /.#member-tab -->

                                                        <div id="comment-tab" class="tab-pane">
                                                            <div class="comments">
                                                                <div class="itemdiv commentdiv">
                                                                    <div class="body">
                                                                        <b>My Address : </b>
                                                                        <div class="text">
                                                                            <i class="ace-icon fa fa-quote-left"></i>
                                                                            <?php echo ucwords($dealerData->dealer_address); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="hr hr-double hr8"></div>
                                                        </div>
                                                    </div>
                                                </div><!-- /.widget-main -->
                                            </div><!-- /.widget-body -->
                                        </div><!-- /.widget-box -->
                                    </div><!-- /.col -->

                                    <div class="col-sm-6">
                                        <div class="widget-box">
                                            <div class="widget-header">
                                                <h4 class="widget-title lighter smaller">
                                                    <i class="ace-icon fa fa-comment blue"></i>
                                                    Conversation
                                                </h4>
                                            </div>

                                            <div class="widget-body">
                                                <div class="widget-main no-padding">
                                                    <div class="dialogs">
                                                        <div class="itemdiv dialogdiv">
                                                            <div class="user">
                                                                <img alt="Alexa's Avatar" src="assets/images/avatars/avatar1.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">4 sec</span>
                                                                </div>

                                                                <div class="name">
                                                                    <a href="#">Alexa</a>
                                                                </div>
                                                                <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis.</div>

                                                                <div class="tools">
                                                                    <a href="#" class="btn btn-minier btn-info">
                                                                        <i class="icon-only ace-icon fa fa-share"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv dialogdiv">
                                                            <div class="user">
                                                                <img alt="John's Avatar" src="assets/images/avatars/avatar.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="blue">38 sec</span>
                                                                </div>

                                                                <div class="name">
                                                                    <a href="#">John</a>
                                                                </div>
                                                                <div class="text">Raw denim you probably haven&#39;t heard of them jean shorts Austin.</div>

                                                                <div class="tools">
                                                                    <a href="#" class="btn btn-minier btn-info">
                                                                        <i class="icon-only ace-icon fa fa-share"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv dialogdiv">
                                                            <div class="user">
                                                                <img alt="Bob's Avatar" src="assets/images/avatars/user.jpg" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="orange">2 min</span>
                                                                </div>

                                                                <div class="name">
                                                                    <a href="#">Bob</a>
                                                                    <span class="label label-info arrowed arrowed-in-right">admin</span>
                                                                </div>
                                                                <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis.</div>

                                                                <div class="tools">
                                                                    <a href="#" class="btn btn-minier btn-info">
                                                                        <i class="icon-only ace-icon fa fa-share"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv dialogdiv">
                                                            <div class="user">
                                                                <img alt="Jim's Avatar" src="assets/images/avatars/avatar4.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="grey">3 min</span>
                                                                </div>

                                                                <div class="name">
                                                                    <a href="#">Jim</a>
                                                                </div>
                                                                <div class="text">Raw denim you probably haven&#39;t heard of them jean shorts Austin.</div>

                                                                <div class="tools">
                                                                    <a href="#" class="btn btn-minier btn-info">
                                                                        <i class="icon-only ace-icon fa fa-share"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv dialogdiv">
                                                            <div class="user">
                                                                <img alt="Alexa's Avatar" src="assets/images/avatars/avatar1.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">4 min</span>
                                                                </div>

                                                                <div class="name">
                                                                    <a href="#">Alexa</a>
                                                                </div>
                                                                <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>

                                                                <div class="tools">
                                                                    <a href="#" class="btn btn-minier btn-info">
                                                                        <i class="icon-only ace-icon fa fa-share"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <form>
                                                        <div class="form-actions">
                                                            <div class="input-group">
                                                                <input placeholder="Type your message here ..." type="text" class="form-control" name="message" />
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-sm btn-info no-radius" type="button">
                                                                        <i class="ace-icon fa fa-share"></i>
                                                                        Send
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div><!-- /.widget-main -->
                                            </div><!-- /.widget-body -->
                                        </div><!-- /.widget-box -->
                                    </div><!-- /.col -->
                                </div><!-- /.row -->

                                <!-- PAGE CONTENT ENDS -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>
