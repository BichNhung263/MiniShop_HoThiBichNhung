<?php
require_once __DIR__ . '/autoload.php';

// Nhận Request
$area = $_GET["area"] ?? "client";
$controller = $_GET["controller"] ?? "product";
$action = $_GET["action"] ?? "index";

// Xác định tên Controller
if ($area === "admin") {
    $controllerClass = "Controllers\\Admin\\" . ucfirst($controller) . "Controller";
} else {
    $controllerClass = "Controllers\\Client\\" . ucfirst($controller) . "Controller";
}

// Kiểm tra Controller
if (!class_exists($controllerClass)) {
    die("Controller không tồn tại");
}

// Tạo Controller
$controllerObject = new $controllerClass();

// Kiểm tra Action
if (!method_exists($controllerObject, $action)) {
    die("Action không tồn tại");
}

// Gọi Action
$controllerObject->$action();