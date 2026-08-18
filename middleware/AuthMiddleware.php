<?php

namespace Middleware;

use DAO\UserDAO;

class AuthMiddleware
{
    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // // Khôi phục đăng nhập bằng cookie
        // if (!isset($_SESSION["user"]) && isset($_COOKIE["remember_user"])) {
        //     $userDAO = new UserDAO();
        //     $user = $userDAO->findByUsername($_COOKIE["remember_user"]);

        //     if ($user) {
        //         $_SESSION["user"] = $user;
        //     }
        // }

        // Chưa đăng nhập
        if (!isset($_SESSION["user"])) {
            header("Location: /MiniShop_HoThiBichNhung/admin/login");
                exit;
        }

        // Kiểm tra quyền Admin
        $user = $_SESSION["user"];

        if ($user->role != 1) {
            die("Bạn không có quyền truy cập trang quản trị này!");
        }
    }
}