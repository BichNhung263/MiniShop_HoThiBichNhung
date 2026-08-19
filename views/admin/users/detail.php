<?php
$pageTitle = "Chi tiết người dùng";

$dao = new \DAO\UserDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$user = $dao->findById($id);
if (!$user) {
    header("Location: /MiniShop_HoThiBichNhung/admin/user");
    exit();
}
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Chi tiết người dùng</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">ID</th>
                    <td><?= $user->id ?></td>
                </tr>
                <tr>
                    <th>Họ và tên</th>
                    <td><?= $user->fullname ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?= $user->username ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= $user->email ?></td>
                </tr>
                <tr>
                    <th>Điện thoại</th>
                    <td><?= $user->phone ?></td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td><?= $user->address ?></td>
                </tr>
                <tr>
                    <th>Vai trò</th>
                    <td>
                        <?php if ($user->role == 1): ?>
                            <span class="badge bg-primary">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">User</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($user->status == 1): ?>
                            <span class="badge bg-success">Hoạt động</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td><?= !empty($user->createdAt) ? date('d/m/Y H:i:s', strtotime($user->createdAt)) : '-' ?></td>
                </tr>
            </table>
            <div class="d-flex gap-2 mt-3">
                <a href="/MiniShop_HoThiBichNhung/admin/user/edit/<?= $user->id ?>" class="btn btn-warning">Sửa</a>
                <a href="/MiniShop_HoThiBichNhung/admin/user" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>