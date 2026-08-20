<?php

namespace Controllers\Admin;

use DAO\BrandDAO;
use Models\Brand;
use Middleware\CsrfMiddleware;

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
        $pageTitle = "Thêm thương hiệu";
        $errors = [];
        $brandDAO = new BrandDAO();
        $brandname = $slug = $description = "";
        $status = 1;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            // Đọc dữ liệu từ Form
            $brandname = trim($_POST["brandname"] ?? "");
            $slug = trim($_POST["slug"] ?? "");
            $description = trim($_POST["description"] ?? "");
            $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;
            $fileName = $_FILES["image"] ?? "";
            $image = "";
            // Đọc thông tin hình ảnh
            $fileName = $_FILES["image"]["name"] ?? "";
            $tmpName = $_FILES["image"]["tmp_name"] ?? "";
            $fileSize = $_FILES["image"]["size"] ?? 0;
            $Error = $_FILES["image"]["error"] ?? 0;
            $errors = [];
            // Validation
            if ($brandname == "") {
                $errors[] = "Tên thương hiệu không được để trống.";
            }
            if ($slug == "") {
                $errors[] = "Slug không được để trống.";
            }
            // Validation hình ảnh
            if ($fileName != "") {
                if ($Error != UPLOAD_ERR_OK) {
                    $errors[] = "Upload hình ảnh không thành công.";
                }
                $allowExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($fileName != "" && !in_array($extension, $allowExtensions)) {
                    $errors[] = "Chỉ cho phép file JPG, JPEG, PNG hoặc WEBP.";
                }
                $maxSize = 200 * 1024;
                if ($fileName != "" && $fileSize > $maxSize) {
                    $errors[] = "Kích thước hình ảnh <= 200 KB.";
                }
            }
            // Nếu không có lỗi
            if (empty($errors)) {
                // + Upload hình ảnh
                if ($fileName != "") {
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $image = time() . "_" . $slug . "." . $extension;
                    $uploadDir = __DIR__ . "/../../uploads/brands/" . $image;
                    move_uploaded_file($tmpName, $uploadDir);
                }
                // + Tạo Brand object
                $brand = new Brand(
                    $brandname,
                    $slug,
                    $description,
                    $image,
                    $status
                );
                // + Lưu CSDL
                if ($brandDAO->insert($brand)) {
                    header("Location: /MiniShop_HoThiBichNhung/admin/brand");
                    exit();
                } else {
                    $errors[] = "Thêm thất bại. Vui lòng thử lại!";
                }
            }
        }
        require __DIR__ . "/../../views/admin/brands/create.php";
    }

    public function edit()
    {
        $pageTitle = "Cập nhật thương hiệu";
        $errors = [];
        $dao = new BrandDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $brand = $dao->findById($id);
        if (!$brand) {
            header("Location: /MiniShop_HoThiBichNhung/admin/brand");
            exit();
        }
        $brandOld = clone $brand;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            $brandname = trim($_POST["brandname"] ?? "");
            $slug = trim($_POST["slug"] ?? "");
            $description = trim($_POST["description"] ?? "");
            $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;
            $fileName = $_FILES["image"] ?? "";
            $image = "";
            // Đọc thông tin hình ảnh
            $fileName = $_FILES["image"]["name"] ?? "";
            $tmpName  = $_FILES["image"]["tmp_name"] ?? "";
            $fileSize = $_FILES["image"]["size"] ?? 0;
            $Error    = $_FILES["image"]["error"] ?? 0;
            $errors = [];
            if ($brandname == "") $errors[] = "Tên thương hiệu không được để trống.";
            if ($slug == "") $errors[] = "Slug không được để trống.";
            // Validation hình ảnh
            if ($fileName != "") {
                if ($Error != UPLOAD_ERR_OK) {
                    $errors[] = "Upload hình ảnh không thành công.";
                }
                $allowExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if (!in_array($extension, $allowExtensions)) {
                    $errors[] = "Chỉ cho phép file JPG, JPEG, PNG hoặc WEBP.";
                }
                $maxSize = 200 * 1024;
                if ($fileSize > $maxSize) {
                    $errors[] = "Kích thước hình ảnh <= 200 KB.";
                }
            }
            if (empty($errors)) {
                $image = $brandOld->image;
                // Có chọn hình ảnh mới
                if ($fileName != "") {
                    // Lấy phần mở rộng của file
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    // Đổi tên file
                    $image = time() . "_" . $slug . "." . $extension;
                    // Thư mục & đường dẫn hình ảnh mới
                    $uploadDir = __DIR__ . "/../../uploads/brands/";
                    $uploadPath = $uploadDir . $image;
                    // Xóa hình ảnh cũ (nếu có)
                    if (!empty($brandOld->image)) {
                        $oldImage = $uploadDir . $brandOld->image;
                        if (file_exists($oldImage)) {
                            unlink($oldImage);
                        }
                    }
                    // Upload hình ảnh mới
                    move_uploaded_file($tmpName, $uploadPath);
                }
                $brand->brandname = $brandname;
                $brand->slug = $slug;
                $brand->description = $description;
                $brand->image = $image;
                $brand->status = $status;

                if ($dao->update($brand)) {
                    header("Location: /MiniShop_HoThiBichNhung/admin/brand");
                    exit();
                } else {
                    $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
                }
            }
        }
        require __DIR__ . "/../../views/admin/brands/edit.php";
    }

    public function detail()
    {
        $pageTitle = "Chi tiết thương hiệu";
        $dao = new BrandDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $brand = $dao->findById($id);
        if (!$brand) {
            header("Location: /MiniShop_HoThiBichNhung/admin/brand");
            exit();
        }
        require __DIR__ . "/../../views/admin/brands/detail.php";
    }

    public function delete()
    {
        $dao = new BrandDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        if ($id > 0 && $dao->delete($id)) {
            header("Location: /MiniShop_HoThiBichNhung/admin/brand");
            exit();
        } else {
            header("Location: /MiniShop_HoThiBichNhung/admin/brand?error=Xóa thương hiệu thất bại!");
            exit();
        }
    }
}
