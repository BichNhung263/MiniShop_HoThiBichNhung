<?php
require_once __DIR__ . "/controllers/admin/ProductController.php";
//require_once __DIR__ . "controllers/admin/CategoryController.php";

//Nhận Request
$controller = $_GET["controller"] ?? "product";
$action = $_GET["action"] ?? "index";

//Xác định tên controller
$controllerClass = ucfirst($controller). "Controller";

//kiểm tra controller
if(!class_exists($controllerClass)){
    die("Controller không tồn tại");
}

//tạo controller
$controllerObject = new $controllerClass();

//kiểm tra Action
$controllerObject->$action();