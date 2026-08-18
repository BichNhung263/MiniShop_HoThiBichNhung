<?php
namespace Controllers\Admin;

use DAO\UserDAO;
use Middleware\CsrfMiddleware;

class AuthController
{
    public function login()
    {
        $errors = [];
        // Hiển thị form
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            require_once __DIR__ . '/../../views/admin/login.php';
            return;
        }

        // Kiểm tra CSRF
        CsrfMiddleware::verify();

        // Nhận dữ liệu
        $username = trim($_POST["username"] ?? "");
        $password = $_POST["password"] ?? "";

        // Validate
        if ($username === "") {
            $errors['username'] = "Vui lòng nhập tên đăng nhập.";
        }
        if ($password === "") {
            $errors['password'] = "Vui lòng nhập mật khẩu.";
        }

        // Nếu có lỗi
        if (!empty($errors)) {
            require_once __DIR__ . '/../../views/admin/login.php';
            return;
        }

        // Tìm User
        $userDAO = new UserDAO();
        $user = $userDAO->findByUsername($username);

        // Kiểm tra tài khoản và mật khẩu
        if (!$user) {
            $errors['username'] = "Tên đăng nhập không đúng.";
            require_once __DIR__ . '/../../views/admin/login.php';
            return;
        } else if (!password_verify($password, $user->password)) {
            $errors['password'] = "Mật khẩu không đúng.";
            require_once __DIR__ . '/../../views/admin/login.php';
            return;
        }

        // Đăng nhập thành công
        $_SESSION["user"] = $user;

        header("Location: /MiniShop_HoThiBichNhung/admin/dashboard");
        exit;
    }

    // Đăng xuất
    public function logout()
    {
        session_unset();
        session_destroy();

        if (isset($_COOKIE["remember_user"])) {
            setcookie("remember_user", "", time() - 3600, "/");
        }

        header("Location: /MiniShop_HoThiBichNhung/admin/login");
            exit;
    }
}