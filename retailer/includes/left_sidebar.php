<div id="sidebar" class="sidebar responsive ace-save-state">
    <script type="text/javascript">
        try {
            ace.settings.loadState('sidebar')
        } catch (e) {
        }
    </script>
    <ul class="nav nav-list">
        <li class="active">
            <a href="index.php?success=1&menu=1">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text">Company Store</span>
            </a>
            <b class="arrow"></b>
        </li>

        <?php if ($_SESSION['email'] == 'mis_manager') { ?>
            <li class="">
                <a href="mis_report.php?menu=100">
                    <i class="menu-icon fa fa-file-excel-o"></i>
                    <span class="menu-text"> MIS REPORT </span>
                </a>
                <b class="arrow"></b>
            </li>
        <?php } ?>


        <?php
        $menuArray = array();
        $openMenuId = 0;
        $menu = 0;

        $assign_zone_menu = getAllAssignMenuByZomId($_SESSION['id']);
        $assign_zone_menu_array = explode(',', $assign_zone_menu);
        // print_r($assign_zone_menu_array);
        if (isset($_GET['menu'])) {
            $menu = $_GET['menu'];
            $master_active_menu = getMasterMenuId($menu);
            $master_active_menu_2 = getMasterMenuId($master_active_menu);
        } else {
            $master_active_menu = 1;
            $menu = 1;
        }

        $menuHeader = getMenuheader();
        foreach ($menuHeader as $headerTxt) {
            if ($master_active_menu_2 == $headerTxt->id) {
                $classSubOpen = "open";
                $ShowSubOpen = "nav-show";
            } else {
                $classSubOpen = "";
                $ShowSubOpen = "";
            }
            if (in_array($headerTxt->id, $assign_zone_menu_array)) {
                ?>
                <li class="<?php echo $classSubOpen; ?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon <?php echo $headerTxt->icon; ?>"></i>
                        <span class="menu-text"> <?php echo $headerTxt->page_title; ?> </span>

                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu <?php echo $ShowSubOpen; ?>">
                        <?php
                        $submenuListLink = getSubMenuList($headerTxt->id);
                        // print_r($submenuListLink);
                        foreach ($submenuListLink as $link) {
                            $link_nm = $link->link;
                            $link_nm .= "?menu=" . $link->id;
                            if ($master_active_menu == $link->id) {
                                // $classopen = "active";
                                $classopen = "open";
                                $ShowSubOpen = "nav-show";
                            } else {
                                $classopen = "";
                                $ShowSubOpen = "";
                            }
                            if (in_array($link->id, $assign_zone_menu_array)) {
                                ?>

                                <li class="<?php echo $classopen; ?>">
                                    <a href="#" class="dropdown-toggle">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        <?php echo $link->page_title; ?>
                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>
                                    <b class="arrow"></b>

                                    <ul class="submenu <?php echo $ShowSubOpen; ?>">
                                        <?php
                                        $submenuListLink = getSubMenuList($link->id);
                                        // print_r($submenuListLink);
                                        foreach ($submenuListLink as $link) {
                                            $link_nm = $link->page_name;
                                            $link_nm .= "?menu=" . $link->id;
                                            if ($menu == $link->id) {
                                                $classopen = "open active";
                                                // $classopen = "open";
                                                $ShowSubOpen = "nav-show";
                                            } else {
                                                $classopen = "";
                                                $ShowSubOpen = "";
                                            }
                                            if (in_array($link->id, $assign_zone_menu_array)) {
                                                ?>

                                                <li class="<?php echo $classopen; ?>">
                                                    <a href="<?php echo $link_nm; ?>">
                                                        <i class="menu-icon fa fa-caret-right"></i>
                                                        <?php echo $link->page_title; ?>
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>

                                            <?php }
                                        }
                                        ?>
                                    </ul>
                                </li>

                            <?php }
                        }
                        ?>
                    </ul>
                </li>
            <?php }
        }
        ?>
    </ul>
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>