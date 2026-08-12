<?php
session_start();
// Khởi động Session
session_unset();
// Xóa các biến dữ liệu đang được lưu trong Session.
session_destroy(); // hủy dữ liệu Session trên Server.
header("Location: login.php");
// Chuyển hướng người dùng về trang đăng nhập.
exit; // Dừng chương trình
