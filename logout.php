<?php
session_start();
session_destroy(); // Xóa toàn bộ session
header("Location: login.php"); // Đẩy về trang đăng nhập
exit();
?>