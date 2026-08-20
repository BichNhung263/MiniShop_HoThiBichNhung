<?php
namespace Middleware;
use DAO\UserDAO;
class GuestMiddleware
{
    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Nếu đã đăng nhập thì không cho vào trang login
        if (isset($_SESSION["user"])) {
            header(
                "Location: /MiniShop_HoThiBichNhung/admin/product"
            );
            exit;
        }
    }
}
