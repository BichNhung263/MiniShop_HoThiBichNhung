<?php
ob_start();
?>

<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Chi tiết danh mục</h4>
        </div>
        <div class="card-body">
            <!-- Hình ảnh danh mục -->
            <div class="text-center mb-4">
                <?php if (!empty($category->image)): ?>
                    <img src="<?= BASE_URL ?>/uploads/categories/<?= $category->image ?>"
                        alt="<?= $category->catename ?>"
                        class="img-fluid img-thumbnail"
                        style="max-height: 200px;">
                <?php else: ?>
                    <div class="border rounded d-flex align-items-center justify-content-center bg-light mx-auto"
                        style="height: 150px; width: 200px;">
                        <span class="text-muted fs-5">No Image</span>
                    </div>
                <?php endif; ?>
            </div>
            <table class="table table-bordered">
                <tr>
                    <th width="200">ID</th>
                    <td><?= $category->id ?></td>
                </tr>
                <tr>
                    <th>Tên danh mục</th>
                    <td><?= $category->catename ?></td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td><?= $category->slug ?></td>
                </tr>
                <tr>
                    <th>Mô tả</th>
                    <td><?= $category->description ?></td>
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
                <a href="<?= BASE_URL ?>/admin/category/edit/<?= $category->id ?>" class="btn btn-warning">Sửa</a>
                <a href="<?= BASE_URL ?>/admin/category" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>