<?php
namespace Controllers\Admin;

use DAO\OrderDAO;

class OrderController {

    public function index()
    {
        // Gán dữ liệu cho tiêu đề trang
        $title = "Quản lý đơn hàng";
        // Đọc request từ URL
        $keyword = trim($_GET["keyword"] ?? "");
        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);
        // Xử lý offset
        $offset = ($page - 1) * $limit;
        // Gọi Dao
        $orderDAO = new OrderDAO();
        $totalRecords = $orderDAO->count(
            "orders",
            "orderCode",
            $keyword
        );
        $totalPages = ceil($totalRecords / $limit);
        $orders = $orderDAO->getPage(
            $limit,
            $offset,
            $keyword
        );
        // Gọi View
        require __DIR__ . "/../../views/admin/orders/index.php";
    }

    public function detail()
    {
        require __DIR__ . "/../../views/admin/orders/detail.php";
    }
}
