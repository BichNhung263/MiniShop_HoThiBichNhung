<?php
require_once __DIR__ . "/../../../dao/CategoryDAO.php";

$categoryDAO = new CategoryDAO();

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id > 0) {
    if ($categoryDAO->delete($id)) {
        header("Location: index.php");
        exit();
    } else {
        header("Location: index.php?error=Xóa danh mục thất bại!");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
