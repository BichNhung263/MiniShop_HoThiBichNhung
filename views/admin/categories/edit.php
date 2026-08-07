<?php
$pageTitle = "Cập nhật danh mục";
require_once __DIR__ . "/../../../dao/CategoryDAO.php";

$categoryDAO = new CategoryDAO();
$errors = [];

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$category = $categoryDAO->findById($id);

if (!$category) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cateName = trim($_POST["cateName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    $category->catename = $cateName;
    $category->slug = $slug;
    $category->description = $description;
    $category->status = $status;

    if ($cateName == "") {
        $errors[] = "Tên danh mục không được để trống.";
    }
    if ($slug == "") {
        $errors[] = "Slug không được để trống.";
    }

    if (empty($errors)) {
        try {
            if ($categoryDAO->update($category)) {
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
            }
        } catch (Exception $e) {
            $errors[] = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
ob_start();
?>

<main class="container my-4">
    <div class="container mt-4">
<div class="card">
<div class="card-header">
<h4>Cập nhật danh mục</h4>
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

            <form action="" method="POST">
                <input type="hidden" name="categoryId" value="<?= $category->id ?>">

                <div class="mb-3">
                    <label class="form-label">Tên danh mục</label>
                    <input type="text" class="form-control" name="cateName" value="<?= $category->catename ?>">
                </div>
                <div class="mb-3">
                    <label  class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= $category->slug ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="5" class="form-control"><?= $category->description ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $category->status == 1 ? "checked" : "" ?>>
                        <label class="form-check-label">Hiển thị / Hoạt động</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $category->status == 0 ? "checked" : "" ?>>
                        <label class="form-check-label">Ẩn / Ngừng hoạt động</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Cập nhật
                    </button>
                    <button type="reset" class="btn btn-warning">
                        Làm mới
                    </button>
                    <a href="index.php" class="btn btn-secondary">
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
