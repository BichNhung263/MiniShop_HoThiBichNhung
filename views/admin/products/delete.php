<?php
require_once __DIR__ . "/../../../dao/ProductDAO.php";
$dao = new ProductDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0 && $dao->delete($id)) {
    header("Location: index.php"); exit();
} else {
    header("Location: index.php?error=Xóa sản phẩm thất bại!"); exit();
}
?>
