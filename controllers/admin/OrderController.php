<?php

namespace Controllers\Admin;

use DAO\OrderDAO;
use Middleware\CsrfMiddleware;

class OrderController
{
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
            "order_code",
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
        $pageTitle = "Chi tiết đơn hàng";
        $dao = new OrderDAO();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $order = $dao->findById($id);
        if (!$order) {
            header('Location: /MiniShop_HoThiBichNhung/admin/order');
            exit();
        }
        $details = $dao->getOrderDetails($id);
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            CsrfMiddleware::verify();
            $newStatus = isset($_POST['status']) ? (int)$_POST['status'] : $order->status;
            $order->status = $newStatus;
            if ($dao->updateStatus($order->id, $newStatus)) {
                header('Location: /MiniShop_HoThiBichNhung/admin/order');
                exit();
            } else {
                $errors[] = 'Cập nhật trạng thái thất bại.';
            }
        }
        require __DIR__ . "/../../views/admin/orders/detail.php";
    }
}
