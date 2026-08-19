<?php
$dao = new \DAO\BrandDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0 && $dao->delete($id)) {
    header("Location: /MiniShop_HoThiBichNhung/admin/brand");
    exit();
} else {
    header("Location: /MiniShop_HoThiBichNhung/admin/brand?error=Xóa thương hiệu thất bại!");
    exit();
}
