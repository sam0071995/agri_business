<?php

require_once 'includes/session.php';
require_once 'includes/common_function.php';

if (isset($_GET['category_id'])) {
    $category_id = base64_decode($_GET['category_id']);
    $where = "id='$category_id'";
    $delete = delete("categories", $where);
    if ($delete) {
        header("Location:categories.php" . $menuURL . "&delete=1&success=1");
        exit;
    } else {
        header("Location:categories.php" . $menuURL . "&delete=1&error=1");
        exit;
    }
}
?>