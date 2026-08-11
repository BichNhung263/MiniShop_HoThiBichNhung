<?php
$pageTitle = "Cập nhật thương hiệu";
require_once __DIR__ . "/../../../dao/BrandDAO.php";

$errors = [];
$dao = new BrandDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$brand = $dao->findById($id);
if (!$brand) { header("Location: index.php"); exit(); }

$brandOld = clone $brand;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            $uploadDir = __DIR__ . "/../../../uploads/brands/";
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
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
        }
    }
}
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Cập nhật thương hiệu</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="brandId" value="<?= $brand->id ?>">

                <div class="text-center mb-3" id="preview">
                    <?php if (!empty($brand->image)): ?>
                        <img src="/MiniShop_HoThiBichNhung/uploads/brands/<?= $brand->image ?>" class="img-thumbnail" width="150" id="preview">
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="brandname" value="<?= $brand->brandname ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" value="<?= $brand->slug ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="4"><?= $brand->description ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $brand->status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Hiển thị</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $brand->status == 0 ? 'checked' : '' ?>>
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
