<?php
$pageTitle = "Danh sách khách hàng";
require_once __DIR__ . "/../../../dao/CustomerDAO.php";

$keyword = trim($_GET["keyword"] ?? "");
$dao = new CustomerDAO();
$customers = $dao->getAll($keyword);
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách khách hàng</h4>
            <a href="create.php" class="btn btn-primary">Thêm khách hàng</a>
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
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th width="220" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <?= !empty($keyword) ? "Không tìm thấy dữ liệu." : "Chưa có khách hàng nào." ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $key => $customer): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $customer->fullname ?></td>
                            <td><?= $customer->email ?></td>
                            <td><?= $customer->phone ?></td>
                            <td><?= $customer->address ?></td>
                            <td>
                                <?php if ($customer->status == 1): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($customer->createdAt) ? date('d/m/Y', strtotime($customer->createdAt)) : '-' ?></td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $customer->id ?>" class="btn btn-info btn-sm text-white me-1">Chi tiết</a>
                                <a href="edit.php?id=<?= $customer->id ?>" class="btn btn-warning btn-sm me-1">Sửa</a>
                                <a href="delete.php?id=<?= $customer->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
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
