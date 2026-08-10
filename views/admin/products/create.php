<?php
$pageTitle = "Thêm sản phẩm";
require_once __DIR__ . "/../../../dao/ProductDAO.php";
require_once __DIR__ . "/../../../dao/CategoryDAO.php";
require_once __DIR__ . "/../../../dao/BrandDAO.php";

$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();

$errors = [];
$proname = $slug = $description = "";
$categoryId = $brandId = 0;
$price = 0;
$discountPrice = 0;
$quantity = 0;
$status = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proname = trim($_POST["proname"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $categoryId = isset($_POST["categoryId"]) && $_POST["categoryId"] !== "" ? (int)$_POST["categoryId"] : 0;
    $brandId = isset($_POST["brandId"]) && $_POST["brandId"] !== "" ? (int)$_POST["brandId"] : 0;
    $price = isset($_POST["price"]) ? floatval($_POST["price"]) : 0;
    $discountPrice = isset($_POST["discountPrice"]) ? floatval($_POST["discountPrice"]) : 0;
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0;
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? 1;

    $errors = [];
    if ($proname == "") {
        $errors[] = "Tên sản phẩm không được để trống.";
    }
    if ($slug == "") {
        $errors[] = "Slug không được để trống.";
    }
    if ($categoryId == 0) {
        $errors[] = "Vui lòng chọn danh mục.";
    }
    if ($brandId == 0) {
        $errors[] = "Vui lòng chọn thương hiệu.";
    }
    if ($price <= 0) {
        $errors[] = "Giá bán phải lớn hơn 0.";
    }
    if ($quantity < 0) {
        $errors[] = "Số lượng không hợp lệ.";
    }

    if (empty($errors)) {
        $dao = new ProductDAO();
        $product = new Product($categoryId, $brandId, $proname, $slug, $price, $discountPrice, $quantity, null, $description, $status);
        if ($dao->insert($product)) {
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
            <h4>Thêm sản phẩm mới</h4>
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
                            <?php foreach($brands as $item){ ?>
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