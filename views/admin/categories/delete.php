<?php

$categoryDAO = new \DAO\CategoryDAO();

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id > 0) {
    if ($categoryDAO->delete($id)) {
        header("Location: /MiniShop_HoThiBichNhung/admin/category");
        exit();
    } else {
        header("Location: /MiniShop_HoThiBichNhung/admin/category?error=Xóa danh mục thất bại!");
        exit();
    }
} else {
    header("Location: /MiniShop_HoThiBichNhung/admin/category");
    exit();
}
?>
