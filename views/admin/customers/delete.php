<?php
require_once __DIR__ . "/../../../dao/CustomerDAO.php";
$dao = new CustomerDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0 && $dao->delete($id)) {
    header("Location: index.php"); exit();
} else {
    header("Location: index.php?error=Xóa khách hàng thất bại!"); exit();
}
?>
