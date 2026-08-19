<?php
$pageTitle = "Danh sách đơn hàng";
$status = isset($_GET['status']) && $_GET['status'] !== '' ? (int)$_GET['status'] : null;
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách đơn hàng</h4>
        </div>

        <form method="GET" class="row mb-3 ">
            <input type="hidden" name="page" value="1">
            <div class="col-md-6">
                <div class="input-group">
                    <input
                        type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
                        class="form-control" placeholder="Tìm theo mã đơn hoặc tên khách...">
                    <button class="btn btn-primary" type="submit">Tìm</button>
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="" <?= $status === null ? 'selected' : '' ?>>-- Tất cả trạng thái --</option>
                    <option value="0" <?= $status === 0 ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="1" <?= $status === 1 ? 'selected' : '' ?>>Đã xác nhận</option>
                    <option value="2" <?= $status === 2 ? 'selected' : '' ?>>Đang giao</option>
                    <option value="3" <?= $status === 3 ? 'selected' : '' ?>>Hoàn thành</option>
                    <option value="4" <?= $status === 4 ? 'selected' : '' ?>>Đã hủy</option>
                </select>
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
                                    case 0:
                                        echo '<span class="badge bg-secondary">Chờ xác nhận</span>';
                                        break;
                                    case 1:
                                        echo '<span class="badge bg-info">Đã xác nhận</span>';
                                        break;
                                    case 2:
                                        echo '<span class="badge bg-warning text-dark">Đang giao</span>';
                                        break;
                                    case 3:
                                        echo '<span class="badge bg-success">Hoàn thành</span>';
                                        break;
                                    case 4:
                                        echo '<span class="badge bg-danger">Đã hủy</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-secondary">Chờ xác nhận</span>';
                                }
                                ?>
                            </td>
                            <td><?= $order->createdAt ?></td>
                            <td class="text-center">
                                <a href="/MiniShop_HoThiBichNhung/admin/order/detail/<?= $order->id ?>" class="btn btn-info btn-sm text-white">Chi tiết</a>
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