<?php
$pageTitle = "Thêm thương hiệu";
require_once __DIR__ . "/../../../dao/BrandDAO.php";

$errors = [];
$brandname = $slug = $description = "";
$status = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brandname = trim($_POST["brandname"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    if ($brandname == "") $errors[] = "Tên thương hiệu không được để trống.";
    if ($slug == "") $errors[] = "Slug không được để trống.";

    if (empty($errors)) {
        $dao = new BrandDAO();
        $brand = new Brand($brandname, $slug, $description, null, $status);
        if ($dao->insert($brand)) {
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
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên thương hiệu</label>
                    <input type="text" class="form-control" name="brandname" value="<?= $brandname ?>" placeholder="Nhập tên thương hiệu...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= $slug ?>" placeholder="nhap-slug...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="4"><?= $description ?></textarea>
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
