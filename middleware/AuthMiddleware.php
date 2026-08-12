<?php
class AuthMiddleware{
    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["user"])) {
            header("Location: login.php");
            exit;
        }

        // Authorization: Kiểm tra quyền sau khi đăng nhập
        $user = $_SESSION["user"];
        if ($user->role != 1) { // 1 là Admin, 0 là User
            // Từ chối truy cập nếu không phải Admin
            die("Bạn không có quyền truy cập trang quản trị này!");
        }
    }
}