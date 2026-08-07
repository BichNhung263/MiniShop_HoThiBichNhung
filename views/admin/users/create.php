<?php
$pageTitle = "Thêm người dùng";
require_once __DIR__ . "/../../../dao/UserDAO.php";

$errors = [];
$fullname = $username = $password = $email = $phone = $address = "";
$role = 0; $status = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $role = isset($_POST["role"]) ? (int)$_POST["role"] : 0;
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    if ($fullname == "") $errors[] = "Họ và tên không được để trống.";
    if ($username == "") $errors[] = "Username không được để trống.";
    if ($password == "") $errors[] = "Mật khẩu không được để trống.";
    if ($email == "") $errors[] = "Email không được để trống.";

    if (empty($errors)) {
        $dao = new UserDAO();
        $user = new User($fullname, $username, md5($password), $email, $phone, $address, $role, $status);
        if ($dao->insert($user)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Thêm thất bại. Vui lòng thử lại!";
        }
    }
}
ob_start();
?>
<main class="container my-4">
    <div class="card">
        <div class="card-header">
            <h4>Thêm người dùng mới</h4>
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
                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" name="fullname" value="<?= $fullname ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?= $username ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $email ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Điện thoại</label>
                    <input type="text" class="form-control" name="phone" value="<?= $phone ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" value="<?= $address ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Vai trò</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" value="0" <?= $role == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">User</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" value="1" <?= $role == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Admin</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Hoạt động</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $status == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">Ẩn</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                    <button type="reset" class="btn btn-warning">Làm mới</button>
                    <a href="index.php" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
