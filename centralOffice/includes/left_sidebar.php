
<div id="sidebar" class="sidebar responsive ace-save-state">
    <script type="text/javascript">
        try {
            ace.settings.loadState('sidebar')
        } catch (e) {
        }
    </script>
    <ul class="nav nav-list">
        <li class="active">
            <a href="index.php?menu=1">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>
            <b class="arrow"></b>
        </li>
        <?php
        $menuArray = array();
        $openMenuId = 0;
        $menu = 0;


        if (isset($_GET['menu'])) {
            $menu = $_GET['menu'];
            if ($menu == 404) {
                $menu = 1;
            }
            $menuData = getMasterMenuId($menu);
            $openMenuId = $menuData->id;
            $menu = $menuData->master_id;
            $menuArray[] = $openMenuId;
            for ($i = 1; $menu != 0; $i++) {
                $menuData = getMasterMenuId($menu);
                $openMenuId = $menuData->id;
                $menu = $menuData->master_id;
                $menuArray[] = $openMenuId;
            }
        }
        $menuHeader = getMenuheader();
        foreach ($menuHeader as $headerTxt) {
            if ($headerTxt->id == 1) {
                $icon = 'fa fa-shield';
            } else if ($headerTxt->id == 2) {
                $icon = 'fa fa-flag-checkered';
            } else {
                $icon = 'fa fa-car';
            }
            if (in_array($headerTxt->id, $menuArray)) {
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
                        <i class="menu-icon <?php echo $icon; ?>"></i>
                        <span class="menu-text">
                            <?php echo $headerTxt->page_title; ?>
                        </span>

                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu <?php echo $ShowSubOpen; ?>">
                        <?php
                        $submenuList = getSubMenuList($headerTxt->id);
                        foreach ($submenuList as $submenu) {
                            if ($submenu->page_name == '') {
                                $link = '#';
                            } else {
                                $link = $submenu->page_name;
                            }

                            if (in_array($submenu->id, $menuArray)) {
                                $classSubOpen_1 = "open";
                                $ShowSubOpen_1 = "nav-show";
                            } else {
                                $classSubOpen_1 = "";
                                $ShowSubOpen_1 = "";
                            }
                            ?>
                            <li class="<?php echo $classSubOpen_1; ?>">
                                <a href="<?php echo $link; ?>" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    <?php echo $submenu->page_title; ?>
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>
                                <ul class="submenu <?php echo $ShowSubOpen_1; ?>">
                                    <?php
                                    $submenuListLink = getSubMenuList($submenu->id);
                                    foreach ($submenuListLink as $link) {
                                        $link_nm = $link->page_name;
                                        $link_nm .= "?menu=" . $link->id;
                                        if (in_array($link->id, $menuArray)) {
                                            $classopen = "open";
                                        } else {
                                            $classopen = "";
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
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php
            }
        }
        ?>
    </ul>
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>
