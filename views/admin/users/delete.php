<?php
$dao = new \DAO\UserDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0 && $dao->delete($id)) {
    header("Location: /MiniShop_HoThiBichNhung/admin/user"); exit();
} else {
    header("Location: /MiniShop_HoThiBichNhung/admin/user?error=Xóa người dùng thất bại!"); exit();
}
?>
