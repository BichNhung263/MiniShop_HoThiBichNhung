<?php
namespace Middleware;

use DAO\UserDAO;

class GuestMiddleware{
    public static function handle(){
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }

        // Nếu Session lưu object cũ (Incomplete Class), xóa để nạp lại
        if (isset($_SESSION["user"]) && ($_SESSION["user"] instanceof \__PHP_Incomplete_Class || !is_object($_SESSION["user"]))) {
            unset($_SESSION["user"]);
        }

        // Khôi phục Session từ Cookie nếu có
        if (!isset($_SESSION["user"]) && isset($_COOKIE["remember_user"])) {
            $userDAO = new UserDAO();
            $user = $userDAO->findByUsername($_COOKIE["remember_user"]);
            if ($user) {
                $_SESSION["user"] = $user;
            }
        }

        if (isset($_SESSION["user"])){
            header("Location: dashboard.php");
            exit;
        }
    }
}