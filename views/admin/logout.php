<?php
session_start();
// Khởi động Session
session_unset();
session_destroy();

// Xóa Cookie ghi nhớ đăng nhập khi người dùng bấm Đăng xuất
if (isset($_COOKIE["remember_user"])) {
    setcookie("remember_user", "", time() - 3600, "/");
}

header("Location: login.php");
exit; 
