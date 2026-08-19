<?php
$dao = new \DAO\CustomerDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0 && $dao->delete($id)) {
    header("Location: /MiniShop_HoThiBichNhung/admin/customer");
    exit();
} else {
    header("Location: /MiniShop_HoThiBichNhung/admin/customer?error=Xóa khách hàng thất bại!");
    exit();
}
