<?php
$dao = new \DAO\ProductDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0 && $dao->delete($id)) {
    header("Location: /MiniShop_HoThiBichNhung/admin/product");
    exit();
} else {
    header("Location: /MiniShop_HoThiBichNhung/admin/product?error=Xóa sản phẩm thất bại!");
    exit();
}
