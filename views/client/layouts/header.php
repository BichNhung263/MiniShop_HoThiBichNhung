<?php

use Composers\HeaderComposer;
$headerData = HeaderComposer::compose();
$categories = $headerData['categories'];
$brands = $headerData['brands'];
$baseUrl = defined('BASE_URL') ? BASE_URL : '/MiniShop_HoThiBichNhung';
$keyword = $_GET['keyword'] ?? '';
?>
<header class="bg-dark shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center"
                href="<?= $baseUrl ?>">
                MiniShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#clientNavbar" aria-controls="clientNavbar" 
            aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="clientNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?= $baseUrl ?>">
                            Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?= $baseUrl ?>/product">
                            Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Danh mục
                        </a>
                        <ul class="dropdown-menu shadow">
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a class="dropdown-item"
                                        href="<?= BASE_URL ?>/category/<?= $category->slug ?>">
                                        <?= ($category->catename) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="brandDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Thương hiệu
                        </a>
                        <ul class="dropdown-menu shadow">
                            <?php foreach ($brands as $brand): ?>
                                <li>
                                    <a class="dropdown-item"
                                        href="<?= BASE_URL ?>/brand/<?= $brand->slug ?>">
                                        <?= ($brand->brandname) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $baseUrl ?>/contact">Liên hệ</a>
                    </li>
                </ul>
                <form class="d-flex me-lg-3 mb-2 mb-lg-0" action="<?= BASE_URL ?>/search" method="GET">
                    <div class="input-group">
                        <input type="search" name="keyword" class="form-control" placeholder="Tìm kiếm sản phẩm..."
                            value="<?= ($keyword) ?>">
                        <button type="submit" class="btn btn-outline-light" title="Tìm kiếm">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <?php if (isset($_SESSION["client_user"])): ?>
                    <a href="<?= $baseUrl ?>/admin/dashboard" class="text-light text-decoration-none me-2 mb-2 mb-lg-0" title="Trang quản trị Admin">
                        <i class="bi bi-person-fill"></i>
                        <?= ($_SESSION["client_user"]["fullname"]) ?>
                    </a>
                    <a href="<?= $baseUrl ?>/logout"
                        class="btn btn-outline-danger me-2 mb-2 mb-lg-0" title="Đăng xuất">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>/login"
                        class="btn btn-outline-light me-2 mb-2 mb-lg-0" title="Đăng nhập">
                        <i class="bi bi-person"></i>
                    </a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/cart"
                    class="btn btn-warning position-relative">
                    <i class="bi bi-cart3"></i>
                    <span id="cartCount"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                </a>
            </div>
        </div>
    </nav>
</header>