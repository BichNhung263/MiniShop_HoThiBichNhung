<?php

namespace Controllers\Admin;

use DAO\UserDAO;
use Models\User;
use Middleware\CsrfMiddleware;

class UserController
{
    
    public function index()
    {
        // Gán dữ liệu cho tiêu đề trang
        $title = "Quản lý người dùng";
        // Đọc request từ URL
        $keyword = trim($_GET["keyword"] ?? "");
        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);
        // Xử lý offset
        $offset = ($page - 1) * $limit;
        // Gọi Dao
        $userDAO = new UserDAO();
        $totalRecords = $userDAO->count(
            "users",
            "fullname",
            $keyword
        );
        $totalPages = ceil($totalRecords / $limit);
        $users = $userDAO->getPage(
            $limit,
            $offset,
            $keyword
        );
        // Gọi View
        require __DIR__ . "/../../views/admin/users/index.php";
    }

    public function create()
    {
        $pageTitle = "Thêm người dùng";
        $errors = [];
        $fullname = $username = $password = $email = $phone = $address = "";
        $role = 0;
        $status = 1;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            $fullname = trim($_POST["fullname"] ?? "");
            $username = trim($_POST["username"] ?? "");
            $password = trim($_POST["password"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $address = trim($_POST["address"] ?? "");
            $role = isset($_POST["role"]) ? (int)$_POST["role"] : 0;
            $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;
            if ($fullname == "") $errors[] = "Họ và tên không được để trống.";
            if ($username == "") $errors[] = "Username không được để trống.";
            if ($password == "") $errors[] = "Mật khẩu không được để trống.";
            if ($email == "") $errors[] = "Email không được để trống.";
            if (empty($errors)) {
                $dao = new UserDAO();
                $user = new User($fullname, $username, md5($password), $email, $phone, $address, $role, $status);
                if ($dao->insert($user)) {
                    header("Location: /MiniShop_HoThiBichNhung/admin/user");
                    exit();
                } else {
                    $errors[] = "Thêm thất bại. Vui lòng thử lại!";
                }
            }
        }
        require __DIR__ . "/../../views/admin/users/create.php";
    }

    public function edit()
    {
        $pageTitle = "Cập nhật người dùng";
        $errors = [];
        $dao = new UserDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $user = $dao->findById($id);
        if (!$user) {
            header("Location: /MiniShop_HoThiBichNhung/admin/user");
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            $user->fullname = trim($_POST["fullname"] ?? "");
            $user->username = trim($_POST["username"] ?? "");
            $user->email = trim($_POST["email"] ?? "");
            $user->phone = trim($_POST["phone"] ?? "");
            $user->address = trim($_POST["address"] ?? "");
            $user->role = isset($_POST["role"]) ? (int)$_POST["role"] : 0;
            $user->status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;
            $newPassword = trim($_POST["password"] ?? "");
            if ($newPassword != "") $user->password = md5($newPassword);
            if ($user->fullname == "") $errors[] = "Họ và tên không được để trống.";
            if ($user->username == "") $errors[] = "Username không được để trống.";
            if ($user->email == "") $errors[] = "Email không được để trống.";

            if (empty($errors)) {
                if ($dao->update($user)) {
                    header("Location: /MiniShop_HoThiBichNhung/admin/user");
                    exit();
                } else {
                    $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
                }
            }
        }
        require __DIR__ . "/../../views/admin/users/edit.php";
    }

    public function detail()
    {
        $pageTitle = "Chi tiết người dùng";
        $dao = new UserDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $user = $dao->findById($id);
        if (!$user) {
            header("Location: /MiniShop_HoThiBichNhung/admin/user");
            exit();
        }
        require __DIR__ . "/../../views/admin/users/detail.php";
    }

    public function delete()
    {
        $dao = new UserDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        if ($id > 0 && $dao->delete($id)) {
            header("Location: /MiniShop_HoThiBichNhung/admin/user");
            exit();
        } else {
            header("Location: /MiniShop_HoThiBichNhung/admin/user?error=Xóa người dùng thất bại!");
            exit();
        }
    }
}
