<?php
$pageTitle = "Chi tiết danh mục";
require_once __DIR__ . "/../../../dao/CategoryDAO.php";

$categoryDAO = new CategoryDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$category = $categoryDAO->findById($id);

if (!$category) {
    header("Location: index.php");
    exit();
}
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Chi tiết danh mục</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">ID</th>
                    <td><?= $category->id ?></td>
                </tr>
                <tr>
                    <th>Tên danh mục</th>
                    <td><?= htmlspecialchars($category->catename) ?></td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td><?= htmlspecialchars($category->slug) ?></td>
                </tr>
                <tr>
                    <th>Mô tả</th>
                    <td><?= htmlspecialchars($category->description ?? '') ?></td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($category->status == 1): ?>
                            <span class="badge bg-success">Hiển thị</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td><?= !empty($category->createdAt) ? date('d/m/Y H:i:s', strtotime($category->createdAt)) : '-' ?></td>
                </tr>
            </table>

            <div class="d-flex gap-2 mt-3">
                <a href="edit.php?id=<?= $category->id ?>" class="btn btn-warning">Sửa</a>
                <a href="index.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
