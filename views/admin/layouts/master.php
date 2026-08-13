<?php
use Middleware\AuthMiddleware;
use Middleware\CsrfMiddleware;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
AuthMiddleware::handle();
CsrfMiddleware::generateToken();
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