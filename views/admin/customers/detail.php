<?php
ob_start();
?>

<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Chi tiết khách hàng</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">ID</th>
                    <td><?= $customer->id ?></td>
                </tr>
                <tr>
                    <th>Họ và tên</th>
                    <td><?= $customer->fullname ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= $customer->email ?></td>
                </tr>
                <tr>
                    <th>Điện thoại</th>
                    <td><?= $customer->phone ?></td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td><?= $customer->address ?></td>
                </tr>
                <tr>
                    <th>Ghi chú</th>
                    <td><?= $customer->note ?></td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($customer->status == 1): ?>
                            <span class="badge bg-success">Hoạt động</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td><?= !empty($customer->createdAt) ? date('d/m/Y H:i:s', strtotime($customer->createdAt)) : '-' ?></td>
                </tr>
            </table>
            <div class="d-flex gap-2 mt-3">
                <a href="<?= BASE_URL ?>/admin/customer/edit/<?= $customer->id ?>" class="btn btn-warning">Sửa</a>
                <a href="<?= BASE_URL ?>/admin/customer" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>