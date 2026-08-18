<?php
$pageTitle = "Thêm sản phẩm";
use DAO\CategoryDAO;
use DAO\BrandDAO;
use DAO\ProductDAO;
use Middleware\CsrfMiddleware;

$categoryDAO = new \DAO\CategoryDAO();
$brandDAO = new \DAO\BrandDAO();

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
    \Middleware\CsrfMiddleware::verify();
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
            $uploadDir = __DIR__ . "/../../../uploads/products/" . $image;
            move_uploaded_file($tmpName, $uploadDir);
        }

        // + Tạo Product
        $product = new \Models\Product(
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
        $productDAO = new \DAO\ProductDAO();
        if ($productDAO->insert($product)) {
            $productId = $product->id;

            // Đọc dữ liệu Upload
            // Sử dụng vòng lặp để Upload từng file
            foreach ($_FILES["images"]["name"] as $key => $imgName) {
                if ($_FILES["images"]["error"][$key] == UPLOAD_ERR_OK) {
                    $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
                    $imgNew = time() . "_" . $key . "_" . $slug . "." . $imgExt;
                    $imgPath = __DIR__ . "/../../../uploads/products/" . $imgNew;
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $imgPath)) {
                        // Lưu vào bảng product_images
                        $productDAO->insertImage($productId, $imgNew);
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
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Thêm sản phẩm mới</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)) { ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error) { ?>
                            <li><?= $error ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="proname" value="<?= $proname ?>" placeholder="Nhập tên sản phẩm...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" value="<?= $slug ?>" placeholder="nhap-slug...">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục</label>
                        <select name="categoryId" class="form-select">
                            <option value="" <?= $categoryId == 0 ? 'selected' : '' ?>>-- Chọn danh mục --</option>
                            <?php foreach ($categories as $item) { ?>
                                <option value="<?= $item->id ?>" <?= $categoryId == $item->id ? 'selected' : '' ?>><?= $item->catename ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <select name="brandId" class="form-select">
                            <option value="" <?= $brandId == 0 ? 'selected' : '' ?>>-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $item) { ?>
                                <option value="<?= $item->id ?>" <?= $brandId == $item->id ? 'selected' : '' ?>><?= $item->brandname ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" value="<?= $price ?>" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input type="number" class="form-control" name="discountPrice" value="<?= $discountPrice ?>" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" class="form-control" name="quantity" value="<?= $quantity ?>" min="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả sản phẩm</label>
                    <textarea class="form-control" name="description" rows="4"><?= $description ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="text-center mb-3" id="preview"></div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh phụ </label>
                    <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Hiển thị</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $status == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">Ẩn</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                    <button type="reset" class="btn btn-warning">Làm mới</button>
                    <a href="/MiniShop_HoThiBichNhung/admin/product" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>