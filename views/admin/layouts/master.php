<?php
require_once __DIR__ . '/../../../models/User.php';
//require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../middleware/CsrfMiddleware.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//AuthMiddleware::handle();
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