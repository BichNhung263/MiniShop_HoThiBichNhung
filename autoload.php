<?php
spl_autoload_register(function ($className) {
    $prefixes = [
        'Controllers\\' => __DIR__ . '/controllers/',
        'DAO\\'         => __DIR__ . '/dao/',
        'Models\\'      => __DIR__ . '/models/',
        'Middleware\\'  => __DIR__ . '/middleware/',
        'Config\\'      => __DIR__ . '/config/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        // Kiểm tra class có thuộc namespace này không
        if (str_starts_with($className, $prefix)) {
            // Bỏ phần namespace gốc
            $relativeClass = substr($className, strlen($prefix));
            // Đổi dấu \ thành /
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
            // Hỗ trợ thư mục admin/client viết thường trên hệ điều hành Linux
            $fileLower = $baseDir . strtolower(str_replace('\\', '/', $relativeClass)) . '.php';
            if (file_exists($fileLower)) {
                require_once $fileLower;
                return;
            }
        }
    }
});