<?php
$pageTitle = "Cập nhật khách hàng";
require_once __DIR__ . "/../../../dao/CustomerDAO.php";

$errors = [];
$dao = new CustomerDAO();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$customer = $dao->findById($id);
if (!$customer) { header("Location: index.php"); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer->fullname = trim($_POST["fullname"] ?? "");
    $customer->email = trim($_POST["email"] ?? "");
    $customer->phone = trim($_POST["phone"] ?? "");
    $customer->address = trim($_POST["address"] ?? "");
    $customer->note = trim($_POST["note"] ?? "");
    $customer->status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    if ($customer->fullname == "") $errors[] = "Họ và tên không được để trống.";
    if ($customer->phone == "") $errors[] = "Điện thoại không được để trống.";

    if (empty($errors)) {
        if ($dao->update($customer)) {
            header("Location: index.php");
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
            <h4>Cập nhật khách hàng</h4>
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
                <input type="hidden" name="customerId" value="<?= $customer->id ?>">
                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" name="fullname" value="<?= $customer->fullname ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $customer->email ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Điện thoại</label>
                    <input type="text" class="form-control" name="phone" value="<?= $customer->phone ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" value="<?= $customer->address ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control" name="note" rows="3"><?= $customer->note ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $customer->status == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Hoạt động</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $customer->status == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">Ẩn</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
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
