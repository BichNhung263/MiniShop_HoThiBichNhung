<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title ?? "Mini Shop" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/Minishop_HoThiBichNhunng/assets/client/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__."/header.php";?>
    <div class ="container-fluid p-4">
        <?= $content ?>
    </div>
    <?php include __DIR__."/footer.php";?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/MiniShop_HoThiBichNhung/assets/client/script.js"></script>
</body>
</html>