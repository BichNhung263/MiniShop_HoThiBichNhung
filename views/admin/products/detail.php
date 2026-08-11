<?php
$pageTitle = "Chi tiết sản phẩm";
require_once __DIR__ . "/../../../dao/ProductDAO.php";

$dao = new ProductDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$product = $dao->findById($id);
if (!$product) { header("Location: index.php"); exit(); }

// Gọi phương thức getImagesByProductId($productId)
$productImages = $dao->getImagesByProductId($id);

ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Chi tiết sản phẩm</h4>
        </div>
        <div class="card-body">
            <!-- Hình ảnh sản phẩm -->
            <div class="text-center mb-4">
                <?php if (!empty($product->image)) { ?>
                    <img src="/MiniShop_HoThiBichNhung/uploads/products/<?= $product->image ?>"
                        alt="<?= $product->proname ?>"
                        class="img-fluid img-thumbnail"
                        style="max-height: 250px;">
                <?php } else { ?>
                    <div class="border rounded d-flex align-items-center justify-content-center bg-light mx-auto"
                        style="height: 200px; width: 250px;">
                        <span class="text-muted fs-5">No Image</span>
                    </div>
                <?php } ?>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th width="200">ID</th>
                    <td><?= $product->id ?></td>
                </tr>
                <tr>
                    <th>Tên sản phẩm</th>
                    <td><?= $product->proname ?></td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td><?= $product->slug ?></td>
                </tr>
                <tr>
                    <th>Danh mục</th>
                    <td><span class="badge bg-info text-dark"><?= $product->cateName ?></span></td>
                </tr>
                <tr>
                    <th>Thương hiệu</th>
                    <td><span class="badge bg-secondary"><?= $product->brandName ?></span></td>
                </tr>
                <tr>
                    <th>Giá bán</th>
                    <td class="text-danger fw-bold"><?= number_format($product->price, 0, ',', '.') ?> đ</td>
                </tr>
                <tr>
                    <th>Giá khuyến mãi</th>
                    <td><?= number_format($product->discountPrice, 0, ',', '.') ?> đ</td>
                </tr>
                <tr>
                    <th>Số lượng tồn kho</th>
                    <td><?= $product->quantity ?></td>
                </tr>
                <tr>
                    <th>Mô tả</th>
                    <td><?= $product->description ?></td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($product->status == 1): ?>
                            <span class="badge bg-success">Hiển thị</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr><th>Ngày tạo</th><td><?= !empty($product->createdAt) ? date('d/m/Y H:i:s', strtotime($product->createdAt)) : '-' ?></td></tr>
            </table>

            <!-- Hiển thị Gallery - chỉ xem hình ảnh -->
            <?php if (!empty($productImages)) { ?>
                <hr>
                <h5>Hình ảnh phụ </h5>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <?php foreach ($productImages as $img) { ?>
                        <img src="/MiniShop_HoThiBichNhung/uploads/products/<?= $img->image ?>"
                            alt="Gallery"
                            class="img-thumbnail"
                            style="width: 120px; height: 100px; object-fit: cover;">
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="d-flex gap-2 mt-3">
                <a href="edit.php?id=<?= $product->id ?>" class="btn btn-warning">Sửa</a>
                <a href="index.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
