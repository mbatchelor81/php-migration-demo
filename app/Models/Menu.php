<?php
namespace Models;

use Core\Database;
use Core\RedisClient;
use PDO;

class Menu
{
    public static function all(): array
    {
        $redis = RedisClient::get();
        $cacheKey = 'menu:all';
        $cached = $redis->get($cacheKey);
        if ($cached) {
            return json_decode($cached, true);
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT id, name, price FROM menu ORDER BY id');
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $redis->setex($cacheKey, 300, json_encode($items));
        return $items;
    }

    public static function find(int $id): ?array
    {
        foreach (self::all() as $item) {
            if ((int) $item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }
}
