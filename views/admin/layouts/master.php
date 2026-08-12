<?php
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
session_start();
AuthMiddleware::handle();
include __DIR__ . "/header.php";
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . "/sidebar.php"; ?>
        <div class="col">
            <?= $content ?>
        </div>
    </div>
</div>
<?php include __DIR__ . "/footer.php"; ?>