<?php
$pageTitle = "Danh sách người dùng";
require_once __DIR__ . "/../../../dao/UserDAO.php";

$keyword = trim($_GET["keyword"] ?? "");
$dao = new UserDAO();
$users = $dao->getAll($keyword);
ob_start();
?>
<main class="container my-4">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Danh sách người dùng</h4>
            <a href="create.php" class="btn btn-primary">Thêm người dùng</a>
        </div>

        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-danger"><?= $_GET['error'] ?></div>
        <?php endif; ?>

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
                    <th>Username</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th width="220" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <?= !empty($keyword) ? "Không tìm thấy dữ liệu." : "Chưa có người dùng nào." ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $key => $user): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $user->fullname ?></td>
                            <td><?= $user->username ?></td>
                            <td><?= $user->email ?></td>
                            <td>
                                <?php if ($user->role == 1): ?>
                                    <span class="badge bg-primary">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user->status == 1): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($user->createdAt) ? date('d/m/Y', strtotime($user->createdAt)) : '-' ?></td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $user->id ?>" class="btn btn-info btn-sm text-white me-1">Chi tiết</a>
                                <a href="edit.php?id=<?= $user->id ?>" class="btn btn-warning btn-sm me-1">Sửa</a>
                                <a href="delete.php?id=<?= $user->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
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
