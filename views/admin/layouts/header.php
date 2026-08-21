<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="icon" type="image/svg+xml" href="/MiniShop_HoThiBichNhung/assets/favicon.svg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .nav-link.text-white-50:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.375rem;
        }
    </style>

</head>

<?php
$baseUrl = defined('BASE_URL') ? BASE_URL : '/MiniShop_HoThiBichNhung';
$user = $_SESSION["user"] ?? null;
?>

<body class="bg-light">

    <!-- HEADER -->
    <div class="container-fluid bg-dark text-white d-flex justify-content-between align-items-center px-3 py-2">

        <button id="btnMenu" class="btn btn-outline-light">
            <i class="bi bi-list"></i>
        </button>

        <div class="d-flex align-items-center gap-2">
            <a href="<?= $baseUrl ?>" class="text-white text-decoration-none d-flex align-items-center gap-2" title="Quay lại trang chủ Client">
                <i class="bi bi-person-circle fs-4"></i>
                <span>
                    <?= ($user->fullname ?? 'Quản trị viên') ?>
                </span>
            </a>
            <a href="<?= $baseUrl ?>/admin/logout" class="text-decoration-none text-light ms-2">
                | Đăng xuất
            </a>
        </div>
    </div>
    </div>