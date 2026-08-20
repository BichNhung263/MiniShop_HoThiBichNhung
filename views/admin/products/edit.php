<?php
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
                <input type="hidden" name="csrf_token" value="<?= ($_SESSION['csrf_token'] ?? '') ?>">
                <input type="hidden" name="productId" value="<?= $product->id ?>">
                <div class="text-center mb-3" id="preview">
                    <?php if (!empty($product->image)) { ?>
                        <img src="<?= BASE_URL ?>/uploads/products/<?= $product->image ?>" class="img-thumbnail" width="150">
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
                                    <img src="<?= BASE_URL ?>/uploads/products/<?= $img->image ?>"
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
                    <a href="<?= BASE_URL ?>/admin/product" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>