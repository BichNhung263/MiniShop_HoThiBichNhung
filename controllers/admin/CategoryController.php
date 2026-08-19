<?php

namespace Controllers\Admin;

use DAO\CategoryDAO;

class CategoryController
{

    public function index()
    {
        // Gán dữ liệu cho tiêu đề trang
        $title = "Quản lý danh mục";
        // Đọc request từ URL
        $keyword = trim($_GET["keyword"] ?? "");
        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);
        // Xử lý offset
        $offset = ($page - 1) * $limit;
        // Gọi Dao
        $categoryDAO = new CategoryDAO();
        $totalRecords = $categoryDAO->count(
            "categories",
            "catename",
            $keyword
        );
        $totalPages = ceil($totalRecords / $limit);
        $categories = $categoryDAO->getPage(
            $limit,
            $offset,
            $keyword
        );
        // Gọi View
        require __DIR__ . "/../../views/admin/categories/index.php";
    }

    public function create()
    {
        require __DIR__ . "/../../views/admin/categories/create.php";
    }

    public function edit()
    {
        require __DIR__ . "/../../views/admin/categories/edit.php";
    }

    public function detail()
    {
        require __DIR__ . "/../../views/admin/categories/detail.php";
    }

    public function delete()
    {
        require __DIR__ . "/../../views/admin/categories/delete.php";
    }
}
