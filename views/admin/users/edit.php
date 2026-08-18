<?php
$pageTitle = "Cập nhật người dùng";

$errors = [];
$dao = new \DAO\UserDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$user = $dao->findById($id);
if (!$user) { header("Location: /MiniShop_HoThiBichNhung/admin/user"); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    \Middleware\CsrfMiddleware::verify();
    $user->fullname = trim($_POST["fullname"] ?? "");
    $user->username = trim($_POST["username"] ?? "");
    $user->email = trim($_POST["email"] ?? "");
    $user->phone = trim($_POST["phone"] ?? "");
    $user->address = trim($_POST["address"] ?? "");
    $user->role = isset($_POST["role"]) ? (int)$_POST["role"] : 0;
    $user->status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;
    $newPassword = trim($_POST["password"] ?? "");
    if ($newPassword != "") $user->password = md5($newPassword);

    if ($user->fullname == "") $errors[] = "Họ và tên không được để trống.";
    if ($user->username == "") $errors[] = "Username không được để trống.";
    if ($user->email == "") $errors[] = "Email không được để trống.";

    if (empty($errors)) {
        if ($dao->update($user)) {
            header("Location: /MiniShop_HoThiBichNhung/admin/user");
            exit();
        } else {
            $errors[] = "Cập nhật thất bại. Vui lòng thử lại!";
        }
    }
}
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Cập nhật người dùng</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <input type="hidden" name="userId" value="<?= $user->id ?>">
                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" name="fullname" value="<?= $user->fullname ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?= $user->username ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu mới <small class="text-muted">(để trống nếu không đổi)</small></label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $user->email ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Điện thoại</label>
                    <input type="text" class="form-control" name="phone" value="<?= $user->phone ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" value="<?= $user->address ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Vai trò</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" value="0" <?= $user->role == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">User</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" value="1" <?= $user->role == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Admin</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $user->status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Hoạt động</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $user->status == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">Ẩn</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <button type="reset" class="btn btn-warning">Làm mới</button>
                    <a href="/MiniShop_HoThiBichNhung/admin/user" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>
