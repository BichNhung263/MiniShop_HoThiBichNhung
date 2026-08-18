<?php
namespace Controllers\Admin;

use DAO\CustomerDAO;

class CustomerController {

    public function index()
    {
        // Gán dữ liệu cho tiêu đề trang
        $title = "Quản lý khách hàng";
        // Đọc request từ URL
        $keyword = trim($_GET["keyword"] ?? "");
        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);
        // Xử lý offset
        $offset = ($page - 1) * $limit;
        // Gọi Dao
        $customerDAO = new CustomerDAO();
        $totalRecords = $customerDAO->count(
            "customers",
            "fullname",
            $keyword
        );
        $totalPages = ceil($totalRecords / $limit);
        $customers = $customerDAO->getPage(
            $limit,
            $offset,
            $keyword
        );
        // Gọi View
        require __DIR__ . "/../../views/admin/customers/index.php";
    }

    public function create()
    {
        require __DIR__ . "/../../views/admin/customers/create.php";
    }

    public function edit()
    {
        require __DIR__ . "/../../views/admin/customers/edit.php";
    }

    public function detail()
    {
        require __DIR__ . "/../../views/admin/customers/detail.php";
    }

    public function delete()
    {
        require __DIR__ . "/../../views/admin/customers/delete.php";
    }
}
