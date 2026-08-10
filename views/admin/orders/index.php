<?php
$pageTitle = "Danh sách đơn hàng";
require_once __DIR__ . "/../../../dao/OrderDAO.php";

$keyword = trim($_GET['keyword'] ?? "");
$status = isset($_GET['status']) && $_GET['status'] !== '' ? (int)$_GET['status'] : null;
$dao = new OrderDAO();
$orders = $dao->getAll($keyword, $status);
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách đơn hàng</h4>
        </div>

        <form class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo mã đơn hoặc tên khách..." value="<?= htmlentities($_GET['keyword'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="" <?= $status === null ? 'selected' : '' ?>>-- Tất cả trạng thái --</option>
                    <option value="0" <?= $status === 0 ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="1" <?= $status === 1 ? 'selected' : '' ?>>Đã xác nhận</option>
                    <option value="2" <?= $status === 2 ? 'selected' : '' ?>>Đang giao</option>
                    <option value="3" <?= $status === 3 ? 'selected' : '' ?>>Hoàn thành</option>
                    <option value="4" <?= $status === 4 ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Nhân viên</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th width="200" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted"><?= !empty($keyword) ? "Không tìm thấy dữ liệu." : "Chưa có đơn hàng nào." ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $key => $order): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $order->orderCode ?></td>
                            <td><?= $order->customerName ?? '-' ?></td>
                            <td><?= $order->userName ?? '-' ?></td>
                            <td class="text-danger fw-bold"><?= number_format($order->totalAmount, 0, ',', '.') ?> đ</td>
                            <td>
                                <?php
                                switch ($order->status) {
                                    case 0: echo '<span class="badge bg-secondary">Chờ xác nhận</span>'; break;
                                    case 1: echo '<span class="badge bg-info">Đã xác nhận</span>'; break;
                                    case 2: echo '<span class="badge bg-warning text-dark">Đang giao</span>'; break;
                                    case 3: echo '<span class="badge bg-success">Hoàn thành</span>'; break;
                                    case 4: echo '<span class="badge bg-danger">Đã hủy</span>'; break;
                                    default: echo '<span class="badge bg-secondary">Chờ xác nhận</span>';
                                }
                                ?>
                            </td>
                            <td><?= $order->createdAt ?></td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $order->id ?>" class="btn btn-info btn-sm text-white">Chi tiết</a>
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