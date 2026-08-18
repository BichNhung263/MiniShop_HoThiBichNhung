<?php
// Xác định URL gốc của project
$baseUrl = '/MiniShop_HoThiBichNhung';

// Lấy từ khóa tìm kiếm nếu có
$keyword = $_GET['keyword'] ?? '';
?>

<!-- Bootstrap CSS -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<!-- Bootstrap Icons -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<header class="bg-dark shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand fw-bold d-flex align-items-center"
               href="<?= $baseUrl ?>/views/client/index.php">

                <i class="bi bi-shop me-2 fs-4"></i>
                MiniShop
            </a>

            <!-- BUTTON MOBILE -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#clientNavbar"
                aria-controls="clientNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="clientNavbar">

                <!-- MENU BÊN TRÁI -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- TRANG CHỦ -->
                    <li class="nav-item">
                        <a class="nav-link"
                           href="<?= $baseUrl ?>/views/client/index.php">
                            <i class="bi bi-house-door me-1"></i>
                            Trang chủ
                        </a>
                    </li>

                    <!-- DANH MỤC -->
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="categoryDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="bi bi-grid me-1"></i>
                            Danh mục
                        </a>

                        <ul class="dropdown-menu shadow">

                            <li>
                                <a class="dropdown-item"
                                   href="<?= $baseUrl ?>/views/client/categories/index.php">
                                    Danh mục 1
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="<?= $baseUrl ?>/views/client/categories/index.php">
                                    Danh mục 2
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="<?= $baseUrl ?>/views/client/categories/index.php">
                                    Danh mục 3
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item fw-semibold"
                                   href="<?= $baseUrl ?>/views/client/categories/index.php">
                                    Xem tất cả
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- THƯƠNG HIỆU -->
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="brandDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="bi bi-tags me-1"></i>
                            Thương hiệu
                        </a>

                        <ul class="dropdown-menu shadow">

                            <li>
                                <a class="dropdown-item"
                                   href="<?= $baseUrl ?>/views/client/brands/index.php">
                                    Thương hiệu 1
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="<?= $baseUrl ?>/views/client/brands/index.php">
                                    Thương hiệu 2
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="<?= $baseUrl ?>/views/client/brands/index.php">
                                    Thương hiệu 3
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item fw-semibold"
                                   href="<?= $baseUrl ?>/views/client/brands/index.php">
                                    Xem tất cả
                                </a>
                            </li>

                        </ul>
                    </li>

                </ul>

                <!-- THANH TÌM KIẾM -->
                <form
                    class="d-flex me-lg-3 mb-2 mb-lg-0"
                    action="<?= $baseUrl ?>/views/client/index.php"
                    method="GET"
                >

                    <div class="input-group">

                        <input
                            type="search"
                            name="keyword"
                            class="form-control"
                            placeholder="Tìm kiếm sản phẩm..."
                            value="<?= htmlspecialchars($keyword) ?>"
                        >

                        <button
                            type="submit"
                            class="btn btn-outline-light"
                            title="Tìm kiếm"
                        >
                            <i class="bi bi-search"></i>
                        </button>

                    </div>

                </form>

                <!-- ĐĂNG NHẬP -->
                <a
                    href="<?= $baseUrl ?>/views/auth/login.php"
                    class="btn btn-outline-light me-2 mb-2 mb-lg-0"
                    title="Đăng nhập"
                >
                    <i class="bi bi-person"></i>
                </a>

                <!-- GIỎ HÀNG -->
                <a
                    href="<?= $baseUrl ?>/views/client/cart/index.php"
                    class="btn btn-warning position-relative mb-2 mb-lg-0"
                    title="Giỏ hàng"
                >
                    <i class="bi bi-cart3"></i>

                    <!-- SỐ LƯỢNG SẢN PHẨM -->
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    >
                        0
                        <span class="visually-hidden">
                            sản phẩm trong giỏ hàng
                        </span>
                    </span>
                </a>

            </div>
        </div>
    </nav>
</header>

