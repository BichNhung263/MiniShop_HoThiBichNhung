<?php
$pageTitle = "Chi tiết thương hiệu";

$dao = new \DAO\BrandDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$brand = $dao->findById($id);
if (!$brand) { header("Location: /MiniShop_HoThiBichNhung/admin/brand"); exit(); }
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Chi tiết thương hiệu</h4>
        </div>
        <div class="card-body">
            <!-- Hình ảnh thương hiệu -->
            <div class="text-center mb-4">
                <?php if (!empty($brand->image)): ?>
                    <img src="/MiniShop_HoThiBichNhung/uploads/brands/<?= $brand->image ?>"
                        alt="<?= $brand->brandname ?>"
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
                    <td><?= $brand->id ?></td>
                </tr>
                <tr>
                    <th>Tên thương hiệu</th>
                    <td><?= $brand->brandname ?></td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td><?= $brand->slug ?></td>
                </tr>
                <tr>
                    <th>Mô tả</th>
                    <td><?= $brand->description ?></td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($brand->status == 1): ?>
                            <span class="badge bg-success">Hiển thị</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td><?= !empty($brand->createdAt) ? date('d/m/Y H:i:s', strtotime($brand->createdAt)) : '-' ?></td>
                </tr>
            </table>
            <div class="d-flex gap-2 mt-3">
                <a href="/MiniShop_HoThiBichNhung/admin/brand/edit/<?= $brand->id ?>" class="btn btn-warning">Sửa</a>
                <a href="/MiniShop_HoThiBichNhung/admin/brand" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>
