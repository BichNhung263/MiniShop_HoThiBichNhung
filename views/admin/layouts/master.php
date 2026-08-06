<?php include __DIR__ . "/header.php"; ?>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . "/sidebar.php"; ?>
        <div class="col p-4">
            <!-- Thanh tiêu đề & Xin chào Admin ở góc trên bên phải -->
            <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                <h4 class="mb-0 fw-bold"><?= $pageTitle ?? 'Dashboard' ?></h4>
                <div class="text-secondary">
                    <i class="bi bi-person-circle fs-5 me-1"></i> Xin chào, <strong>Admin</strong>
                </div>
            </div>

            <?= $content ?>
        </div>
    </div>
</div>
<?php include __DIR__ . "/footer.php"; ?>