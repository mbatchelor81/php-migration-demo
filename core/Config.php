<?php
namespace Core;

class Config
{
    private static array $env;

    public static function get(string $key, $default = null)
    {
        if (!isset(self::$env)) {
            self::load();
        }
        // Prefer actual environment variables (useful inside Docker)
        $envValue = getenv($key);
        if ($envValue !== false) {
            return $envValue;
        }
        return self::$env[$key] ?? $default;
    }

    private static function load(): void
    {
        $root = dirname(__DIR__);
        $envFile = $root . '/.env';
        if (!file_exists($envFile)) {
            $envFile = $root . '/.env.example';
        }
        self::$env = [];
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            [$k, $v] = array_map('trim', explode('=', $line, 2));
            self::$env[$k] = $v;
        }
    }
}
