<?php
namespace Config;

class Cache
{
    private static string $cacheDir = __DIR__ . '/../storage/cache/';

    private static function init(): void
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
    }

    public static function get(string $key)
    {
        self::init();
        $filePath = self::$cacheDir . md5($key) . '.json';

        if (!file_exists($filePath)) {
            return null;
        }

        $content = file_get_contents($filePath);
        $data = json_decode($content, true);

        if (!$data || !isset($data['expires_at'])) {
            return null;
        }

        if (time() > $data['expires_at']) {
            @unlink($filePath);
            return null;
        }

        return unserialize($data['payload']);
    }

    public static function set(string $key, $value, int $ttl = 300): bool
    {
        self::init();
        $filePath = self::$cacheDir . md5($key) . '.json';

        $data = [
            'expires_at' => time() + $ttl,
            'payload'    => serialize($value)
        ];

        return (bool)file_put_contents($filePath, json_encode($data));
    }

    public static function remember(string $key, int $ttl, callable $callback)
    {
        $cached = self::get($key);
        if ($cached !== null) {
            return $cached;
        }

        $value = $callback();
        self::set($key, $value, $ttl);
        return $value;
    }

    public static function clear(): void
    {
        self::init();
        $files = glob(self::$cacheDir . '*.json');
        if ($files) {
            foreach ($files as $file) {
                @unlink($file);
            }
        }
    }
}
