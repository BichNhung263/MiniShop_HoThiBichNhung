<?php
$pageTitle = "Cập nhật thương hiệu";
require_once __DIR__ . "/../../../dao/BrandDAO.php";

$errors = [];
$dao = new BrandDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$brand = $dao->findById($id);
if (!$brand) { header("Location: index.php"); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand->brandname = trim($_POST["brandname"] ?? "");
    $brand->slug = trim($_POST["slug"] ?? "");
    $brand->description = trim($_POST["description"] ?? "");
    $brand->status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    if ($brand->brandname == "") $errors[] = "Tên thương hiệu không được để trống.";
    if ($brand->slug == "") $errors[] = "Slug không được để trống.";

    if (empty($errors)) {
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
            <form action="" method="POST">
                <input type="hidden" name="brandId" value="<?= $brand->id ?>">
                <div class="mb-3">
                    <label class="form-label">Tên thương hiệu</label>
                    <input type="text" class="form-control" name="brandname" value="<?= $brand->brandname ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= $brand->slug ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="4"><?= $brand->description ?></textarea>
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
