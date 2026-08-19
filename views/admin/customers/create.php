<?php
$pageTitle = "Thêm khách hàng";

$errors = [];
$fullname = $email = $phone = $address = $note = "";
$status = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    \Middleware\CsrfMiddleware::verify();
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $note = trim($_POST["note"] ?? "");
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    if ($fullname == "") $errors[] = "Họ và tên không được để trống.";
    if ($phone == "") $errors[] = "Điện thoại không được để trống.";

    if (empty($errors)) {
        $dao = new \DAO\CustomerDAO();
        $customer = new \Models\Customer($fullname, $email, $phone, $address, $note, $status);
        if ($dao->insert($customer)) {
            header("Location: /MiniShop_HoThiBichNhung/admin/customer");
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
            <h4>Thêm khách hàng mới</h4>
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
                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" name="fullname" value="<?= $fullname ?>">
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
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control" name="note" rows="3"><?= $note ?></textarea>
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
                    <a href="/MiniShop_HoThiBichNhung/admin/customer" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>