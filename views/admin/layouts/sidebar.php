<?php
$baseUrl = "/MiniShop_HoThiBichNhung/views/admin";
?>
<div class="col-md-3 col-lg-2 bg-dark text-white min-vh-100 p-3">
    <a href="#" class="d-flex align-items-center mb-4 text-white text-decoration-none fs-5 fw-bold px-2">
        <i class="bi bi-cart3 fs-4 me-2"></i> Mini Shop
    </a>
    
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="<?= $baseUrl ?>/dashboard.php" class="nav-link text-white-50 py-2 px-3">
                <i class="bi bi-house-door-fill me-3"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?= $baseUrl ?>/categories/index.php" class="nav-link text-white-50 py-2 px-3">
                <i class="bi bi-folder2-open me-3"></i> Danh mục
            </a>
        </li>
        <li>
            <a href="<?= $baseUrl ?>/brands/index.php" class="nav-link text-white-50 py-2 px-3">
                <i class="bi bi-star me-3"></i> Thương hiệu
            </a>
        </li>
        <li>
            <a href="<?= $baseUrl ?>/products/index.php" class="nav-link text-white-50 py-2 px-3">
                <i class="bi bi-bag me-3"></i> Sản phẩm
            </a>
        </li>
        <li>
            <a href="<?= $baseUrl ?>/customers/index.php" class="nav-link text-white-50 py-2 px-3">
                <i class="bi bi-people me-3"></i> Khách hàng
            </a>
        </li>
        <li>
            <a href="<?= $baseUrl ?>/users/index.php" class="nav-link text-white-50 py-2 px-3">
                <i class="bi bi-person me-3"></i> Người dùng
            </a>
        </li>
        <li>
            <a href="<?= $baseUrl ?>/orders/index.php" class="nav-link text-white-50 py-2 px-3">
                <i class="bi bi-clipboard-data me-3"></i> Đơn hàng
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white-50 py-2 px-3">
                <i class="bi bi-box-arrow-right me-3"></i> Đăng xuất
            </a>
        </li>
    </ul>
</div>
