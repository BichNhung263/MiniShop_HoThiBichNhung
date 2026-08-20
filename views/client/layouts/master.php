<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Mini Shop" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/MiniShop_HoThiBichNhung/assets/client/style.css?v=2" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . "/header.php"; ?>
    <div class="container-fluid p-4">
        <?php if (!empty($_SESSION["order_success"])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?= $_SESSION["order_success"] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION["order_success"]); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION["order_error"])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle-fill"></i> <?= $_SESSION["order_error"] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION["order_error"]); ?>
        <?php endif; ?>
        <?= $content ?>
    </div>
    <?php include __DIR__ . "/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/client/script.js"></script>
    <script>
        const BASE_URL = "<?= defined('BASE_URL') ? BASE_URL : '/MiniShop_HoThiBichNhung' ?>";
    </script>
    <script src="<?= defined('BASE_URL') ? BASE_URL : '/MiniShop_HoThiBichNhung' ?>/public/js/cart.js"></script>
</body>

</html>