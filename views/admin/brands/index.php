<?php 
$pageTitle = "Danh sách thương hiệu";
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách thương hiệu</h4>
            <a href="<?= BASE_URL ?>/admin/brand/create" class="btn btn-primary">Thêm thương hiệu</a>
        </div>
        <form class="row mb-3">
            <div class="col-md-4">
                <form method="GET" class="d-flex">
                    <input
                        type="text" name="keyword" value="<?= ($keyword) ?>"
                        class="form-control" placeholder="Nhập tên thương hiệu...">
                    <!-- Giữ số sản phẩm/trang -->
                    <input type="hidden" name="limit" value="<?= $limit ?>">
                    <button class="btn btn-primary ms-2">Tìm </button>
                </form>
            </div>
        </form>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Hình ảnh</th>
                    <th>Tên thương hiệu</th>
                    <th>Slug</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th width="220" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($brands)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <?= !empty($keyword) ? "Không tìm thấy dữ liệu." : "Chưa có thương hiệu nào." ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($brands as $key => $brand): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td>
                                <?php if (!empty($brand->image)): ?>
                                    <img src="<?= BASE_URL ?>/uploads/brands/<?= $brand->image ?>"
                                        alt="<?= $brand->brandname ?>"
                                        width="60" height="60" class="img-thumbnail object-fit-cover">
                                <?php else: ?>
                                    <span class="badge bg-light text-secondary border">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $brand->brandname ?></td>
                            <td><?= $brand->slug ?></td>
                            <td>
                                <?php if ($brand->status == 1): ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($brand->createdAt) ? date('d/m/Y', strtotime($brand->createdAt)) : '-' ?></td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/admin/brand/detail/<?= $brand->id ?>" class="btn btn-info btn-sm text-white me-1">Chi tiết</a>
                                <a href="<?= BASE_URL ?>/admin/brand/edit/<?= $brand->id ?>" class="btn btn-warning btn-sm me-1">Sửa</a>
                                <a href="<?= BASE_URL ?>/admin/brand/delete/<?= $brand->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
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
include __DIR__ . "/../layouts/master.php";
?>