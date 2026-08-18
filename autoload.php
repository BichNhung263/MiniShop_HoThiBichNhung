<?php
spl_autoload_register(function ($className) {
    $prefixes = [
        'Controllers\\' => __DIR__ . '/controllers/',
        'DAO\\' => __DIR__ . '/dao/',
        'Models\\' => __DIR__ . '/models/',
        'Middleware\\' => __DIR__ . '/middleware/',
        'Config\\' => __DIR__ . '/config/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        // Kiểm tra class có thuộc namespace này không
        if (str_starts_with($className, $prefix)) {
            // Bỏ phần namespace gốc
            $relativeClass = substr($className, strlen($prefix));
            // Đổi dấu \ thành /
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            // Nếu file tồn tại thì nạp file
            if (file_exists($file)) {
                require_once $file;
            }
            return;
        }
    }
});

if (!defined('BASE_URL')) {
    define('BASE_URL', '/MiniShop_HoThiBichNhung');
}
if (!defined('PRODUCT_IMAGE_URL')) {
    define('PRODUCT_IMAGE_URL', BASE_URL . '/uploads/products/');
}
