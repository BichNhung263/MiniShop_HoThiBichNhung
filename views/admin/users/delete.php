<?php
require_once __DIR__ . "/../../../dao/UserDAO.php";
$dao = new UserDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0 && $dao->delete($id)) {
    header("Location: index.php"); exit();
} else {
    header("Location: index.php?error=Xóa người dùng thất bại!"); exit();
}
?>
