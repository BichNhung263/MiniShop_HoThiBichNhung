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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product->proname = trim($_POST["proname"] ?? "");
    $product->slug = trim($_POST["slug"] ?? "");
    $product->categoryId = isset($_POST["categoryId"]) && $_POST["categoryId"] !== "" ? (int)$_POST["categoryId"] : 0;
    $product->brandId = isset($_POST["brandId"]) && $_POST["brandId"] !== "" ? (int)$_POST["brandId"] : 0;
    $product->price = isset($_POST["price"]) ? floatval($_POST["price"]) : 0;
    $product->discountPrice = isset($_POST["discountPrice"]) ? floatval($_POST["discountPrice"]) : 0;
    $product->quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0;
    $product->description = trim($_POST["description"] ?? "");
    $product->status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    if ($product->proname == "") $errors[] = "Tên sản phẩm không được để trống.";
    if ($product->slug == "") $errors[] = "Slug không được để trống.";
    if ($product->categoryId == 0) $errors[] = "Vui lòng chọn danh mục.";
    if ($product->brandId == 0) $errors[] = "Vui lòng chọn thương hiệu.";
    if ($product->price <= 0) $errors[] = "Giá sản phẩm phải lớn hơn 0.";
    if ($product->quantity < 0) $errors[] = "Số lượng không hợp lệ.";

    if (empty($errors)) {
        if ($dao->update($product)) {
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
            <h4>Cập nhật sản phẩm</h4>
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
                <input type="hidden" name="productId" value="<?= $product->id ?>">
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
