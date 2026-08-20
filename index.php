<?php
require_once __DIR__ . '/autoload.php';
session_start();
// Nhận Request
$area = $_GET["area"] ?? null; 
$controller = $_GET["controller"] ?? null;
$action = $_GET["action"] ?? null;

if ($area === null) {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $base = '/MiniShop_HoThiBichNhung';
    $path = trim(substr($uri, strlen($base)), '/');
    $segs = $path !== '' ? explode('/', $path) : [];

    if (count($segs) > 0 && $segs[0] === 'admin') {
        $area = 'admin';
        $seg1 = $segs[1] ?? '';
        if ($seg1 === 'login' || $seg1 === 'logout') {
            $controller = 'auth';
            $action = $seg1;
        } else {
            $controller = $seg1 !== '' ? $seg1 : 'product';
            $action = $segs[2] ?? 'index';
            if (isset($segs[3])) {
                $_GET['id'] = $segs[3];
            }
        }
    } elseif (count($segs) > 0 && $segs[0] !== '') {
        $area = 'client';
        $controller = $segs[0];
        $action = $segs[1] ?? 'index';
        if (isset($segs[2])) {
            $_GET['id'] = $segs[2];
        }
    } else {
        $area = 'client';
    }
}

// Giá trị mặc định nếu chưa có
$area = $area ?? 'client';
$controller = $controller ?? 'home';
$action = $action ?? 'index';

//kiểm tra Authentication cho Admin
if ($area === "admin" && $controller !== "auth") {
    \Middleware\AuthMiddleware::handle();
}

//Kiểm tra Guest
if ($area === "admin" && $controller === "auth" && $action === "login") {
    \Middleware\GuestMiddleware::handle();
}
//Tạo CSRF Token
if (($_SERVER["REQUEST_METHOD"] ?? '') === "POST") {
    \Middleware\CsrfMiddleware::generateToken();
}
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
