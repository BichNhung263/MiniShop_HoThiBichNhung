<?php

namespace Controllers\Admin;

use DAO\BrandDAO;

class BrandController
{

    public function index()
    {
        // Gán dữ liệu cho tiêu đề trang
        $title = "Quản lý thương hiệu";
        // Đọc request từ URL
        $keyword = trim($_GET["keyword"] ?? "");
        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);
        // Xử lý offset
        $offset = ($page - 1) * $limit;
        // Gọi Dao
        $brandDAO = new BrandDAO();
        $totalRecords = $brandDAO->count(
            "brands",
            "brandname",
            $keyword
        );
        $totalPages = ceil($totalRecords / $limit);
        $brands = $brandDAO->getPage(
            $limit,
            $offset,
            $keyword
        );
        // Gọi View
        require __DIR__ . "/../../views/admin/brands/index.php";
    }

    public function create()
    {
        require __DIR__ . "/../../views/admin/brands/create.php";
    }

    public function edit()
    {
        require __DIR__ . "/../../views/admin/brands/edit.php";
    }

    public function detail()
    {
        require __DIR__ . "/../../views/admin/brands/detail.php";
    }

    public function delete()
    {
        require __DIR__ . "/../../views/admin/brands/delete.php";
    }
}
