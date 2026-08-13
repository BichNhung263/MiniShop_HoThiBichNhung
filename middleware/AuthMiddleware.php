<?php
namespace Middleware;

use DAO\UserDAO;

class AuthMiddleware{
    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nếu Session lưu object cũ (Incomplete Class), xóa để nạp lại
        if (isset($_SESSION["user"]) && ($_SESSION["user"] instanceof \__PHP_Incomplete_Class || !is_object($_SESSION["user"]))) {
            unset($_SESSION["user"]);
        }

        // Tự động khôi phục Session nếu có Cookie ghi nhớ đăng nhập
        if (!isset($_SESSION["user"]) && isset($_COOKIE["remember_user"])) {
            $userDAO = new UserDAO();
            $user = $userDAO->findByUsername($_COOKIE["remember_user"]);
            if ($user) {
                $_SESSION["user"] = $user;
            }
        }

        if (!isset($_SESSION["user"])) {
            header("Location: login.php");
            exit;
        }

        // Kiểm tra quyền sau khi đăng nhập
        $user = $_SESSION["user"];
        if ($user->role != 1) { 
            // Từ chối truy cập nếu không phải Admin
            die("Bạn không có quyền truy cập trang quản trị này!");
        }
    }
}