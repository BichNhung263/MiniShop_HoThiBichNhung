<?php
$pageTitle = "Danh sách sản phẩm";
require_once __DIR__ . "/../../../dao/ProductDAO.php";

$keyword = trim($_GET["keyword"] ?? "");
$dao = new ProductDAO();
$products = $dao->getAll($keyword);
ob_start();

$limit = 10;
$keyword = trim($_GET["keyword"] ?? "");
$page = (int)($_GET["page"] ?? 1);
$limit = (int)($_GET["limit"] ?? 10);
$offset = ($page - 1) * $limit;
$productDAO = new ProductDAO();
$totalRecords = $productDAO->count("products", "proname", $keyword);
$totalPages = ceil($totalRecords / $limit);
$products = $productDAO->getPage($limit, $offset, $keyword);
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
                <form method="GET" class="d-flex">
                    <input
                        type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
                        class="form-control" placeholder="Nhập tên sản phẩm...">
                    <!-- Giữ số sản phẩm/trang -->
                    <input type="hidden" name="limit" value="<?= $limit ?>">
                    <button class="btn btn-primary ms-2">Tìm </button>
                </form>
            </div>

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


        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex align-items-center">
                <label class="me-2">Hiển thị</label>
                <form method="GET">
                    <select name="limit" class="form-select" onchange="this.form.submit()">
                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>
                            10
                        </option>
                        <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>
                            20
                        </option>
                        <option value="30" <?= $limit == 30 ? 'selected' : '' ?>>
                            30
                        </option>

                    </select>
                </form>
            </div>
        </div>
        <nav>
            <ul class="pagination">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&page=<?= $page - 1 ?>">
                        Trước
                    </a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?limit=<?= $limit ?>&page=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php } ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&page=<?= $page + 1 ?>">
                        Sau
                    </a>
                </li>
            </ul>
        </nav>
    </section>
</main>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>