<?php

namespace Controllers\Admin;

use DAO\CustomerDAO;
use Models\Customer;
use Middleware\CsrfMiddleware;

class CustomerController
{
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
        $pageTitle = "Thêm khách hàng";
        $errors = [];
        $fullname = $email = $phone = $address = $note = "";
        $status = 1;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            $fullname = trim($_POST["fullname"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $address = trim($_POST["address"] ?? "");
            $note = trim($_POST["note"] ?? "");
            $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;
            if ($fullname == "") $errors[] = "Họ và tên không được để trống.";
            if ($phone == "") $errors[] = "Điện thoại không được để trống.";
            if (empty($errors)) {
                $dao = new CustomerDAO();
                $customer = new Customer($fullname, $email, $phone, $address, $note, $status);
                if ($dao->insert($customer)) {
                    header("Location: /MiniShop_HoThiBichNhung/admin/customer");
                    exit();
                } else {
                    $errors[] = "Thêm thất bại. Vui lòng thử lại!";
                }
            }
        }
        require __DIR__ . "/../../views/admin/customers/create.php";
    }

    public function edit()
    {
        $pageTitle = "Cập nhật khách hàng";
        $errors = [];
        $dao = new CustomerDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $customer = $dao->findById($id);
        if (!$customer) {
            header("Location: /MiniShop_HoThiBichNhung/admin/customer");
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            $customer->fullname = trim($_POST["fullname"] ?? "");
            $customer->email = trim($_POST["email"] ?? "");
            $customer->phone = trim($_POST["phone"] ?? "");
            $customer->address = trim($_POST["address"] ?? "");
            $customer->note = trim($_POST["note"] ?? "");
            $customer->status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;
            if ($customer->fullname == "") $errors[] = "Họ và tên không được để trống.";
            if ($customer->phone == "") $errors[] = "Điện thoại không được để trống.";
            if (empty($errors)) {
                if ($dao->update($customer)) {
                    header("Location: /MiniShop_HoThiBichNhung/admin/customer");
                    exit();
                } else {
                    $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
                }
            }
        }
        require __DIR__ . "/../../views/admin/customers/edit.php";
    }

    public function detail()
    {
        $pageTitle = "Chi tiết khách hàng";
        $dao = new CustomerDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $customer = $dao->findById($id);
        if (!$customer) {
            header("Location: /MiniShop_HoThiBichNhung/admin/customer");
            exit();
        }
        require __DIR__ . "/../../views/admin/customers/detail.php";
    }

    public function delete()
    {
        $dao = new CustomerDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        if ($id > 0 && $dao->delete($id)) {
            header("Location: /MiniShop_HoThiBichNhung/admin/customer");
            exit();
        } else {
            header("Location: /MiniShop_HoThiBichNhung/admin/customer?error=Xóa khách hàng thất bại!");
            exit();
        }
    }
}
