<?php

namespace Controllers\Admin;

use DAO\CategoryDAO;
use Models\Category;
use Middleware\CsrfMiddleware;

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
        $pageTitle = "Thêm danh mục mới";
        $categoryDAO = new CategoryDAO();
        $errors = [];
        $cateName = $slug = $description = "";
        $status = 1;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            // Đọc dữ liệu từ Form
            $cateName = trim($_POST["cateName"] ?? "");
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
            if ($cateName == "") {
                $errors[] = "Tên danh mục không được để trống.";
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
                    $uploadDir = __DIR__ . "/../../uploads/categories/" . $image;
                    move_uploaded_file($tmpName, $uploadDir);
                }
                // + Tạo Category object
                $category = new Category(
                    $cateName,
                    $slug,
                    $description,
                    $image,
                    $status
                );
                // + Lưu CSDL
                if ($categoryDAO->insert($category)) {
                    header("Location: /MiniShop_HoThiBichNhung/admin/category");
                    exit();
                } else {
                    $errors[] = "Thêm danh mục thất bại. Vui lòng thử lại!";
                }
            }
        }
        require __DIR__ . "/../../views/admin/categories/create.php";
    }

    public function edit()
    {
        $pageTitle = "Cập nhật danh mục";
        $categoryDAO = new CategoryDAO();
        $errors = [];
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $category = $categoryDAO->findById($id);
        if (!$category) {
            header("Location: /MiniShop_HoThiBichNhung/admin/category");
            exit();
        }
        $categoryOld = clone $category;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            $cateName = trim($_POST["cateName"] ?? "");
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
            if ($cateName == "") $errors[] = "Tên danh mục không được để trống.";
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
                $image = $categoryOld->image;

                // Có chọn hình ảnh mới
                if ($fileName != "") {
                    // Lấy phần mở rộng của file
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    // Đổi tên file
                    $image = time() . "_" . $slug . "." . $extension;
                    // Thư mục & đường dẫn hình ảnh mới
                    $uploadPath = __DIR__ . "/../../uploads/categories/" . $image;
                    // Xóa hình ảnh cũ (nếu có)
                    if (!empty($categoryOld->image)) {
                        $oldImage = __DIR__ . "/../../uploads/categories/" . $categoryOld->image;
                        if (file_exists($oldImage)) {
                            unlink($oldImage); // xóa file
                        }
                    }
                    // Upload hình ảnh mới
                    move_uploaded_file($tmpName, $uploadPath);
                }
                $category->catename = $cateName;
                $category->slug = $slug;
                $category->description = $description;
                $category->image = $image;
                $category->status = $status;
                if ($categoryDAO->update($category)) {
                    header("Location: /MiniShop_HoThiBichNhung/admin/category");
                    exit();
                } else {
                    $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
                }
            }
        }
        require __DIR__ . "/../../views/admin/categories/edit.php";
    }

    public function detail()
    {
        $pageTitle = "Chi tiết danh mục";
        $categoryDAO = new CategoryDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $category = $categoryDAO->findById($id);
        if (!$category) {
            header("Location: /MiniShop_HoThiBichNhung/admin/category");
            exit();
        }
        require __DIR__ . "/../../views/admin/categories/detail.php";
    }

    public function delete()
    {
        $categoryDAO = new CategoryDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        if ($id > 0) {
            if ($categoryDAO->delete($id)) {
                header("Location: /MiniShop_HoThiBichNhung/admin/category");
                exit();
            } else {
                header("Location: /MiniShop_HoThiBichNhung/admin/category?error=Xóa danh mục thất bại!");
                exit();
            }
        } else {
            header("Location: /MiniShop_HoThiBichNhung/admin/category");
            exit();
        }
    }
}
