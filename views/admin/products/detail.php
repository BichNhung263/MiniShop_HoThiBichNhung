<?php
$pageTitle = "Chi tiết sản phẩm";
require_once __DIR__ . "/../../../dao/ProductDAO.php";

$dao = new ProductDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$product = $dao->findById($id);
if (!$product) { header("Location: index.php"); exit(); }
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Chi tiết sản phẩm</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th width="200">ID</th><td><?= $product->id ?></td></tr>
                <tr><th>Tên sản phẩm</th><td><?= $product->proname ?></td></tr>
                <tr><th>Slug</th><td><?= $product->slug ?></td></tr>
                <tr><th>Danh mục</th><td><span class="badge bg-info text-dark"><?= $product->cateName ?></span></td></tr>
                <tr><th>Thương hiệu</th><td><span class="badge bg-secondary"><?= $product->brandName ?></span></td></tr>
                <tr><th>Giá bán</th><td class="text-danger fw-bold"><?= number_format($product->price, 0, ',', '.') ?> đ</td></tr>
                <tr><th>Giá khuyến mãi</th><td><?= number_format($product->discountPrice, 0, ',', '.') ?> đ</td></tr>
                <tr><th>Số lượng tồn kho</th><td><?= $product->quantity ?></td></tr>
                <tr><th>Mô tả</th><td><?= $product->description ?></td></tr>
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
