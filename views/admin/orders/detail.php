<?php
$pageTitle = "Chi tiết đơn hàng";
require_once __DIR__ . "/../../../dao/OrderDAO.php";

$dao = new OrderDAO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = $dao->findById($id);
if (!$order) { header('Location: index.php'); exit(); }
$details = $dao->getOrderDetails($id);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newStatus = isset($_POST['status']) ? (int)$_POST['status'] : $order->status;
    $order->status = $newStatus;
    if ($dao->updateStatus($order->id, $newStatus)) {
        header('Location: index.php');
        exit();
    } else {
        $errors[] = 'Cập nhật trạng thái thất bại.';
    }
}

ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Chi tiết đơn hàng</h4>
            <a href="index.php" class="btn btn-secondary">Quay lại</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Mã đơn: <?= $order->orderCode ?></h5>
                <p>Khách hàng: <?= $order->customerName ?? '-' ?></p>
                <p>Nhân viên xử lý: <?= $order->userName ?? '-' ?></p>
                <p>Tổng tiền: <strong><?= number_format($order->totalAmount,0,',','.') ?> đ</strong></p>
                <p>Ghi chú: <?= $order->note ?? '-' ?></p>
                <p>Trạng thái hiện tại:
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
                </p>

                <form method="POST" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="form-label">Cập nhật trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="0" <?= $order->status==0? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="1" <?= $order->status==1? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="2" <?= $order->status==2? 'selected' : '' ?>>Đang giao</option>
                            <option value="3" <?= $order->status==3? 'selected' : '' ?>>Hoàn thành</option>
                            <option value="4" <?= $order->status==4? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mt-4">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Danh sách sản phẩm trong đơn</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($details as $k => $d): ?>
                            <tr>
                                <td><?= $k+1 ?></td>
                                <td><?= $d->productName ?? ('#' . $d->productId) ?></td>
                                <td><?= $d->quantity ?></td>
                                <td><?= number_format($d->price,0,',','.') ?> đ</td>
                                <td><?= number_format($d->subtotal,0,',','.') ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</main>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>