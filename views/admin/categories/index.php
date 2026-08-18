<?php
$pageTitle = "Danh sách danh mục";
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách danh mục</h4>
            <a href="/MiniShop_HoThiBichNhung/admin/category/create" class="btn btn-primary">
                Thêm danh mục
            </a>
        </div>
        <form class="row mb-3">
            <div class="col-md-4">
                <form method="GET" class="d-flex">
                    <input
                        type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
                        class="form-control" placeholder="Nhập tên danh mục...">
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
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th width="220" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <?= !empty($keyword) ? "Không tìm thấy dữ liệu." : "Chưa có danh mục nào." ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $key => $category): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td>
                                <?php if (!empty($category->image)): ?>
                                     <img src="/MiniShop_HoThiBichNhung/uploads/categories/<?= $category->image ?>"
                                         alt="<?= $category->catename ?>"
                                         width="60" height="60" class="img-thumbnail object-fit-cover">
                                <?php else: ?>
                                    <span class="badge bg-light text-secondary border">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-semibold"><?= $category->catename ?></td>
                            <td><?= $category->slug ?></td>
                            <td>
                                <?php if ($category->status == 1): ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($category->createdAt) ? date('d/m/Y', strtotime($category->createdAt)) : '-'; ?></td>
                            <td class="text-center">
                                <a href="/MiniShop_HoThiBichNhung/admin/category/detail/<?= $category->id; ?>" class="btn btn-info btn-sm text-white me-1">
                                    Chi tiết
                                </a>
                                <a href="/MiniShop_HoThiBichNhung/admin/category/edit/<?= $category->id; ?>" class="btn btn-warning btn-sm me-1">
                                    Sửa
                                </a>
                                <a href="/MiniShop_HoThiBichNhung/admin/category/delete/<?= $category->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">
                                    Xóa
                                </a>
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