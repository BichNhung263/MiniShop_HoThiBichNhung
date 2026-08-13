<?php
require_once __DIR__ . "/../dao/UserDAO.php";

class GuestMiddleware{
    public static function handle(){
        if (session_status() === PHP_SESSION_NONE){
            session_start();
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