<?php
namespace Models;

use Core\Database;
use Core\RedisClient;
use PDO;

class Order
{
    public static function create(array $cart): int
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO orders(status, created_at) VALUES("pending", NOW())');
        $stmt->execute();
        $orderId = (int) $pdo->lastInsertId();

        $itemStmt = $pdo->prepare('INSERT INTO order_items(order_id, menu_id, quantity, price) VALUES(:order_id, :menu_id, :qty, :price)');
        foreach ($cart as $menuId => $qty) {
            $item = Menu::find($menuId);
            $itemStmt->execute([
                ':order_id' => $orderId,
                ':menu_id' => $menuId,
                ':qty' => $qty,
                ':price' => $item['price'] ?? 0,
            ]);
        }
        $pdo->commit();

        // push to Redis queue
        RedisClient::get()->rPush('kitchen:queue', $orderId);
        return $orderId;
    }

    public static function markReady(int $orderId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE orders SET status="ready", ready_at = NOW() WHERE id = :id');
        $stmt->execute([':id' => $orderId]);

        // Remove from Redis kitchen queue
        RedisClient::get()->lRem('kitchen:queue', $orderId, 0);
    }

    public static function items(int $orderId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT mi.name, oi.quantity, oi.price FROM order_items oi JOIN menu mi ON mi.id = oi.menu_id WHERE oi.order_id = :id');
        $stmt->execute([':id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function queued(): array
    {
        $redis = RedisClient::get();
        $ids = $redis->lRange('kitchen:queue', 0, -1);
        if (empty($ids)) return [];
        $in = implode(',', array_map('intval', $ids));
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id FROM orders WHERE id IN ($in) ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
