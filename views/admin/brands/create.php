<?php
$pageTitle = "Thêm thương hiệu";
$errors = [];
$brandDAO = new BrandDAO();
$brandname = $slug = $description = "";
$status = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            $uploadDir = __DIR__ . "/../../../uploads/brands/" . $image;
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
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Thêm thất bại. Vui lòng thử lại!";
        }
    }
}
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Thêm thương hiệu mới</h4>
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
                <div class="mb-3">
                    <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="brandname" value="<?= $brandname ?>" placeholder="Nhập tên thương hiệu...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" value="<?= $slug ?>" placeholder="nhap-slug...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="4"><?= $description ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="text-center mb-3" id="preview"></div>
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
