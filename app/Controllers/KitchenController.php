<?php
namespace Controllers;

use Models\Order;

class KitchenController
{
    public function showQueue(): void
    {
        $orderIds = Order::queued();
        $queue = [];
        foreach ($orderIds as $id) {
            $queue[$id] = Order::items($id);
        }
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/kitchen.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }

    public function markReady(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            Order::markReady($id);
        }
        header('Location: /kitchen');
        exit;
    }
}
