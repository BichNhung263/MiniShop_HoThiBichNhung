<?php

namespace Controllers\Admin;

use DAO\ProductDAO;

class ProductController
{

    public function index()
    {
        // Gán dữ liệu cho tiêu đề trang
        $title = "Quản lý sản phẩm";
        // Đọc request từ URL
        $keyword = trim($_GET["keyword"] ?? "");
        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);
        // Xử lý offset
        $offset = ($page - 1) * $limit;
        // Gọi Dao
        $productDAO = new ProductDAO();
        $totalRecords = $productDAO->count(
            "products",
            "proname",
            $keyword
        );
        $totalPages = ceil($totalRecords / $limit);
        $products = $productDAO->getPage(
            $limit,
            $offset,
            $keyword
        );
        // Gọi View
        require __DIR__ . "/../../views/admin/products/index.php";
    }

    public function create()
    {
        require __DIR__ . "/../../views/admin/products/create.php";
    }

    public function edit()
    {
        require __DIR__ . "/../../views/admin/products/edit.php";
    }

    public function detail()
    {
        require __DIR__ . "/../../views/admin/products/detail.php";
    }

    public function delete()
    {
        require __DIR__ . "/../../views/admin/products/delete.php";
    }
}
