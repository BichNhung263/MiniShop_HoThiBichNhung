<?php
$pageTitle = "Cập nhật danh mục";

$categoryDAO = new \DAO\CategoryDAO();
$errors = [];

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$category = $categoryDAO->findById($id);

if (!$category) {
    header("Location: /MiniShop_HoThiBichNhung/admin/category");
    exit();
}

$categoryOld = clone $category;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    \Middleware\CsrfMiddleware::verify();
    $cateName = trim($_POST["cateName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : 1;

    $fileName = $_FILES["image"] ?? "";
    $image = "";
    // Đọc thông tin hình ảnh
    $fileName = $_FILES["image"]["name"] ?? "";
    $tmpName  = $_FILES["image"]["tmp_name"] ?? "";
    $fileSize = $_FILES["image"]["size"] ?? 0;
    $Error    = $_FILES["image"]["error"] ?? 0;

    $errors = [];
    if ($cateName == "") $errors[] = "Tên danh mục không được để trống.";
    if ($slug == "") $errors[] = "Slug không được để trống.";

    // Validation hình ảnh
    if ($fileName != "") {
        if ($Error != UPLOAD_ERR_OK) {
            $errors[] = "Upload hình ảnh không thành công.";
        }

        $allowExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowExtensions)) {
            $errors[] = "Chỉ cho phép file JPG, JPEG, PNG hoặc WEBP.";
        }

        $maxSize = 200 * 1024;
        if ($fileSize > $maxSize) {
            $errors[] = "Kích thước hình ảnh <= 200 KB.";
        }
    }

    if (empty($errors)) {
        $image = $categoryOld->image;

        // Có chọn hình ảnh mới
        if ($fileName != "") {
            // Lấy phần mở rộng của file
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            // Đổi tên file
            $image = time() . "_" . $slug . "." . $extension;
            // Thư mục & đường dẫn hình ảnh mới
            $uploadPath = __DIR__ . "/../../../uploads/categories/" .$image;
            // Xóa hình ảnh cũ (nếu có)
            if (!empty($categoryOld->image)) {
                $oldImage = __DIR__ . "/../../../uploads/categories/" . $categoryOld->image;
                if (file_exists($oldImage)) {
                    unlink($oldImage); // xóa file
                }
            }
            // Upload hình ảnh mới
            move_uploaded_file($tmpName, $uploadPath);
        }

        $category->catename = $cateName;
        $category->slug = $slug;
        $category->description = $description;
        $category->image = $image;
        $category->status = $status;

        if ($categoryDAO->update($category)) {
            header("Location: /MiniShop_HoThiBichNhung/admin/category");
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
            <h4>Cập nhật danh mục</h4>
        </div>
        <div class="card-body">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <input type="hidden" name="categoryId" value="<?= $category->id ?>">

                <div class="text-center mb-3" id="preview">
                    <?php if (!empty($category->image)): ?>
                        <img src="/MiniShop_HoThiBichNhung/uploads/categories/<?= $category->image ?>" class="img-thumbnail" width="150" id="preview">
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="cateName" value="<?= $category->catename ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" value="<?= $category->slug ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="3" class="form-control"><?= $category->description ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="1" <?= $category->status == 1 ? "checked" : "" ?>>
                        <label class="form-check-label">Hiển thị / Hoạt động</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="0" <?= $category->status == 0 ? "checked" : "" ?>>
                        <label class="form-check-label">Ẩn / Ngừng hoạt động</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <button type="reset" class="btn btn-warning">Làm mới</button>
                    <a href="/MiniShop_HoThiBichNhung/admin/category" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/master.php";
?>
