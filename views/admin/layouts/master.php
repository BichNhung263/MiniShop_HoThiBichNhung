<?php
use Middleware\AuthMiddleware;
use Middleware\CsrfMiddleware;
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