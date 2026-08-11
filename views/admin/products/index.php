<?php
$pageTitle = "Danh sách sản phẩm";
require_once __DIR__ . "/../../../dao/ProductDAO.php";

$keyword = trim($_GET["keyword"] ?? "");
$dao = new ProductDAO();
$products = $dao->getAll($keyword);
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách sản phẩm</h4>
            <a href="create.php" class="btn btn-primary fw-bold">Thêm sản phẩm</a>
        </div>

        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-danger"><?= $_GET['error'] ?></div>
        <?php endif; ?>

        <form class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control"
                    placeholder="Nhập từ khóa..." value="<?= $_GET['keyword'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th width="220" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            <?= !empty($keyword) ? "Không tìm thấy dữ liệu." : "Chưa có sản phẩm nào." ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $key => $item): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td>

                                <?php if ($item->image != "") { ?>
                                    <img
                                        src="/MiniShop_HoThiBichNhung/uploads/products/<?= $item->image ?>"
                                        alt="<?= $item->productName ?>"
                                        class="img-thumbnail"
                                        width="80">
                                <?php } else { ?>
                                    <span class="text-muted">
                                        No Image
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="fw-semibold"><?= $item->proname ?></td>
                            <td><span class="badge bg-info text-dark"><?= $item->cateName ?></span></td>
                            <td><span class="badge bg-secondary"><?= $item->brandName ?></span></td>
                            <td class="text-danger fw-bold"><?= number_format($item->price, 0, ',', '.') ?> đ</td>
                            <td><?= $item->quantity ?></td>
                            <td>
                                <?php if ($item->status == 1): ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $item->id ?>" class="btn btn-info btn-sm text-white me-1">Chi tiết</a>
                                <a href="edit.php?id=<?= $item->id ?>" class="btn btn-warning btn-sm me-1">Sửa</a>
                                <a href="delete.php?id=<?= $item->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>