<?php
namespace Controllers;

use Models\Order;

class CheckoutController
{
    public function checkout(): void
    {
        // Create order record, push to Redis queue
        $cart = $_SESSION['cart'] ?? [];
        if (!$cart) {
            header('Location: /cart');
            exit;
        }

        // Simulate order creation
        $orderId = Order::create($cart);
        unset($_SESSION['cart']);

        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/checkout.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }
}
