<?php
include "header.php";
?>
<!-- Wrapper: sidebar + content -->
<div style="display:flex; min-height: calc(100vh - 56px);">

    <!-- Sidebar bên trái -->
    <?php include "sidebar.php"; ?>

    <!-- Nội dung trang chính -->
    <div style="flex:1; padding: 25px; background: #f4f6f9;">
        <?= $content ?>
    </div>

</div>

<?php include "footer.php"; ?>