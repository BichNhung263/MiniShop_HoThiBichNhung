<?php

namespace Controllers\Admin;

use DAO\UserDAO;

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
        require __DIR__ . "/../../views/admin/users/create.php";
    }

    public function edit()
    {
        require __DIR__ . "/../../views/admin/users/edit.php";
    }

    public function detail()
    {
        require __DIR__ . "/../../views/admin/users/detail.php";
    }

    public function delete()
    {
        require __DIR__ . "/../../views/admin/users/delete.php";
    }
}
