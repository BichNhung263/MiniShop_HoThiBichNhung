<?php
$pageTitle = "Thêm danh mục mới";
require_once __DIR__ . "/../../../dao/CategoryDAO.php";
$categoryDAO = new CategoryDAO();
$errors = [];
$cateName = "";
$slug = "";
$description = "";
$status = 1;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cateName = trim($_POST["cateName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;
    if ($cateName == "") {
        $errors[] = "Tên danh mục không được để trống.";
    }
    if ($slug == "") {
        $errors[] = "Slug không được để trống.";
    }
    if (empty($errors)) {
        try {
            $category = new Category($cateName, $slug, $description, null, $status);
            if ($categoryDAO->insert($category)) {
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Thêm danh mục thất bại. Vui lòng thử lại!";
            }
        } catch (Exception $e) {
            $errors[] = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
ob_start();
?>
<main class="container my-4">
    <div class="card">
<div class="card-header">
<h4>Cập nhật danh mục</h4>
</div>
<div class="card-body">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên danh mục </label>
                    <input type="text" class="form-control"  name="cateName" value="<?= htmlspecialchars($cateName) ?>" placeholder="Nhập tên danh mục...">
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label fw-semibold">Slug <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="slug" name="slug" value="<?= htmlspecialchars($slug) ?>" placeholder="nhap-duong-dan-slug">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Mô tả</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Nhập mô tả ngắn..."><?= htmlspecialchars($description) ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= $status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status1">Hiển thị / Hoạt động</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= $status == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status0">Ẩn / Ngừng hoạt động</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Lưu
                    </button>
                    <button type="reset" class="btn btn-secondary px-4">
                        Làm mới
                    </button>
                    <a href="index.php" class="btn btn-outline-dark px-4">
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
