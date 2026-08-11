<?php
$pageTitle = "Cập nhật sản phẩm";
require_once __DIR__ . "/../../../dao/ProductDAO.php";
require_once __DIR__ . "/../../../dao/CategoryDAO.php";
require_once __DIR__ . "/../../../dao/BrandDAO.php";

$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();

$errors = [];
$dao = new ProductDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$product = $dao->findById($id);
if (!$product) { header("Location: index.php"); exit(); }

$productOld = $product;

// Gọi phương thức getImagesByProductId($productId)
$productImages = $dao->getImagesByProductId($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            $uploadPath = __DIR__ . "/../../../uploads/products/" . $image;

            // Xóa hình ảnh cũ (nếu có)
            if (!empty($productOld->image)) {
                $oldImage = __DIR__ . "/../../../uploads/products/" . $productOld->image;
                if (file_exists($oldImage)) {
                    unlink($oldImage); // xóa file
                }
            }

            // Upload hình ảnh mới
            move_uploaded_file($tmpName, $uploadPath);
        }

        $product->image = $image;

        if ($dao->update($product)) {
            // Đọc dữ liệu Upload gallery
            // Sử dụng vòng lặp để Upload từng file
            foreach ($_FILES["images"]["name"] as $key => $imgName) {
                if ($_FILES["images"]["error"][$key] == UPLOAD_ERR_OK) {
                    $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
                    $imgNew = time() . "_" . $key . "_" . $slug . "." . $imgExt;
                    $imgPath = __DIR__ . "/../../../uploads/products/" . $imgNew;
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $imgPath)) {
                        // Lưu vào bảng product_images
                        $dao->insertImage($id, $imgNew);
                    }
                }
            }

            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
        }
    }

    // Làm mới gallery sau POST
    $productImages = $dao->getImagesByProductId($id);
}
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Cập nhật sản phẩm</h4>
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
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="productId" value="<?= $product->id ?>">
                <div class="text-center mb-3" id="preview">
                    <?php if (!empty($product->image)) { ?>
                        <img src="/MiniShop_HoThiBichNhung/uploads/products/<?= $product->image ?>" class="img-thumbnail" width="150">
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="proname" value="<?= $product->proname ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" value="<?= $product->slug ?>">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục</label>
                        <select name="categoryId" class="form-select">
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= $product->categoryId == $cat->id ? 'selected' : '' ?>>
                                    <?= $cat->catename ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <select name="brandId" class="form-select">
                            <option value="">-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $b): ?>
                                <option value="<?= $b->id ?>" <?= $product->brandId == $b->id ? 'selected' : '' ?>>
                                    <?= $b->brandname ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" value="<?= $product->price ?>" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input type="number" class="form-control" name="discountPrice" value="<?= $product->discountPrice ?>" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" class="form-control" name="quantity" value="<?= $product->quantity ?>" min="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả sản phẩm</label>
                    <textarea class="form-control" name="description" rows="4"><?= $product->description ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh phụ</label>
                    <input
                        type="file"
                        name="images[]"
                        id="images"
                        class="form-control"
                        accept="image/*"
                        multiple>
                </div>
                <!-- Hiển thị ảnh phụ - vừa xem vừa có thể xóa từng hình ảnh -->
                <?php if (!empty($productImages)) { ?>
                    <div class="mb-3">
                        <label class="form-label">Ảnh phụ hiện tại</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($productImages as $img) { ?>
                                <div class="text-center">
                                    <img src="/MiniShop_HoThiBichNhung/uploads/products/<?= $img->image ?>"
                                        class="img-thumbnail"
                                        style="width: 100px; height: 85px; object-fit: cover;">
                                    <br>
                                    <a href="delete_image.php?id=<?= $img->id ?>&product_id=<?= $id ?>"
                                        class="btn btn-danger btn-sm mt-1"
                                        onclick="return confirm('Xóa hình ảnh này?');">Xóa</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $product->status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Hiển thị</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $product->status == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">Ẩn</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <button type="reset" class="btn btn-warning">Làm mới</button>
                    <a href="index.php" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
