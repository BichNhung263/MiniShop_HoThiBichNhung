<?php

namespace Controllers\Admin;

use DAO\ProductDAO;
use DAO\CategoryDAO;
use DAO\BrandDAO;
use Models\Product;
use Middleware\CsrfMiddleware;

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
        $pageTitle = "Thêm sản phẩm";
        $categoryDAO = new CategoryDAO();
        $brandDAO = new BrandDAO();
        $categories = $categoryDAO->getAll();
        $brands = $brandDAO->getAll();
        $errors = [];
        $message = "";
        $proname = $slug = $description = "";
        $categoryId = $brandId = 0;
        $price = 0;
        $discountPrice = 0;
        $quantity = 0;
        $status = 1;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            // Đọc dữ liệu từ Form
            $categoryid = (int)($_POST["categoryId"] ?? 0);
            $brandid = (int)($_POST["brandId"] ?? 0);
            $productname = trim($_POST["proname"] ?? "");
            $slug = trim($_POST["slug"] ?? "");
            $price = (float)($_POST["price"] ?? 0);
            $pricediscount = (float)($_POST["discountPrice"] ?? 0);
            $quantity = (int)($_POST["quantity"] ?? 0);
            $description = trim($_POST["description"] ?? "");
            $status = isset($_POST["status"]) ? (int)$_POST["status"] : 0;
            $fileName = $_FILES["image"] ?? "";
            $image = "";
            // Đọc thông tin hình ảnh
            $fileName = $_FILES["image"]["name"] ?? "";
            $tmpName = $_FILES["image"]["tmp_name"] ?? "";
            $fileSize = $_FILES["image"]["size"] ?? 0;
            $Error = $_FILES["image"]["error"] ?? 0;
            $errors = [];
            // Validation
            if ($productname == "") {
                $errors[] = "Tên sản phẩm không được để trống.";
            }
            if ($slug == "") {
                $errors[] = "Slug không được để trống.";
            }
            if ($categoryid == 0) {
                $errors[] = "Vui lòng chọn danh mục.";
            }
            if ($brandid == 0) {
                $errors[] = "Vui lòng chọn thương hiệu.";
            }
            if ($price <= 0) {
                $errors[] = "Giá bán phải lớn hơn 0.";
            }
            if ($quantity < 0) {
                $errors[] = "Số lượng không hợp lệ.";
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
                if ($fileName != "") {
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $image = time() . "_" . $slug . "." . $extension;
                    $uploadDir = __DIR__ . "/../../uploads/products/" . $image;
                    move_uploaded_file($tmpName, $uploadDir);
                }
                // + Tạo Product
                $product = new Product(
                    $categoryid,
                    $brandid,
                    $productname,
                    $slug,
                    $price,
                    $pricediscount,
                    $quantity,
                    $image,
                    $description,
                    $status
                );
                // + Lưu CSDL
                $productDAO = new ProductDAO();
                if ($productDAO->insert($product)) {
                    $productId = $product->id;
                    // Đọc dữ liệu Upload
                    // Sử dụng vòng lặp để Upload từng file
                    if (isset($_FILES["images"]["name"]) && is_array($_FILES["images"]["name"])) {
                        foreach ($_FILES["images"]["name"] as $key => $imgName) {
                            if ($_FILES["images"]["error"][$key] == UPLOAD_ERR_OK) {
                                $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
                                $imgNew = time() . "_" . $key . "_" . $slug . "." . $imgExt;
                                $imgPath = __DIR__ . "/../../uploads/products/" . $imgNew;
                                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $imgPath)) {
                                    // Lưu vào bảng product_images
                                    $productDAO->insertImage($productId, $imgNew);
                                }
                            }
                        }
                    }
                    header("Location: /MiniShop_HoThiBichNhung/admin/product");
                    exit();
                } else {
                    $errors[] = "Thêm sản phẩm thất bại. Vui lòng thử lại!";
                }
            }
            // Giữ lại giá trị hiển thị trên form nếu có lỗi
            $proname = $productname;
            $categoryId = $categoryid;
            $brandId = $brandid;
            $discountPrice = $pricediscount;
        }
        require __DIR__ . "/../../views/admin/products/create.php";
    }

    public function edit()
    {
        $pageTitle = "Cập nhật sản phẩm";
        $errors = [];
        $dao = new ProductDAO();
        $categoryDAO = new CategoryDAO();
        $brandDAO = new BrandDAO();
        $categories = $categoryDAO->getAll();
        $brands = $brandDAO->getAll();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $product = $dao->findById($id);
        if (!$product) {
            header("Location: /MiniShop_HoThiBichNhung/admin/product");
            exit();
        }
        $productOld = $product;
        $productImages = $dao->getImagesByProductId($id);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CsrfMiddleware::verify();
            $categoryid = (int)($_POST["categoryId"] ?? 0);
            $brandid = (int)($_POST["brandId"] ?? 0);
            $productname = trim($_POST["proname"] ?? "");
            $slug = trim($_POST["slug"] ?? "");
            $price = (float)($_POST["price"] ?? 0);
            $pricediscount = (float)($_POST["discountPrice"] ?? 0);
            $quantity = (int)($_POST["quantity"] ?? 0);
            $description = trim($_POST["description"] ?? "");
            $status = isset($_POST["status"]) ? (int)$_POST["status"] : 0;
            $fileName = $_FILES["image"] ?? "";
            $image = "";
            // Đọc thông tin hình ảnh
            $fileName = $_FILES["image"]["name"] ?? "";
            $tmpName  = $_FILES["image"]["tmp_name"] ?? "";
            $fileSize = $_FILES["image"]["size"] ?? 0;
            $Error    = $_FILES["image"]["error"] ?? 0;
            $errors = [];
            if ($productname == "") $errors[] = "Tên sản phẩm không được để trống.";
            if ($slug == "") $errors[] = "Slug không được để trống.";
            if ($categoryid == 0) $errors[] = "Vui lòng chọn danh mục.";
            if ($brandid == 0) $errors[] = "Vui lòng chọn thương hiệu.";
            if ($price <= 0) $errors[] = "Giá sản phẩm phải lớn hơn 0.";
            if ($quantity < 0) $errors[] = "Số lượng không hợp lệ.";
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
                $image = $productOld->image;
                // Có chọn hình ảnh mới
                if ($fileName != "") {
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $image = time() . "_" . $slug . "." . $extension;
                    $uploadPath = __DIR__ . "/../../uploads/products/" . $image;
                    // Xóa hình ảnh cũ (nếu có)
                    if (!empty($productOld->image)) {
                        $oldImage = __DIR__ . "/../../uploads/products/" . $productOld->image;
                        if (file_exists($oldImage)) {
                            unlink($oldImage);
                        }
                    }
                    // Upload hình ảnh mới
                    move_uploaded_file($tmpName, $uploadPath);
                }
                $product->image = $image;
                $product->categoryId = $categoryid;
                $product->brandId = $brandid;
                $product->proname = $productname;
                $product->slug = $slug;
                $product->price = $price;
                $product->discountPrice = $pricediscount;
                $product->quantity = $quantity;
                $product->description = $description;
                $product->status = $status;
                if ($dao->update($product)) {
                    // Đọc dữ liệu Upload gallery
                    if (isset($_FILES["images"]["name"]) && is_array($_FILES["images"]["name"])) {
                        foreach ($_FILES["images"]["name"] as $key => $imgName) {
                            if ($_FILES["images"]["error"][$key] == UPLOAD_ERR_OK) {
                                $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
                                $imgNew = time() . "_" . $key . "_" . $slug . "." . $imgExt;
                                $imgPath = __DIR__ . "/../../uploads/products/" . $imgNew;
                                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $imgPath)) {
                                    // Lưu vào bảng product_images
                                    $dao->insertImage($id, $imgNew);
                                }
                            }
                        }
                    }
                    header("Location: /MiniShop_HoThiBichNhung/admin/product");
                    exit();
                } else {
                    $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
                }
            }
            // Làm mới gallery sau POST
            $productImages = $dao->getImagesByProductId($id);
        }
        require __DIR__ . "/../../views/admin/products/edit.php";
    }

    public function detail()
    {
        $pageTitle = "Chi tiết sản phẩm";
        $dao = new ProductDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $product = $dao->findById($id);
        if (!$product) {
            header("Location: /MiniShop_HoThiBichNhung/admin/product");
            exit();
        }
        // Gọi phương thức getImagesByProductId($productId)
        $productImages = $dao->getImagesByProductId($id);
        require __DIR__ . "/../../views/admin/products/detail.php";
    }

    public function delete()
    {
        $dao = new ProductDAO();
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        if ($id > 0 && $dao->delete($id)) {
            header("Location: /MiniShop_HoThiBichNhung/admin/product");
            exit();
        } else {
            header("Location: /MiniShop_HoThiBichNhung/admin/product?error=Xóa sản phẩm thất bại!");
            exit();
        }
    }
    
}