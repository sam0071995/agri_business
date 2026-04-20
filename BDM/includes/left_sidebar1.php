
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
                <span class="menu-text"> OEM Panel </span>
            </a>
            <b class="arrow"></b>
        </li>
        <?php
        $menuArray = array();
        $openMenuId = 0;
        $menu = 0;
        if (isset($_GET['menu'])) {
            $menu = $_GET['menu'];
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
            ?>
            <li class="<?php echo $classSubOpen; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-pencil-square-o"></i>
                    <span class="menu-text"> Forms </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>
                <?php
                $submenuListLink = getSubMenuList($headerTxt->id);
                foreach ($submenuListLink as $link) {
                    $link_nm = $link->page_name;
                    $link_nm .= "?menu=" . $link->id;
                    if ($menu == $link->id) {
                        $classopen = "active";
                    } else {
                        $classopen = "";
                    }
                    ?>
                    <ul class="submenu">
                        <li class="<?php echo $classopen; ?>">
                            <a href="<?php echo $link_nm; ?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                <?php echo $link->page_title; ?>
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>
