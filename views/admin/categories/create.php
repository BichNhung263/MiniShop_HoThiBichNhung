<?php
$pageTitle = "Thêm danh mục mới";

$categoryDAO = new \DAO\CategoryDAO();
$errors = [];
$cateName = $slug = $description = "";
$status = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    \Middleware\CsrfMiddleware::verify();
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
            $uploadDir = __DIR__ . "/../../../uploads/categories/" . $image;
            move_uploaded_file($tmpName, $uploadDir);
        }

        // + Tạo Category object
        $category = new \Models\Category(
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
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Thêm danh mục mới</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <div class="mb-3">
                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="cateName" value="<?= $cateName; ?>" placeholder="Nhập tên danh mục...">
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label fw-semibold">Slug <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="slug" name="slug" value="<?= $slug; ?>" placeholder="nhap-duong-dan-slug">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Mô tả</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Nhập mô tả ngắn..."><?= $description; ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="text-center mb-3" id="preview"></div>
                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Hiển thị / Hoạt động</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $status == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">Ẩn / Ngừng hoạt động</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Lưu</button>
                    <button type="reset" class="btn btn-secondary px-4">Làm mới</button>
                    <a href="/MiniShop_HoThiBichNhung/admin/category" class="btn btn-outline-dark px-4">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>