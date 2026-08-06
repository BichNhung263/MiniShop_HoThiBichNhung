<?php
$pageTitle = "Danh sách danh mục";
require_once __DIR__ . "/../../../dao/CategoryDAO.php";

$categoryDAO = new CategoryDAO();
$keyword = trim($_GET["keyword"] ?? "");
$categories = [];
try {
    $categories = $categoryDAO->getAll($keyword);
} catch (Exception $e) {
}
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách danh mục</h4>
            <a href="create.php" class="btn btn-primary">
                Thêm danh mục
            </a>
        </div>

        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form class="row mb-3" method="GET">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Nhập từ khóa..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
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
                        <td colspan="6" class="text-center text-muted">
                            <?= !empty($keyword) ? "Không tìm thấy dữ liệu." : "Chưa có danh mục nào." ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $key => $category): ?>
                        <tr>
                            <td><?= ($key + 1); ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($category->catename); ?></td>
                            <td><code><?= htmlspecialchars($category->slug); ?></code></td>
                            <td>
                                <?php if ($category->status == 1): ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($category->createdAt) ? date('d/m/Y', strtotime($category->createdAt)) : '-'; ?></td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $category->id; ?>" class="btn btn-info btn-sm text-white me-1">
                                    Chi tiết
                                </a>
                                <a href="edit.php?id=<?= $category->id; ?>" class="btn btn-warning btn-sm me-1">
                                    Sửa
                                </a>
                                <a href="delete.php?id=<?= $category->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">
                                    Xóa
                                </a>
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
