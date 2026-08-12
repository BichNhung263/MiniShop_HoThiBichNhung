

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
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
$user = $_SESSION["user"];
?>

<div class="container-fluid d-flex justify-content-between align-items-center">
<button id="btnMenu" class="btn btn-outline-secondary">
<i class="bi bi-list"></i>
</button>
<div class="d-flex align-items-center gap-2">
<i class="bi bi-person-circle fs-3"></i>
<span>
<?= htmlspecialchars($user->fullname) ?>
</span>
</div>
</div>