<?php
$pageTitle = "Danh sách thương hiệu";
require_once __DIR__ . "/../../../dao/BrandDAO.php";

$keyword = trim($_GET["keyword"] ?? "");
$dao = new BrandDAO();
$brands = $dao->getAll($keyword);
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách thương hiệu</h4>
            <a href="create.php" class="btn btn-primary">Thêm thương hiệu</a>
        </div>
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
                                     <img src="/MiniShop_HoThiBichNhung/uploads/brands/<?= $brand->image ?>"
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
                                <a href="detail.php?id=<?= $brand->id ?>" class="btn btn-info btn-sm text-white me-1">Chi tiết</a>
                                <a href="edit.php?id=<?= $brand->id ?>" class="btn btn-warning btn-sm me-1">Sửa</a>
                                <a href="delete.php?id=<?= $brand->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
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
